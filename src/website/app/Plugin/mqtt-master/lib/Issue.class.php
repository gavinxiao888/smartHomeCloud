<?php
/**
 * @power 此类用来验证topic格式的合法性，并不验证数据的合法性
 * @power 此类用来分发topic的作用
 */
class Issue
{
		//需要一个字典或者规则来解析topic
	//该数组是用来限制topic名称的
	private $topicFirstLimit = [
		'v1.0',
		'SuperApp|Setting',
		'v1.0'
	];
	//对第四个子主题限制
	private $topicFourthLimit = ['.{18}|.{36}'];
	//对第五个子主题限制
	private $topicFifthLimit = ['.{18}'];

	//最后一个子主题(一般代表业务)
	private $topicLastIndexLimit = [
		//绑定setting
		'bindSettingFromApp',
		//绑定用户
		'bindUserFromSetting',
		//获取设备
		'isattyFromApp',
        //获取设备
        'isattyFromSetting',
		//命名
		'nameFromApp',
		//删除设备
		'deleteDeviceFromApp',
        //删除设备
        'deleteDeviceFromSetting',
		//上传数据
		'postDataFromSetting',
		//获取设备状态
		'RTQFromApp',
		//发布控制指令
		'commandFromApp'
	];
	
	//存放topic的对象
	private $topic = null;
	
	//使用方法封装了对该类属性的访问
	public function setTopic($topicNameList)
	{
		//解析TOPIC，数据格式
//		$this->topic = explode('/', $topic);
//		array_shift($this->topic);
        $this->topic = $topicNameList;
	}
	
	/**
	 * @power 验证topic格式的合法性
     * @return 0 不合法 1 合法
	 */
	public function legitimacy()
	{
        //判断topic属性是不是为空
		if (is_null($this->topic)) {
            return 0;
        }

        $topicNameListCount = count($this->topic, 0);
        if ($topicNameListCount <= 4) {
            echo 'topic Count is error';
            return 0;
        }
        //topic的前三个字段应该是在$topicFirstLimit 的限制范围内
        for ($i = 0; $i < 3; $i ++ ) {
            if (!preg_match('/^' . $this->topicFirstLimit[$i]. '$/i', $this->topic[$i])) {
                echo $i + 1 . 'st topic is error' . PHP_EOL;
                return 0;
            } else {
                echo $i + 1 . 'st topic is right' . PHP_EOL;
            }
        }
        //topic的最后一个字段应该是表示的动作应该在$topicLastIndexLimit的限制之内
        if (!in_array($this->topic[$topicNameListCount - 1], $this->topicLastIndexLimit)) {
            echo 'last topic error' . PHP_EOL;
            return 0;
        } else {
            echo 'last topic is right' . PHP_EOL;
        }

        //topic 是5个 或者 6个
        $middleNameCount = $topicNameListCount - 4;
        var_dump($this->topic);
        var_dump(__LINE__);


        switch ($middleNameCount) {
            //表示只还有一个主题
            //共有五个主题
            case 1:
                if (!preg_match('/^' . $this->topicFourthLimit[0] . '$/i', $this->topic[3])) {
                    echo 'fourth topic is error' . PHP_EOL;
                    return 0;
                } else {
                    echo 'fourth topic is right' . PHP_EOL;
                    return 1;
                }
                break;
            //表示只还有两个主题
            //共有六个主题
            case 2:
                if (!preg_match('/^' . $this->topicFourthLimit[0] . '$/i', $this->topic[3])) {
                    echo 'fourth topic is error' . PHP_EOL;
                    return 0;
                } else {
                    echo 'fifth topic is right' . PHP_EOL;
                }
                if (!preg_match('/^' . $this->topicFifthLimit[0] . '$/i', $this->topic[4])) {
                    echo 'fifth topic is error' . PHP_EOL;
                    return 0;
                } else {
                    return 1;
                    echo 'fifth topic is right' . PHP_EOL;
                }
                break;
            default:
                return 0;
                break;
        }
    }

    /**
     * @power 验证标题数据合法性
     */
    public function ValidationTitle()
    {
        if (is_null($this->topic)) {
            return 0;
        }

        //第四个元素目前代表user_id 或者device_id
        switch (strlen($this->topic[3])) {
            case 18 :
                return 1;
                break;
            case 36 :
                return 1;
                break;
            default:
                break;
        }
    }
}