<?php
/**
 * @power 处理setting相关的业务逻辑的代码
 */
class Setting 
{
	//使用私有对象存放DB实例类
	private static $dbConnect;

    //使用私有对象存放MQTT实例类
    private static $mqtt;

    //使用私有对象存放REDIS实例类
    private static $redis;
	
	/**
	 * @power 使用方法访问$dbConnect 对象
	 */
	public function setDBConnect(MysqliDb $db) 
	{
		self::$dbConnect = $db;
	}

    /**
     * @power 使用方法访问$dbConnect 对象
     */
    public function setMQTTConnect(spMQTT $mqtt)
    {
        self::$mqtt = $mqtt;
    }

    /**
     * @power 使用方法访问$redis 对象
     */
    public function setRedisConnect(Predis\Client $client)
    {
        self::$redis = $client;
    }
	
	/**
	 * @power　写入DB
     * @todo 以后如果替换DB或者DB的访问类的话，这样的操作是有好处的
     * @param $table string 表名
     * @param $data array 表的数据
	 */
	public function writeDB($table, $data)
	{
		return self::$dbConnect->insert($table, $data);
	}

    /**
     * @power　读取DB
     * @todo 以后如果替换DB或者DB的访问类的话，这样的操作是有好处的
     * @param $q string select语句，形如select * from user where id = ?
     * @param $params array select语句的参数 形如 ['sas']
     */
    public function readDB($q, $params)
    {
        return self::$dbConnect->rawQuery ($q, $params);
    }

    /**
     * @power 验证用户ID和SettingIDd的绑定关系，在该类内部调用
     * @todo 验证机制
     * @param $ID userid
     * @return boolean 0：验证没有通过 1：验证已通过
     */
    private function verifyUserIDandSettingIDbyDB($userID, $SettingID)
    {
        /**
         * @todo验证机制
         */
        $info  = self::readDB('select * from user_gateway where gateway_id = ? and user_id = ?', [$userID, $SettingID]);

        return !empty($info);
    }

	/**
	 * @power 验证SettingID有没有在DB中
	 * @todo 验证机制
	 * @param $ID settingID
	 * @return boolean 0：验证没有通过 1：验证已通过
	 */
	public function verifyIDbyDB($ID)
	{
        return 1;
        //$user $token 都不能是空
        if (is_null($ID)) {
            return 0;
        }
		/**
		 * @todo验证机制
		 */
		$info = json_decode(file_get_contents(SETTING_INTERFACE . '/personalcenter/deviceinfo?code=' . $ID), 1);

		return !empty($info['deviceinfo']);
    }

    /**
     * @power 根据最后一个topic来处理具体逻辑
     */
    public function doSomethingByLastTitle($lastTitle = null, $topicNameList, $messageArray)
	{
        echo $lastTitle . PHP_EOL;
        //不使用call_user_func的话就要使用switch
        call_user_func(array('self', $lastTitle),$topicNameList, $messageArray);
	}

    /**
     * @power 接受来至SETTING的绑定user信息
     *
     */
    private function bindUserFromSetting($topicNameList, $messageArray)
    {
        //验证数据
        if (!self::bindUserFromSettingVerifyMessage($messageArray)) {
            echo 'message is error';
            return 0;
        }

        //@todo 验证
        if (self::verifyUserIDandSettingIDbyDB($messageArray['user_id'], $messageArray['user_token'])) {

        }

        //检查有没有mqtt的实例
        if (is_null(self::$mqtt)) {
            return 0;
        }
        //检查有没有DB的实例
        if (is_null(self::$dbConnect)) {
            return 0;
        }
        //检查message中的SettingID 和 topic中的Setting ID 是否相同
        if ($messageArray['gateway_id'] != $topicNameList[3]) {
            echo 'gateway_id is error';
            return 0;
        }
        //@todo 写入DB
        $result = self::writeDB('user_gateway', ['gateway_id' => $messageArray['gateway_id'],
                                                  'user_id'    => $messageArray['user_id'],
                                                  'app_name'   => 'superApp',
                                                  'app_version'=> 'v1.0',
                                                  'init_time'  => date('Y-m-d H:i:s',$messageArray['init_time'])
                                                  ]);
        //发布给Super绑定setting的结果

        self::$mqtt->publish('/v1.0/SuperApp/v1.0/' . $messageArray['user_id'] . '/' . $messageArray['gateway_id'] . '/bindSettingFromWeb', '{"id":"' . $messageArray['id'] . '", "result":"' . $result . '"}');

    }

    /**
     * @power 验证信息
     * @param $messageArray
     * @return int
     */
    private function bindUserFromSettingVerifyMessage($messageArray)
    {
        //@todo 要更加严谨
        if (!(isset($messageArray['id']) && isset($messageArray['user_id']) && isset($messageArray['user_token']) && isset($messageArray['init_time']) && isset($messageArray['gateway_id']))) {
            return false;
        }

        return true;
    }

