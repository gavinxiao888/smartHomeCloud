<?php
/************************************************************
Copyright (C), 2015-2016, Everyoo Tech. Co., Ltd.
@FileName: zhusi.php
@Author: zzx       Version :   V.1.0.0       Date: 2015/8/31
@Description:     该文件用来处理web端的数据初步的计算
@todo: 该文件以后会被取代

 ***********************************************************/

namespace App\Custom\Statistics;
use Carbon;

class WebInfo
{

	private $key = '';

	function __construct()
	{

	}
	//显示WEB数据
	public function webData()
	{

	}

/*	//显示设备数据
	public function deviceData()
	{

	}
	//
	public function otherData()
	{

	}*/

	//获取IP数
	// @param $data array
    //@return array
    //@todo没有去掉为空的数据
	public function getIPcount($data) 
	{
        if (empty($data)) {
            return '0';
        }
		//得到全部IP一维数组
		$ipSet = array_column($data, 'ip');
        // var_dump($ipSet);
		return count(array_unique($ipSet));
	}

	//获取UV数
	//@param $data array‘
    //@return array
    //@todo没有去掉为空的数据
	public function getUVcount($data) 
	{
        if (empty($data)) {
            return '0';
        }
		$pvSet = array_column($data, 'rnd');
		return count(array_unique($pvSet));
	}

	//获取浏览量
	//@param $data array
	public function getPVcount($data)
	{
        if (empty($data)) {
            return '0';
        }
	    $arrayIsearch = function ($str, $array){
          $found = array();
          foreach ($array as $k => $v)
              if ($v == $str) $found[] = $k;
          return $found;
        };
		$uvSet = array_column($data, 'event');

		return count($arrayIsearch(1, $uvSet));
	}

	//获取访问时长
	//@param $data array 
    //@return array
    //@todo没有去掉为空的数据
	public function getDuration($data)
	{
        // var_dump($data);
        //检查输入是否为null
        if (empty($data)) {
            return '0';
        }     
  		// 全部的time数组
        $time = array_column($data, 'init_time');
        // 全部的事件数组
        $event = array_column($data, 'event');
      /*  var_dump($time);
        var_dump($event);*/

        //搜索$array 中的$str 返回key
        //第一个参数为搜索的字符串 
        //第二个参数为输入的数组 一维数组
        $arrayIsearch = function ($str, $array){
          $found = [];
          foreach ($array as $k => $v) {
              if ($v == $str) $found[] = $k;
          }
          return $found;
        };
        //使用$index 中的value 作为 $array 的key 的value 函数累加
        //$index 为索引数组 一维数组
        //$array 为输入数组 一维数组
        $arraySum = function ($index, $array) {
            $sum = 0;
            foreach ($index as $value) {
                $sum += $array[$value];
            }
            return $sum;
        };
        // 获取全部的 event = 1，整理为array
        $indexAyBy1 = $arrayIsearch('1', $event);
        //获取全部 event = 1 的时间总数
        //@todo正常情況下$indexAyBy1会比$indexAyBy10多1,但是这样并不是完整，要使用下面的才行:$sumBy1 =  floatval($sumBy1 * $indexAyBy10Count / $indexAyBy1Count);
        // array_pop($indexAyBy1);
        $sumBy1 = $arraySum($indexAyBy1, $time); 
        // 获取全部的 event = 1，整理为array
        $indexAyBy10 = $arrayIsearch('10', $event);   

        //获取全部 event = 10 的时间总数
        $sumBy10 = $arraySum($indexAyBy10, $time);
        // var_dump(__LINE__);
        // var_dump($indexAyBy1);
        // var_dump($indexAyBy10);
        // var_dump($sumBy10);
        // var_dump($sumBy1);
    
        $indexAyBy1Count = count($indexAyBy1);
        $indexAyBy10Count = count($indexAyBy10);
        //保证分母一定比分子大
        if ($indexAyBy1Count < $indexAyBy10Count) {
            $tmp = $indexAyBy1Count;
            $indexAyBy1Count = $indexAyBy10Count;
            $indexAyBy10Count = $tmp;
        }
        if (0 == $indexAyBy1Count) {
            return 0;
        }
        //平均数    
        // @todo 这里如果刷新太快会出现问题  
        // var_dump(__LINE__);
        // dd($indexAyBy10);
        // var_dump($indexAyBy10Count);
        // var_dump($indexAyBy1Count);
        // var_dump($sumBy1);
        // floatval($sumBy1 * $indexAyBy10Count / $indexAyBy1Count);

        $sumBy1 =  floatval($sumBy1 * $indexAyBy10Count / $indexAyBy1Count);


   
        // var_dump($sumBy1);
        // var_dump($sumBy10);

        // var_dump(array_sum($time));
        // var_dump(count($indexAyBy10));
        // var_dump(count($indexAyBy1));
        // var_dump($sumBy10 - $sumBy1);
        //平均访问时长
        $poor = ($sumBy10 - $sumBy1) / $indexAyBy1Count;
        // var_dump($poor);DIE;
        return intVal($poor);
	}

