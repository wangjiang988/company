<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class UserResetMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $email;
    protected $token;
    protected $data;
    protected $templatePath;
    protected $toSubject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$email=null,$token=null,$options=null)
    {
        $this->user         = $user;
        $this->email        = is_null($email) ? $user->email : $email;
        $this->token        = $token;
        $this->templatePath = $this->getTemplatePath(isset($options['template_path']) ? $options['template_path'] : null);
        $this->data         = $this->getData(isset($options['data']) ? $options['data'] : null);
        $this->toSubject    = $this->getSubject(isset($options['subject']) ? $options['subject'] : null);
    }

    /**
     * 设置邮件主题
     * @param null $subject
     * @return null|string
     */
    private function getSubject($subject=null){
        if(!is_null($subject)) return $subject;

        return is_null($this->token) ? '重置密码-验证码' : '重置密码';
    }

    /**
     * 设置或获取模板内容
     * @param null $template_path
     * @return null|string
     */
    private function getTemplatePath($template_path=null){
        if(!is_null($template_path)) return $template_path;

        return is_null($this->token) ? 'emails.reset-code' : 'emails.password';
    }
    /**
     * 获取或设置发送短信的内容变量数组
     * @param null $data
     * @return array|null
     */
    private function getData($data=null)
    {
        if (! is_null($data)) return $data;

        if (is_null($this->token)) {
            $cacheName = 'userCacheEMail'.$this->user->id;
            if(Cache::has($cacheName)){
                $data = Cache::get($cacheName);
            }else{
                $code = get_rand(1,6);
                $data = [
                    'title' => '苏州华车',
                    'url'   => url('/'),
                    'code'  => $code,
                    'date'  => '一天',
                    'verifiy'=>0,
                    'email'=>$this->email,
                    'sendDate'=> Carbon::now()->toDateTimeString()
                ];
            }
            //存错邮件验证码
            emailCacheCheck(['user_id'=>$this->user->id,'code'=>$data['code'],'email'=>$this->email]);
        }else{
            $data = [
                'url'   => url('/'),
                'title' => '苏州华车',
                'link'  => route('mail.emailReset', $this->token)
            ];
        }
       return $data;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->toSubject) ->markdown($this->templatePath,$this->data);
    }
}
