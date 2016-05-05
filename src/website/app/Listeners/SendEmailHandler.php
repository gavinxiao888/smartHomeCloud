<?php namespace App\Listeners;

use App\Events\SendEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
//use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Contracts\Queue\ShouldBeQueued;
class SendEmailHandler {
    //写入邮件模板中的数据
    private $data = [];
    //邮件的收件者数组
    private $person = [];
    //邮件的视图位置
    private $view = '';
    //邮件的主题
    private $subject = '';
    //邮件的抄送者:默认空数组
    private $cc = [];
    //邮件中包含的附件:默认为空数组
    private $attachment = [];
    //附件的mime数组
    private $mime = [];

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  SendEmail  $event
	 * @return void
	 */
	public function handle(SendEmail $event)
	{
        $this->data = $event->data;
        $this->person = $event->person;
        $this->view = $event->view;
        $this->subject = $event->subject;

        $this->cc = $event->cc;
        $this->attachment = $event->attachment;
        $this->mime = $event->mime;

        if ($event->mode){
           return self::queueEmail();
        } else {
            var_dump(__FILE__.__LINE__);
           return self::generalEmail();
        }
	}

    /**
     * @power 以queue方式发送EMAIL
     * @todo 遇到的问题是不知道怎么弄监听不到发送失败，失败信息一直很难理解
     */
    private function queueMail_bak()
    {
        $mailId = Mail::queue($this->view, $this->data, function($message){
            $message->to($this->person)->subject($this->subject)->cc($this->cc);
            $count = count($this->attachment, 0);

            //循环调用附件
            for ($i = 0; $i < $count; $i++){
                $message->attach($this->attachment[$i], $this->mime[$i]);
            }
        });
        return $mailId;
    }

    /**
     * @power 使用QUEUE方式发送Email
     * 还需要考量一下：到底是用这种方式还是用QUEUE+COMMAND的方式
     */
    private function queueEmail()
    {
        //压入队列
        return Queue::push(function($job){
            $result = $this->generalEmail();
            $job->delete();
			// var_dump($job);
			// file_put_contents(public_path() . '/job.txt', var_export($job));
            return $result;
        });
    }


    /**
     * @power 以常规方式发送Email
     */
    private function generalEmail()
    {
        //使用try记录错误异常，并尽可能处理
        try {
            $mailId = Mail::send($this->view, $this->data, function ($message) {
                $message->to($this->person)->subject($this->subject)->cc($this->cc)->from("854850613@qq.com","zzx");
                $count = count($this->attachment, 0);
                //循环调用附件
                for ($i = 0; $i < $count; $i++) {
                    $message->attach($this->attachment[$i], $this->mime[$i]);
                }
            });
            /**
             * 这里要insert到表里面去
             */
//            DB::connection('commonmysql')->INSERT('INSERT INTO `email_log`(`init_time`, `email`, `content`)');
        } catch (\Exception $e){
            //根据异常类名
            switch (get_class($e)) {
                //收件者出现错误
                case 'Swift_RfcComplianceException':
//                    Address in mailbox given [] does not comply with RFC 2822, 3.6.2.
                    $e->getMessage();
                    break;
                //没有V文件
                case 'InvalidArgumentException':
//                    View [.password] not found.
                    $e->getMessage();
                    break;
                case 'ErrorException':
//                    Missing argument 1 for Illuminate\Mail\Message::subject(), called in D:\wamp\www\lv5_everyoov2\app\Http\routes.php on line 292 and defined
                    $e->getMessage();
                    break;
                //语法错误
                case 'FatalErrorException':
                    break;
            }
            /**
             * 发生异常要UPDATE数据
             */
//            DB::connection('commonmysql')->UPDATE('UPDATE');

//            var_dump($e);
            return 0;
        }
        return $mailId;
    }
}
?>