	// @power  获取跳出率
	// @param $data array
    //@return array
    //@todo没有去掉为空的数据
	public function getBounceRatio($data)
	{
        if (empty($data)) {
            return '0';
        }
        $pvSet = array_column($data, 'rnd');
        //PV书
        $pvCount = count(array_unique($pvSet));

        $OncePv = array_search(1, array_count_values($pvSet));

        //array_search 返回值 有 string array bool 三种.
        // @todo 一般返回bool就是没有找到
        if (is_string($OncePv)) {
            $OncePvCount = 1;
        } elseif (is_array($OncePv)) {
            $OncePvCount = count($OncePv);
        } elseif (is_bool($OncePv)) {
            $OncePvCount = $OncePv ? 0 : 1;
        }
        // var_dump(__LINE__);
        // var_dump($data);
        //跳出率 = 只浏览一次的独立PV / 总的PV
        $bounceRatio = number_format($OncePvCount / $pvCount, 4, '.', '');

        return $bounceRatio;
	}

    // @power 获取OS分布
    // @param $dara array
    // @return array 单个回合的数据
    // @todo没有去掉为空的数据
    public function getOScount($data)
    {
        //@idea因为这里的OS字段用的0 ，1 ， 2 ，3这样的数字代替的
        //@todo 需要注意下
        // var_dump($data);
        // var_dump(array_column($data, 'os'));

        //@todo 这里需要除2，因为关闭页面的时候也会发出一个请求。
        //@date 2015-08-12 
        //@author zzx
        $data = self::filterDataByField($data, 'event', '10');

        return array_count_values(array_column($data, 'os'));
    }

    //@power 获取os分布%
    //@param　　array
    //@return array
    //@todo没有去掉为空的数据
    public function getOScountRatio($data) 
    {
        // 获取分类信息
        $classifyCount = self::getOScount($data);
        // 获取全部数量
        $count = array_sum($classifyCount);
        if (0 == $count) {
            return 0;
        }
        $set = [];
        foreach ($classifyCount as $key => $value) {
            $set[$key] = number_format($value / $count, 4, '.', '');            
        }

        return $set;
    }

    //@power 获取网络提供商数量
    //@param array
    //@return array
    //@todo没有去掉为空的数据
    public function getISPcount($data)
    {
        //@todo 这里需要除2，因为关闭页面的时候也会发出一个请求。
        //@date 2015-08-12 
        //@author zzx
        $data = self::filterDataByField($data, 'event', '10');
        return array_count_values(array_column($data, 'isp')); 
    }

    //@power 获取网络提供商的%
    //@param array
    //@return array
    //@todo没有去掉为空的数据
    public function getISPcountRatio($data)
    {
        //获取全部分类信息
        $classifyCount = self::getISPcount($data);
        //获取全部数量
        $count = array_sum($classifyCount);
        if (0 == $count) {
            return 0;
        }
        $set = [];
        //遍历计算值
        foreach ($classifyCount as $key => $value) {
            $set[$key] = number_format($value / $count, 4, '.', '');
        }
        return $set;
    }

