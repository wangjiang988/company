<?php

namespace App\Com\Hwache\Upload;
use zgldh\QiniuStorage\QiniuStorage;
use zgldh\QiniuStorage\QiniuUrl;
class qiniu
{
    protected $disk;
    public function __construct()
    {
        $this->disk = QiniuStorage::disk('qiniu');
    }

    /**
     * 获取上传token
     * @return bool
     */
    public function getUploadTonke($key=null)
    {
       return $this->disk ->uploadToken($key);
    }
    /**
     * @param $filePathName 上传文件的本地缓存路径 $request->file('filename')->getPathname();
     * @param $token
     * @param null $contents 可以随便设置，貌似没什么用
     */
    public function upload($filePathName,$token,$contents=null)
    {
        $fileTo2  = $this->setImg2($filePathName);
        $isUpload = $this->disk ->put($token, $fileTo2);
        if($isUpload){
            return $this->getImgUrl($token,$contents);
        }else{
            return false;
        }
    }

    /**
     * @param $token
     * @param null $contents
     * @return mixed
     */
    public function getImgUrl($token,$contents=null){
        $imgUrl = $this->disk ->imagePreviewUrl($token,$contents)->getUrl();
        $urlObj = new QiniuUrl($imgUrl);
        $url = $urlObj->jsonSerialize();
        //$url = $urlObj->getUrl();
        return $url;
    }
    /** 获取上传图片的详细信息
     * @param $token
     * @return mixed
     */
    public function getImgInfo($token)
    {
        return $this->disk ->imageInfo($token);
    }

    /**验证文件是否上传成功
     * @param $token
     * @return bool
     */
    public function isFile($token)
    {
        return $this->disk ->exists($token);
    }
    /** 获取文件内容
     * @param $token
     * @return string
     */
    public function getFile($token){
        return $this->disk ->get($token);
    }

    /**删除图片
     * @param $tokens
     * @return bool
     */
    public function delImg($tokens){
        return $this->disk->delete('file.jpg');
    }
    /**
     * 读取二进制文件流
     * @param $fileName
     * @param $filesize
     * @return string
     */
    private function setImg2($fileName){
        //$imgType = strtolower($type);
        //header( "Content-type: {$imgType}");
        $handle=fopen($fileName,"r");//使用打开模式为r
        $content=fread($handle,filesize($fileName));//读为二进制
        fclose($handle);                    //关闭文件
        return $content;
    }
    /*
    $disk = \Storage::disk('qiniu');
    $disk->exists('file.jpg');                      //文件是否存在
    $disk->get('file.jpg');                         //获取文件内容
    $disk->put('file.jpg',$contents);               //上传文件
    $disk->put('file.jpg',fopen('path/to/big.jpg','r+')); //分段上传文件。建议大文件>10Mb使用。
    $disk->prepend('file.log', 'Prepended Text');   //附加内容到文件开头
    $disk->append('file.log', 'Appended Text');     //附加内容到文件结尾
    $disk->delete('file.jpg');                      //删除文件
    $disk->delete(['file1.jpg', 'file2.jpg']);
    $disk->copy('old/file1.jpg', 'new/file1.jpg');  //复制文件到新的路径
    $disk->move('old/file1.jpg', 'new/file1.jpg');  //移动文件到新的路径
    $size = $disk->size('file1.jpg');               //取得文件大小
    $time = $disk->lastModified('file1.jpg');       //取得最近修改时间 (UNIX)
    $files = $disk->files($directory);              //取得目录下所有文件
    $files = $disk->allFiles($directory);               //这个没实现。。。
    $directories = $disk->directories($directory);      //这个也没实现。。。
    $directories = $disk->allDirectories($directory);   //这个也没实现。。。
    $disk->makeDirectory($directory);               //这个其实没有任何作用
    $disk->deleteDirectory($directory);             //删除目录，包括目录下所有子文件子目录

    $disk->getDriver()->uploadToken();                          //获取上传Token
    $disk->getDriver()->uploadToken('file.jpg');                //获取上传Token
    $disk->getDriver()->downloadUrl('file.jpg');                //获取下载地址
    $disk->getDriver()->downloadUrl('file.jpg')
    ->setDownload('foo.jpg');                 //获取下载地址，文件名为 foo.jpg
    $disk->getDriver()->downloadUrl('file.jpg', 'https');       //获取HTTPS下载地址
    $disk->getDriver()->privateDownloadUrl('file.jpg');         //获取私有bucket下载地址
    $disk->getDriver()->privateDownloadUrl('file.jpg', 'https');//获取私有bucket的HTTPS下载地址
    $disk->getDriver()->privateDownloadUrl('file.jpg',
    [
    'domain'=>'https',
    'expires'=>3600
    ]);                     //获取私有bucket的HTTPS下载地址。超时 3600 秒。
    $disk->getDriver()->avInfo('file.mp3');                     //获取多媒体文件信息
    $disk->getDriver()->imageInfo('file.jpg');                  //获取图片信息
    $disk->getDriver()->imageExif('file.jpg');                  //获取图片EXIF信息
    $disk->getDriver()->imagePreviewUrl('file.jpg','imageView2/0/w/100/h/200');                         //获取图片预览URL
    $disk->getDriver()->privateImagePreviewUrl('file.jpg','imageView2/0/w/100/h/200');                  //获取私有bucket图片预览URL
    $disk->getDriver()->verifyCallback('application/x-www-form-urlencoded', $request->header('Authorization'), 'callback url', $request->getContent());//验证回调内容是否合法
    $disk->getDriver()->persistentFop('file.flv','avthumb/m3u8/segtime/40/vcodec/libx264/s/320x240');   //执行持久化数据处理
    $disk->getDriver()->persistentFop('file.flv','fop','队列名');   //使用私有队列执行持久化数据处理
    $disk->getDriver()->persistentStatus($persistent_fop_id);       //查看持久化数据处理的状态。
    $disk->getDriver()->fetch('http://abc.com/foo.jpg', 'bar.jpg'); //调用fetch将 foo.jpg 数据以 bar.jpg 的名字储存起来。
    $disk->getDriver()->qetag();
    */
}
