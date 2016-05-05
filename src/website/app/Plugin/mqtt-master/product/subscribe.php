<?php
// session_start();
//加载配置文件
require_once(__DIR__ . '/config.php');
//加载MQTT库文件
require(__DIR__ . '/../spMQTT.class.php');
$mqtt = new spMQTT(MQTT_SERVER);

spMQTTDebug::Disable();

$mqtt->setAuth(MQTT_USER, MQTT_PASSWD);
$mqtt->setKeepalive(3600);
$connected = $mqtt->connect();
if (!$connected) {
    die("Not connected\n");
}

/**
 * @power 得到微妙级别的时间戳。
 * @todo 目前没有用到
 */
function getMillisecond() {
list($t1, $t2) = explode(' ', microtime());
return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}

$topics['#'] = 2;
$mqtt->subscribe($topics);

#$mqtt->unsubscribe(array_keys($topics));


//@todo 应该实例化一些处理类，以便在函数在使用(global进来使用)
//实例化DB操作类
include  dirname(dirname(__DIR__)) . '/PHPMySQLiDatabaseClassMaster/MysqliDb.php';
$db = new MysqliDb(DB_IP, DB_USER, DB_PASSWD, DB_NAME);
//实例化REDIS操作类
require dirname(dirname(__DIR__)) . '/predis1.0/autoload.php';
Predis\Autoloader::register();
$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => REDIS_IP,
    'port'   => REDIS_PORT,
							], 
	[
		'prefix' => 'MQTT',
	]);
//使用1号DB
$redis->select(1);
$redis->set(':explain', '该库是用来存放MQTT相关的数据');
// $redis->hmset('predis:hash', ['name1' => "a2", 'age1' => "1"]);
// $redis->hsetnx('predis:hash', "a1", "1");

//实例化issue
include_once dirname(__DIR__) . '/lib/Issue.class.php';
$issue = new Issue();
//实例化setting类
include_once dirname(__DIR__) . '/lib/Setting.class.php';
$setting = new Setting();
$setting->setMQTTConnect($mqtt);
$setting->setDBConnect($db);
$setting->setRedisConnect($redis);
//        var_dump(self::$dbConnect->getLastError());
//        var_dump(self::$dbConnect->getLastQuery());
//实例化super类
include_once dirname(__DIR__) . '/lib/SuperApp.class.php';
$superApp = new SuperApp();
$superApp->setMQTTConnect($mqtt);
$superApp->setDBConnect($db);

// var_dump(spl_autoload_functions());
//接受到消息后，使用该回调函数处理信息。
$mqtt->loop('default_subscribe_callback');

/**
 * @power 处理接受到的MQTT包
 * @todo 第一步应该是把所有的包（全部接受的包）记录到REDIS中
 * @param spMQTT $mqtt
 * @param string $topic
 * @param string $message
 */
function default_subscribe_callback($mqtt, $topic, $message) {
	
	//引用外部实例化的DB操作类
	global $db;
	//引用外部实例化的MQTT操作类
	global $mqtt;
	//引用外部实例化的REDIS操作类
	global $redis;
    //引用外部实例化的Issue验证类
    global $issue;
	
    printf("Message received: Topic=%s, Message=%s\n", $topic, $message);
	// var_dump(iconv('utf-8','gb2312', '锘'));
	// var_dump($mqtt);
	//这一段可以用switch分隔
	//@todo 这里要验证权限

	
	//解析TOPIC
	$topicNameList = explode('/', $topic);
	// var_dump($topicNameList);
	array_shift($topicNameList);


    $issue->setTopic($topicNameList);
    //检查topic的格式
    if (!$issue->legitimacy()) {
        echo 'topic is error' . PHP_EOL;
        return 0;
    }
    $info = json_decode($message, 1);
    var_dump($info);
    //使用count key计数
    $redis->incr(':count');
    $redisCount = $redis->get(':count');
    var_dump($redisCount);

    //遍历其中的元素， 对其中的数组元素进行处理
    //使其变成一维数组
    //存放redis信息的一维数组
    $redisInfo = $info;
    foreach ($redisInfo as &$item) {
        if (is_array($item)) {
            $item = serialize($item);
        }
    }

    $redis->hmset('_log:' . $redisCount, array_merge($redisInfo, ['topic' => $topic]));
    //这个地方的category字段要分成多个的
    $redis->hmset('_category:' . $redisCount, ['MQTT_log_id' => $redisCount, 'category' => 1]);

/*

	//@todo 接下来应该分析topic的作用
	//首先根据第一个TOPIC
	switch ($topicNameList[0]) {
		case 'v1.0':
			echo 'v1.0' .PHP_EOL;
			break;
		default:
			break;
	}
*/
	//根据第二个TOPIC
	//@todo 也可以尝试在case中实例化不同的类，在switch外使用方法。这要求Setting 类 和 SurperApp 类有共同的接口父类
	switch ($topicNameList[1]) {
		case 'SuperApp':
			echo 'Form superApp' . PHP_EOL;
            superApp($topicNameList, $info);
			break;
		case 'Setting':
			echo 'Form Setting' . PHP_EOL;
            setting($topicNameList, $info);
			break;
		default:
			echo '2st is error' . PHP_EOL;
			break;
	}

}

/**
 * @power 接受来自setting的信息
 * @param $topic array
 * @param $message
 * @return int|void
 */
function setting($topicNameList, $messageArray)
{
    global $setting;

    //第四个子主题必须是settingID
    if (!$setting->verifyIDbyDB($topicNameList[3])) {
        echo 'deviceID is not right';
        return 0;
    }

    //实际行动
    $setting->doSomethingByLastTitle($topicNameList[count($topicNameList, 0) - 1], $topicNameList, $messageArray);
}
/**
 * @power 接受来自superapp的信息
 * @param $topic array
 * @param $message
 * @return int|void
 */
function superApp($topicNameList, $messageArray)
{
    global $superApp;
    //检查topic中的userID是不是有效的
    //第四个子主题必须要是userID
    if (!$superApp->verifyIDbyDB($topicNameList[3])) {
        echo 'userid is not right';
        return 0;
    }
    //实际行动
    $superApp->doSomethingByLastTitle($topicNameList[count($topicNameList, 0) - 1], $topicNameList, $messageArray);
}
//@todo这里应该定义一个异常处理的类。或许不需要
?>