    //@power 获取cookie数量
    //@param array
    //@return array
    public function getCookiecount($data)
    {
        //@todo 这里需要除2，因为关闭页面的时候也会发出一个请求。
        //@date 2015-08-12 
        //@author zzx
        $data = self::filterDataByField($data, 'event', '10');        
        return array_count_values(array_column($data, 'cookie'));
    }
    //@power 获取cookie的%
    //@power array
    //@return array
    // 获取百分率都一样， 我为什么不合并到一个private 方法呢，这样也符合重构。因为爱情，当然不是。因为以后这块需求可能会变动。 现在接考虑这些重构什么的没有什么作用
    public function getCookiecountRatio($data)
    {
        //获取全部分类信息
        $classifyCount = self::getCookiecount($data);
        //获取全部数量
        $count = array_sum($classifyCount);
        if (0 == $count) {
            return 0;
        }
        $set = [];
        //遍历计算值
        foreach ($classifyCount as $key => $value) {
            $set[$key] = number_format($value / $count, 4, '.', '');
        }
        return $set;
    }
    
    //@power 获取屏幕分辨率
    //@param array
    //@return array
    public function getScreencount($data)
    {
        //@todo 这里需要除2，因为关闭页面的时候也会发出一个请求。
        //@date 2015-08-12 
        //@author zzx
        $data = self::filterDataByField($data, 'event', '10');

        return array_count_values(array_column($data, 'screen'));
    }

    //@power 获取分辨率%
    //@power array
    //@return array
    public function getScreencountRatio($data)
    {
               //获取全部分类信息
        $classifyCount = self::getScreencount($data);
        //获取全部数量
        $count = array_sum($classifyCount);
        if (0 == $count) {
            return 0;
        }
        $set = [];
        //遍历计算值
        foreach ($classifyCount as $key => $value) {
            $set[$key] = number_format($value / $count, 4, '.', '');
        }
        return $set; 
    }

    //@power 获取语言数量
    //@param array
    //@return array
    public function getLangcount($data) 
    {
        //@todo 这里需要除2，因为关闭页面的时候也会发出一个请求。
        //@date 2015-08-12 
        //@author zzx
        $data = self::filterDataByField($data, 'event', '10');
        
        return array_count_values(array_column($data, 'lang'));
    }

    //@power 获取语言%
    //@power array
    //@return array
    public function getLangcountRatio($data)
    {
               //获取全部分类信息
        $classifyCount = self::getLangcount($data);
        //获取全部数量
        $count = array_sum($classifyCount);
        if (0 == $count) {
            return 0;
        }
        $set = [];
        //遍历计算值
        foreach ($classifyCount as $key => $value) {
            $set[$key] = number_format($value / $count, 4, '.', '');
        }
        return $set; 
    }


    //@power 过滤掉event = $event 的数据
    //@param arary
    //@param string
    //@param string
    //@return array
    private function filterDataByField($data, $field, $value)
    {   
        foreach ($data as $key => $val) {
            if ($val[$field] == $value) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    //@power 得到浏览器数据
    //@param array 
    public function getBrowerCount($data)
    {
        //@todo 这里需要除2，因为关闭页面的时候也会发出一个请求。
        //@date 2015-08-12 
        //@author zzx
        $data = self::filterDataByField($data, 'event', '10');
        
        return array_count_values(array_column($data, 'brower'));
    }

    //@power 得到浏览器数据
    //@param array 
    public function getBrowerCountRatio($data)
    {
       //获取全部分类信息
        $classifyCount = self::getBrowerCount($data);
        //获取全部数量
        $count = array_sum($classifyCount);
        if (0 == $count) {
            return 0;
        }
        $set = [];
        //遍历计算值
        foreach ($classifyCount as $key => $value) {
            $set[$key] = number_format($value / $count, 4, '.', '');
        }
        return $set; 
    }


    
}