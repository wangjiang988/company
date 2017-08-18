<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserResetMail;
use App\User;

class SendReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * 任务运行的超时时间。
     *
     * @var int
     */
    public $timeout = 60;
    /**
     * 任务最大尝试次数
     *
     * @var int
     */
    public $tries = 5;
    protected $email;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user,$email)
    {
        //
        $this->email = $email;
        $this->user  = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email,'华车')->send(new UserResetMail($this->user,$this->email));
        //
    }

    /**
     * 要处理的失败任务。
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // 给用户发送失败通知，等等...
    }
}
