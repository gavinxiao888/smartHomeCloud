<?php
/**
 * @power 处理setting相关的业务逻辑的代码
 */
class SuperApp
{

    //使用私有对象存放DB实例类
    private static $dbConnect;

    //使用私有对象存放MQTT实例类
    private static $mqtt;
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
        return self::$dbConnect->rawQuery($q, $params);
    }

    /**
     * @param $table string 表名
     * @param $where string 条件字符串 例如 1="1"
     * @param $params array 修改的数据
     * @return int
     */
    private function updateDB($table, $where, $params)
    {
        //设置where
        self::$dbConnect->where($where);

        return self::$dbConnect->update($table, $params);
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
         * @todo验证机制， 查库
         */
        $info  = self::readDB('select * from user_gateway where gateway_id = ? and user_id = ?', [$userID, $SettingID]);

        return !empty($info);
    }

    /**
     * @power 限制userID信息是否在数据库中
     * @param $info string
     * @return boolean 0：不完整 1：完整
     */
    public function	verifyIDbyDB($userID)
    {
        return true;
    }

    /**
     * @power 验证用户token
     *
     */
    public function verifyUserInfobyDB($user = null, $token = null)
    {
        //$user $token 都不能是空
        if (is_null($user) || is_null($token)) {
            return 0;
        }

        //@todo 此处应该调用一个接口
        //***
        return 1;
    }
    /**
     * @power 根据最后一个topic来处理具体逻辑
     */

    public function doSomethingByLastTitle($lastTitle = null, $topicNameList, $messageArray)
    {
        echo $lastTitle . PHP_EOL;
        //不使用call_user_func的话就要使用switch
        call_user_func(array('self', $lastTitle), $topicNameList, $messageArray);
        // switch ($lastTitle) {
        // case 'bindSetting':
        // echo 'bindSetting';
        // }
    }

    /**
     * @power 接受来至SurperApp的绑定setting信息
     */
    private function bindSettingFromApp($topicNameList, $messageArray)
    {
        //验证数据
        if (!self::bindSettingFromAppVerifyMessage($messageArray)) {
            echo 'message is error';
            return 0;
        }

        //@todo 现在的验证放在了一个function中
        if (!self::verifyUserIDandSettingIDbyDB($messageArray['user_id'], $messageArray['user_token'])) {

        }

        //检查有没有mqtt的实例
        if (is_null(self::$mqtt)) {
            return 0;
        }
        //检查有没有DB的实例
        if (is_null(self::$dbConnect)) {
            return 0;
        }
        //@todo 这里要不要写入DB
        //发布给setting绑定user信息
        self::$mqtt->publish('/v1.0/Setting/v1.0/' . $messageArray['device_id'] . '/bindUserFromWeb', json_encode($messageArray));

    }

    /**
     * @power 验证messgae
     * @param array
     */
    private function bindSettingFromAppVerifyMessage($messageArray)
    {

        //@todo 要更加严谨
        if (!(isset($messageArray['id']) && isset($messageArray['user_id']) && isset($messageArray['user_token']) && isset($messageArray['init_time']) && isset($messageArray['gateway_id']))) {
            return false;
        }

        return true;
    }

    /**
     * @power 接受来自SuperApp的获取设备的请求
     *
     */
    private function isattyFromApp($topicNameList, $messageArray)
    {
        //验证数据完整性
        if (!self::isattyFromAppVerifyMessage($messageArray)) {
            echo 'message is error';
            return 0;
        }
        //验证数据有效性
        if (!self::verifyUserIDandSettingIDbyDB($topicNameList[3], $topicNameList[4])) {
            //@todo
        }
        //检查有没有mqtt的实例
        if (is_null(self::$mqtt)) {
            return 0;
        }
        //检查有没有DB的实例
        if (is_null(self::$dbConnect)) {
            return 0;
        }

        //@todo 检查DB
/*        $info = self::readDB('select * from user_gateway where user_id = ?', [$topicNameList[3]]);
        //使用匿名函数来判断有没有结果
        $result = function () use ($info, $topicNameList) {
                    if (count($info, 0) == 0) {
                        return 0;
                    }
                    foreach ($info as $item) {
                        if ($item->gateway_id == $topicNameList[4]) {
                            return 1;
                        }
                    }
        };

        if (!$result()) {
            echo 'user_id and gateway_id not match';
            return 0;
        }*/
        //发布给setting获取设备的主题信息
        self::$mqtt->publish('/v1.0/Setting/v1.0/' . $topicNameList[4] . '/isattyFromWeb', json_encode($messageArray));
    }
    /**
     * @power 验证messgae
     * @param array
     */
    private function isattyFromAppVerifyMessage($messageArray)
    {
        //@todo 要更加严谨
        if (!(isset($messageArray['id']) && isset($messageArray['init_time']) && isset($messageArray['user_id']) && isset($messageArray['user_token']))) {
            return false;
        }


        return true;
    }

    /**
     * @power 接受App的命名信息
     */
    private function nameFromApp($topicNameList, $messageArray)
    {
        if (!self::nameFromAppVerifyMessage($messageArray)) {
            echo 'nameFromApp message is error' .PHP_EOL;
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
        /**
         * @todo获取DB信息
         * self::readDB
         */

        /**
         * @todo写数据库
         *self::updateDB
         */



        self::$mqtt->publish('/v1.0/SuperApp/v1.0/' . $topicNameList[3] . '/' . $topicNameList[4] . '/nameFromWeb', '{"id":"' . $messageArray['id'] . '" , "result" : "1"}');

    }

    /**
     * @power 验证App命名信息
     */
    private function nameFromAppVerifyMessage($messageArray)
    {
        if (!(isset($messageArray['id']) && isset($messageArray['name']) && isset($messageArray['device_id']))) {
            return 0;
        }

        return 1;
    }

    /**
     * @power 接受app删除设备的请求
     */
    private function deleteDeviceFromApp($topicNameList, $messageArray)
    {
        if (!self::deleteDeviceFromAppVerifyMessage($messageArray)) {
            echo 'deleteDeviceFormApp message is error';
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

        /**
         * @todo 删除设备的DB操作
         * self::update()
         */
        self::$mqtt->publish('/v1.0/Setting/v1.0/' . $topicNameList[4] . '/deleteDeviceFromWeb', json_decode($messageArray));
        return 1;
    }

    /**
     * @power 验证app删除设备请求的数据是否合法
     */
    private function deleteDeviceFromAppVerifyMessage($messageArray)
    {
        if (!(isset($messageArray['id']) && isset($messageArray['device_id']) && isset($messageArray['user_id']) && isset($messageArray['user_token']))) {
            return 0;
        }

        return 1;
    }

    /**
     * @power 接受来自App的获取设备的数据
     * @param $topicNameList
     * @param $messageArray
     * @return int
     */
    private function RTQFromApp($topicNameList, $messageArray)
    {
        if (!self::RTQFromAppVerifyMessage($messageArray)) {
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

        //@todo 目前从MYSQL中获取。 以后可能要发送给SETTING。SETTING上报
        $info = self::readDB('select device_id, init_time, attribute as name, value from device_status where gateway_id = ?', [$topicNameList[4]]);

        self::$mqtt->publish('/v1.0/SuperApp/v1.0/' . $topicNameList[3] . '/' . $topicNameList[4] . '/RTQFromWeb', json_decode($info));
        return 1;
    }

    /**
     * @power 验证来自App的获取设备请求的数据
     * @param $messageArray
     * @return int
     */
    private function RTQFromAppVerifyMessage($messageArray)
    {
        if (!(isset($messageArray['id']) && isset($messageArray['device_id']) && isset($messageArray['user_id']) && isset($messageArray['user_token']))) {
            return 0;
        }

        return 1;
    }

    /**
     * @power 接受来自App的控制指令
     * @param $topicNameList
     * @param $messageArray
     * @return int
     */
    private function commandFromApp($topicNameList, $messageArray)
    {
        if (!self::commandFromAppVerifyMessage($messageArray)) {
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

        self::$mqtt->publish('/v1.0/Setting/v1.0/' . $topicNameList[4] . '/commandFromWeb', json_encode($$messageArray));

        return 1;
    }

    /**
     * @power 验证接收到的APP的控制指令
     * @param $messageArray
     * @return int
     */
    private function commandFromAppVerifyMessage($messageArray)
    {
       if (!(isset($messageArray['id']) && isset($messageArray['device_id']) && isset($messageArray['user_id']) && isset($messageArray['user_token']) && isset($messageArray['name']) && isset($messageArray['param']))) {
            return 0;
       }
        return 1;
    }

}