<?php
namespace App\Jobs;
use Log;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMqtt extends Job implements SelfHandling//, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
	// use SerializesModels;
    //用户信息
    protected $user;
    // //MQTT用户名
    // protected $mqttUser;
    // //MQTT密码
    // protected $mqttPass;
    //内容 array
    protected $content;
    //主题 array
    protected $topic;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Admin $admin, $topic, $content)
    {
        //
        $this->admin = $admin;
        $this->content = $content;
        $this->topic = $topic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		//设置失败次数如果超过三次则直接失败
		if ($this->attempts() > 3) {
			return false;
	    }
        $mqtt = self::connectionMQTT();
        if ($mqtt == false) {
            Log::error('{系统出现了错误：在' . __FILE__ .__LINE__ . '代码处;原因：MQTT没有通过认证' );
            return false;
        }
        
        $topicCount = count($this->topic, 0);
        $contentCount = count($this->content, 0);

        if ($topicCount != $contentCount) {
            Log::error('{系统出现了错误：在' . __FILE__ . __LINE__ . '代码处;原因：提交的TOPIC数组和MESSGAE数组数据量不统一');
            return false;
        }
		var_dump(__FILE__);
		var_dump($topicCount);
        for ($i = 0; $i < $topicCount; $i ++) {
			var_dump(__FILE__);
			var_dump($mqtt->publish($this->topic[$i], $this->content[$i]));		
        }	
		//销毁变量
		$mqtt->disconnect();
		unset($mqtt);
		return 1;
    }
	
    //连接MQTT
    private function connectionMQTT()
    {
        require_once app_path() . '/Plugin/mqtt-master/spMQTT.class.php';		

		$mqtt = \spMQTT::getInstance();
		if ($mqtt != false) {
			echo __FILE__;
			$connected = $mqtt->connect();
			if (!$connected) {
				return false;
			}
			return $mqtt;
		}
        $mqtt = new \spMQTT(MQTT_SERVER);

        \spMQTTDebug::Disable();

        $mqtt->setAuth(MQTT_USER, MQTT_PASSWD);
        $mqtt->setKeepalive(3600);
        $connected = $mqtt->connect();
        if (!$connected) {
            return false;
        }

        return $mqtt;
    }   
}
