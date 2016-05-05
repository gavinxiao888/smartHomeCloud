<?php

function getMillisecond() {
list($t1, $t2) = explode(' ', microtime());
return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}

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

//$msg ='{"id":"123456789", "device_id":"1", "device_type":1}';
//$msg = '{"id":"12346789","user_id":"123456789123456789123456789123456789","init_time":"123456","user_token":"123456"}';
//$msg = '{"id":"12346789","user_id":"123456789123456789123456789123456789","user_token":"123456","device_id":"111","device_type":["1", "1F98695B-D069-4C86-B1A5-4D31C1DDC33"]}';
//$msg = '{"id":"12346789","user_id":"123456789123456789123456789123456789","user_token":"123456","device_id":"111","device_type":[["2"], ["1","1F98695B-D069-4C86-B1A5-4D31C1DDC334"]]}';
$msg = '{"id":"12346789","user_id":"123456789123456789123456789123456789","user_token":"123456","device_id":"111","device_type":["channel":["2"], "channel":["1","1F98695B-D069-4C86-B1A5-4D31C1DDC334"]]}';
// $msg = '{id:12346789,user_id:123456789123456789123456789123456789,device_id:123456789123456789,init_time:123456,user_token:123456}';

//	$mqtt->publish('/v1.0/Setting/v1.0/123456789/bindSetting', $msg, 0, 1, 0, 1);
$mqtt->publish('/v1.0/Setting/v1.0/123456789123456789/isattyFromSetting', $msg);

//$mqtt->publish('/v1.0/Setting/v1.0/123456789123456789/isattyFromSetting', $msg, 0, 1, 0, 1);



//访问DB使用https://github.com/joshcam/PHP-MySQLi-Database-Class

