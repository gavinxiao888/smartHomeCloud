<?php namespace App\Listeners;

use App\Events\SendMsg;
use Illuminate\Support\Facades\Queue;

//use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Contracts\Queue\ShouldBeQueued;

class SendMsgHandler
{


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
     * @param  SendMsg $event
     * @return void
     */
    public function handle(SendMsg $event)
    {
        if ($event->mode) {
            return $this->queueSendNote($event);
        } else {
            return $this->generalSendNote($event);
        }
    }

    /**
     * @power 队列发送短信
     */
    private function queueSendNote($event)
    {
        Queue::push(function ($job) use ($event) {
            $this->generalSendNote($event);
            $job->delete();
        });
    }

    /**
     * @power 普通发送短信
     * 默认采用变量短信发送方式
     */
    private function generalSendNote($event)
    {
        $post_data = array();
        $post_data['account'] = SMS_USER;
        $post_data['pswd'] = SMS_PASSWD;
        $post_data['msg'] = $event->content;
        $post_data['needstatus'] = 'true';
//        $post_data['product'] = '39207';
        $url = 'http://222.73.117.158/msg/HttpVarSM';
        $o = '';
        foreach ($post_data as $k => $v) {
            $o .= $k . '=' . urlencode($v) . '&';
        }
        //设置变量短信发送参数
        $o .= 'params=';
        $count = count($event->param, 0);
        //
        for ($i = 0; $i < $count; $i++) {
            $o .= $event->receiver[$i] . ',';
            foreach ($event->param[$i] as $k => $v) {
                $o .= urlencode($v) . ',';
            }
            $o = substr($o, 0, -1) . ';';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $o);
        $result = curl_exec($ch);
        logger();
        var_dump($result);
        curl_close($ch);
        return $result;
    }

}