    /**
     * @power 接受来自setting的绑定设备信息
     */
    private function isattyFromSetting($topicNameList, $messageArray)
    {
        //验证数据
        if (!self::isattyFromSettingVerifyMessage($messageArray)) {
            echo 'message is error';
            return 0;
        }

        //检查有没有mqtt的实例
        if (is_null(self::$mqtt)) {
            return 0;
        }
        //检查有没有DB的实例
        if (is_null(self::$dbConnect)) {
            return 0;
        }
        //判断传递过来的
        //@todo 根据device_type 获取数据
        //message中的device_type表示设备的类型。如果settin获取了设备的二级分类的话，那么就应该在数组中的第二个元素表示出来，例如[1,2]1表示大类，2表示在1下面的2小类。如果该设备是个多合一的设备的话，那么应该有多个数组，例如：[[2,1],[1,2]]

        //例如
//        array (size=1)
//          'device_type' =>
//            array (size=2)
//              0 =>
//                array (size=2)
//                  0 => string '1' (length=1)
//                  1 => string '2' (length=1)
//              1 =>
//                array (size=2)
//                  0 => string '2' (length=1)
//                  1 => string '1' (length=1)
/*@power 获取了  device_name中的数据
@todo 如果setting没有获取到智能设备的小类别的话，那么就应该由SuperApp指定一下，那么SuperApp指定的时候的数据项应该来源与APP本身数据还是有服务器发送过去的数据
@todo 如果需要服务器发送数据的话那么就应该从$info这个匿名函数中获取到。

      //使用了闭包来判断device_type中的元素, 来选择查询数据库的表
        $info = function () use ($messageArray) {
            $ay = [];
            //用来存放array的索引
            $i = 0;
            //array的话表示多合一设备
            if (is_array($messageArray['device_type'][0])) {
                foreach ($messageArray['device_type'] as $item) {
                    //第二个元素为空的话，表示没有获取到小类的名称
                    if (!isset($item[1])) {
                        var_dump(__LINE__);
                        //@todo 去查询device_categroy表
                        $ay[$i] = self::readDB('select * from device_name where category_id = (select id from device_category where category = ?)', [$item[0]]);
                    } else {
                        var_dump(__LINE__);
                        //@todo 略过device_categroy表，直接去查询devivce_name表
                        $ay[$i] = self::readDB('select * from device_name where id = ? and category_id = (select id from device_category where category = ?)', [$item[1], $item[0]]);

                    }
                    $i ++;
                }
            } //不是多合一设备就是单一设备
            else {
                //如果该字段的第二个元素是空的话，表示只获取到了大类，没有获取到小类
                if (!isset($messageArray['device_type'][1])) {
                    var_dump(__LINE__);
                    //@todo 去查询device_categroy表
                    $ay[$i] = self::readDB('select * from device_name where category_id = (select id from device_category where category = ?)', [$messageArray['device_type'][0]]);
                } //获取到了小类
                else {
                    var_dump(__LINE__);
                    //@todo 略过devuce_categroy表，直接去查询devivce_name表
                    $ay[$i] = self::readDB('select * from device_name where id = ? and category_id = (select id from device_category where category = ?)', [$messageArray['device_type'][1], $messageArray['device_type'][0]]);

                }
            }
            return $ay;
        };
        //最多是一个二维数组
        $info = $info();
       var_dump(json_encode($info));
*/

/*@power 这一段代码是把上面获取到的array转化成一维数组
    //@todo 大概可以用
          @power 把多维数组转化成json
        for ($i = 0, $count = count($info), $k = 0; $i < $count; $i++) {
            foreach($info[$i] as $item) {
                if (is_object($item)) {
                    $infoR[$k] = (array)$item;
                    $k++;
                } elseif (is_array($item)) {
                    $infoR[$k] = $item;
                    $k++;
                }
            }

        }
        var_dump($infoR);
*/


        //@todo 写入DB，需要使用for
        var_dump($messageArray['device_type']);
        //如果是array表示设备类型是多多合一
        if (is_array($messageArray['device_type'][0])) {
            foreach ($messageArray['device_type'] as $item) {
                self::writeDB('gateway_device_category',
                    [
                        'id' => trim(com_create_guid(), '{}'),
                        'gateway_id' => $topicNameList[3],
                        'device_id' => $messageArray['device_id'],
                        'device_category_id' => $item[0],
                        'init_time' => date('Y-m-d H:i:s', time())
                    ]);
                //如果该字段的第二个元素是空的话，表示只获取到了大类，没有获取到小类
                if (isset($item[1])) {
                    self::writeDB('gateway_device_name',
                        [
                            'id' => trim(com_create_guid(), '{}'),
                            'gateway_id' => $topicNameList[3],
                            'device_id' => $messageArray['device_id'],
                            'device_name_id' => $item[1],
                            'init_time' => date('Y-m-d H:i:s', time())
                         ]);
                }
            }
        }
        //不是array表示设备类型是单一的
        else {
            self::writeDB('gateway_device_category',
                [
                    'id' => trim(com_create_guid(), '{}'),
                    'gateway_id' => $topicNameList[3],
                    'device_id' => $messageArray['device_id'],
                    'device_category_id' => $messageArray['device_type'][0],
                    'init_time' => date('Y-m-d H:i:s', time())
                ]
            );
            //如果该字段的第二个元素是空的话，表示只获取到了大类，没有获取到小类
            if (isset($messageArray['device_type'][1])) {
                self::writeDB('gateway_device_name',
                    [
                    'id' => trim(com_create_guid(), '{}'),
                    'gateway_id' => $topicNameList[3],
                    'device_id' => $messageArray['device_id'],
                    'device_name_id' => $messageArray['device_type'][1],
                    'init_time' => date('Y-m-d H:i:s', time())
                    ]);
            }
        }
        //发布给Super Setting数据


        self::$mqtt->publish('/v1.0/SuperApp/v1.0/' . $messageArray['user_id'] . '/' . $topicNameList[3] . '/isattyFromWeb', json_encode($messageArray));

    }
    /**
     * @power 验证信息
     * @param $messageArray
     * @return int
     */
    private function isattyFromSettingVerifyMessage($messageArray)
    {
        //@todo 要更加严谨
        if (!(isset($messageArray['id']) && isset($messageArray['device_id']) && isset($messageArray['device_type']) && isset($messageArray['user_id']) && isset($messageArray['user_token']))) {
            return false;
        }

        return true;
    }

