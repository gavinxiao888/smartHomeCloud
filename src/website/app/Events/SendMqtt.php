<?php namespace App\Events;

//use App\Events\Event;

//use Illuminate\Queue\SerializesModels;

class SendMqtt extends Event {

//	use SerializesModels;
    //接受者
    public $receiver = [];
    //发送内容
    public $content = '';
    //发送模式，默认为队列发送
    public $mode = true;
    //变量参数
    public $param = [];


	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($receiver, $content, $param, $mode = true)
	{
		//
        $this->content = $content;
        $this->receiver = $receiver;
        $this->mode = $mode;
        $this->param = $param;
	}

}
