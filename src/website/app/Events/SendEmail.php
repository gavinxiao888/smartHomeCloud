<?php namespace App\Events;

//use App\Events\Event;
//
//use Illuminate\Queue\SerializesModels;
use App\Events\Event;



class SendEmail extends Event {

//	use SerializesModels;

    //写入邮件模板中的数据
    public $data = [];
    //邮件的收件者数组
    public $person = [];
    //邮件的视图位置
    public $view = '';
    //邮件的主题
    public $subject = '';
    //发送邮件的方式: true：queue方式发送邮件;false:普通方式发送邮件
    public $mode = true;
    //邮件中包含的附件:默认为空数组
    public $attachment = [];
    //附件的mime数组
    public $mime = [];
    //发送邮件的抄送者
    public $cc = [];


	/**
	 * Create a new event instance.
     * 设置参数
	 * @return void
	 */
	function __construct( $person,  $view,  $data,  $subject, $cc = [], $attachment = [],  $mime = [], $mode = false)
	{
        $this->data = $data;
        $this->person = $person;
        $this->view = $view;
        $this->subject = $subject;

        $this->cc = $cc;
        $this->attachment = $attachment;
        $this->mode = $mode;
        $this->mime = $mime;
        var_dump($person);
	}

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

}
