<?php
// session_start();
// $_SESSION['i'] = 0;

require(__DIR__ . '/../spMQTT.class.php');

// $mqtt = new spMQTT('tcp://test.mosquitto.org:1883/');
$mqtt = new \spMQTT('tcp://192.168.100.166:1883/');
spMQTTDebug::Enable();

$mqtt->setAuth('1', '1');
$mqtt->setKeepalive(3600);
$connected = $mqtt->connect();
if (!$connected) {
    die("Not connected\n");
}

function getMillisecond() {
list($t1, $t2) = explode(' ', microtime());
return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}

// $topics['foo/#'] = 1;
$topics['#'] = 1;
$mqtt->subscribe($topics);

#$mqtt->unsubscribe(array_keys($topics));

$mqtt->loop('default_subscribe_callback');

/**
 * @param spMQTT $mqtt
 * @param string $topic
 * @param string $message
 */
function default_subscribe_callback($mqtt, $topic, $message) {
	
    printf("Message received: Topic=%s, Message=%s\n", $topic, $message);
	var_dump(iconv('utf-8','gb2312', $message));
	// var_dump($mqtt);
	//这一段可以用switch分隔
	//@todo 这里要验证权限
	if ($topic === 'foo/bar') {
		if ($message === 'ok') {	
			//DB INSERT UPDATE操作
			$msg = str_repeat('1234567890', 1);
			# mosquitto_sub -t 'sskaje/#'  -q 1 -h test.mosquitto.org
			//@todo PHP发布到SETTING端
			//@todo 主题名应该符合一定规则
			$mqtt->publish('zzx', $msg, 0, 1, 0, 1);
		
		}
	} else {

		echo 'TIME:';
		print_r(getMillisecond());
	}
}
