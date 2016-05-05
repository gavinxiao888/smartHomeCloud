<?php

// function getMillisecond() {
// list($t1, $t2) = explode(' ', microtime());
// return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
// }
// print_r(getMillisecond());

require_once(__DIR__ . '/../spMQTT.class.php');

// $mqtt = new spMQTT('tcp://test.mosquitto.org:1883/');
$mqtt = new spMQTT('tcp://192.168.100.166:1883/');

spMQTTDebug::Enable();

$mqtt->setAuth('1', '1');
$connected = $mqtt->connect();
if (!$connected) {
    die("Not connected\n");
}


$mqtt->ping();

$msg = str_repeat('我们', 1);

# mosquitto_sub -t 'sskaje/#'  -q 1 -h test.mosquitto.org
// for($i = 0; $i < 10; $i ++) {
	// echo 'COUNT:' . $i;
	$mqtt->publish('foo/bar', $msg, 0, 1, 0, 1);
// }
// sleep(10);

// $msg = str_repeat('1234567890', 15);

// # mosquitto_sub -t 'sskaje/#'  -q 1 -h test.mosquitto.org
// $mqtt->publish('sskaje/test', $msg, 0, 1, 0, 1);

// sleep(10);

// $msg = str_repeat('1234567890', 1640);

// # mosquitto_sub -t 'sskaje/#'  -q 1 -h test.mosquitto.org
// $mqtt->publish('sskaje/test', $msg, 0, 1, 0, 1);

// sleep(10);

// $msg = str_repeat('1234567890', 209716);

// # mosquitto_sub -t 'sskaje/#'  -q 1 -h test.mosquitto.org
// $mqtt->publish('sskaje/test', $msg, 0, 1, 0, 1);
//访问DB使用https://github.com/joshcam/PHP-MySQLi-Database-Class

