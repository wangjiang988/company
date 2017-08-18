<?php

class Qiniu
{

    protected $options = array(
        'access_key' => 'JeSMpQICopUs5oL9RbzjxhKc8-b1P7sTe1MS0MHr',
        'secret_key' => 'CO1i-carvpEj17c6XkLHN3SgCx-dymqb6B6PRxR0',
        'bucket' => 'hwache',
        'domain' => null,
        'timeout' => 3600,
        'is_private' => false,
        'rs_url' => 'http://rs.qiniu.com',
        'rsf_url' => 'http://rs.qiniu.com',
        'upload_url' => 'http://upload.qiniu.com',

    );

    public function __construct(array $options)
    {
        $this->options = array_merge($this->options, $options);
        if (!$this->access_key || !$this->secret_key || !$this->bucket) {
            throw new Exception('缺少参数');
        }
        if (!$this->domain) $this->domain = 'http://' . $this->bucket . '.qiniudn.com/';
    }


    public static function make(array $options = [])
    {
        return new static($options);
    }

    # 查看文件信息 (putTime 字段被转成科学计数法 自行用number_format解决)
    public function info($key)
    {
        $url = $this->rs_url . '/stat/' . $this->encode("{$this->bucket}:{$key}");
        $token = $this->accessToken($url);
        return $this->getRequest($url, $token);
    }

    # 复制文件
    public function copy($key, $target)
    {
        $url = $this->rs_url . '/copy/' . $this->encode("{$this->bucket}:{$key}") . '/' . $this->encode("{$this->bucket}:{$target}");
        $token = $this->accessToken($url);
        return $this->getRequest($url, $token);
    }

    # 移动文件(重命名)
    public function move($key, $newKey)
    {
        $url = $this->rs_url . '/move/' . $this->encode("{$this->bucket}:{$key}") . '/' . $this->encode("{$this->bucket}:{$newKey}");
        $token = $this->accessToken($url);
        return $this->getRequest($url, $token);
    }

    # 删除文件
    public function delete($key)
    {
        $url = $this->rs_url . '/delete/' . $this->encode("{$this->bucket}:{$key}");
        $token = $this->accessToken($url);
        return $this->getRequest($url, $token);
    }

    # 列举资源 (see:http://developer.qiniu.com/docs/v6/api/reference/rs/list.html)
    public function lists($query = array())
    {
        $query = array_merge(array(
            'bucket' => $this->bucket,
            'limit' => 1000,
            'perfix' => '',
            'delimiter' => '',
            'marker' => ''
        ), $query);

        $url = $this->rsf_url . '/list?' . http_build_query($query);
        $token = $this->accessToken($url);
        return $this->getRequest($url, $token);
    }

    /**
     * 文件上传
     * @param  string $path 文件路径
     * @param  string $key 云端文件名
     * @return array
     */
    public function upload($path, $key)
    {
        $token = $this->uploadToken();
        return $this->postRequest($token, $path, $key);
    }

    /**
     * 文件下载
     * @param  string $key
     * @param  boolean $private
     * @return array
     */
    public function download($key, $private = false)
    {
        $url = $this->domain . str_replace("%2F", "/", rawurlencode($key));
        if ($private) {
            $param = array('e' => time() + $this->timeout);
            $url = $url . '?' . http_build_query($param);
            $token = $this->sign($url);
            $url = "{$url}&token={$token}";
        }
        return $url;
    }

    # 访问令牌
    public function accessToken($url, $body = '')
    {
        $url = parse_url($url);
        $data = '';
        if (isset($url['path'])) {
            $data = $url['path'];
        }
        if (isset($url['query'])) {
            $data .= '?' . $url['query'];
        }
        $data .= "\n";

        if ($body) {
            $data .= $body;
        }
        return $this->sign($data);
    }

    # 上传令牌
    public function uploadToken($policy = array())
    {
        // 上传策略 http://developer.qiniu.com/docs/v6/api/reference/security/put-policy.html
        $policy = array_merge(array(
            'scope' => $this->bucket,
            'deadline' => time() + $this->timeout
        ), $policy);

        $data = $this->encode(json_encode($policy));
        return $this->sign($data) . ':' . $data;
    }

    public function sign($data)
    {
        $sign = hash_hmac('sha1', $data, $this->secret_key, true);
        return $this->access_key . ':' . $this->encode($sign);
    }

    # get 请求
    private function getRequest($url, $token)
    {
        $headers[] = "Authorization: QBox {$token}";

        $oCurl = curl_init();
        if (stripos($url, "https://") !== false) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, true);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($oCurl, CURLOPT_HEADER, true);//输出头信息
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, $headers);

        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

    # post 文件请求
    private function postRequest($token, $file, $key)
    {
        $url = $this->options['upload_url'];
        $param = array(
            'file' => file_get_contents($file),
            'token' => $token,
            'key' => $key,
        );
        $headers = array("content-type"=>"multipart/form-data;");

        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, true);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_HEADER, true);//输出头信息
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $param);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, $headers);

        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

    # URL安全的Base64编码
    public function encode($str)
    {
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($str));
    }

    public function decode($str)
    {
        $find = array('-', '_');
        $replace = array('+', '/');
        return base64_decode(str_replace($find, $replace, $str));
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->options)) {
            return $this->options[$key];
        }
    }
}