    /**
     * @power 接受来自设备的删除设备请求
     */
    private function deleteDeviceFromSetting($topicNameList, $messageArray)
    {
        if (!self::deleteDeviceFromSettingVerifyMessgae($messageArray)) {
            echo 'deleteDevivceFromSetting Message is error!' . PHP_EOL;
            return 0;
        }
        //检查有没有mqtt的实例
        if (is_null(self::$mqtt)) {
            return 0;
        }
        //检查有没有DB的实例
        if (is_null(self::$dbConnect)) {
            return 0;
        }

        self::$mqtt->publish('/v1.0/SuperApp/v1.0/' . $messageArray['user_id'] . '/' . $messageArray['device_id'] . '/deleteDeviceFromWeb', json_decode($messageArray));

    }

    /**
     * @power 验证来自设备的删除设备请求的信息
     */
    private function deleteDeviceFromSettingVerifyMessgae($messageArray)
    {
        //@todo 要更加严谨
        if (!(isset($messageArray['id']) && isset($messageArray['result']))) {
            return false;
        }

        return true;
    }

    /**
     * @power 接受设备的上报数据
     */
    private function postDataFromSetting($topicNameList, $messageArray)
    {
        //根据在topic的deviceID参数来进行DB操作
        //if ($topicNameList[3])
        if (!self::postDataFromSettingVerifyMessage($messageArray)) {
            echo 'postDataFromSetting message is error!' . PHP_EOL;
            return 0;
        }
        //@todo 需要解析messageArray
        for ($i = 1, $count = count($messageArray, 0); $i < $count; $i ++) {
            self::writeDB('device_status', ['id' => trim(com_create_guid(), '{}'),
                                            'gateway_id' => $topicNameList[3],
                                            'device_id' => $messageArray[$i]['device_id'],
                                            'init_time' => date('Y-m-d H:i:s', time()),
                                            'attribute' => $messageArray[$i]['attr'],
                                            'value' => $messageArray[$i]['value']
                                            ]);
        }
        //self::writeDB('device_status')
        return 1;
    }

    /**
     * @power验证设备上报信息是否完整
     */
    private function postDataFromSettingVerifyMessage($messageArray)
    {
        if (isset($messageArray['init_time'])) {
             //判断从第两个开始的元素的合法性
            for ($i =1, $count = count($messageArray, 0); $i < $count; $i ++) {
                if (is_array($messageArray[$i])) {
                    //如果数组里面不存在device_id attr param 元素的话就return 0
                    if (!(isset($messageArray[$i]['device_id']) && isset($messageArray[$i]['attr']) && isset($messageArray[$i]['param']))) {
                        return 0;
                    }
                } else {
                    return 0;
                }
            }
            return 1;
        }

        return 0;
    }
} 