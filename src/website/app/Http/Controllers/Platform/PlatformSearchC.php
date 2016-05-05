<?php
/************************************************************
 * Copyright (C), 2014-2015, Everyoo Tech. Co., Ltd.
 * @FileName: SearchC.php
 * @Author: zzx       Version :   V.1.0.0       Date: 2015/5/15
 * @Description:     // 模块描述
 ***********************************************************/

namespace App\Http\Controllers\Platform;

use Illuminate\Support\Facades\Redis as Redis;

class PlatformSearchC extends \App\Http\Controllers\Controller
{
    /**
     * @power 代替了Middleware和Request的作用，不符合LARAVEL的思想
     */
    function __construct()
    {
        if (!isset($_GET['query'])) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                header('Content-Type: text/html; charset=UTF-8');
                echo '<script>alert("没有必要参数")</script>';
                echo '<script>window.location.href="', $_SERVER['HTTP_REFERER'], '"</script>';
                die();
            } else {
                header('Content-Type: text/html; charset=UTF-8');
                echo '<script>alert("没有必要参数")</script>';
                echo '<script>window.location.href="/user/center"</script>';
                die();
            }
        }
    }

    /**
     * @power 搜索关键词
     */
    public function show($page = 1)
    {
        header('Content-Type: text/html; charset=UTF-8');
        $cs = new \App\Custom\Coreseek\Coreseek();
        //得到分词结果
        $words = array_keys($cs->getWordByWords($_GET['query']));
//        var_dump($words);

        //实例化Redis连接
        $redis = new Redis();
        //得到全部SET中的value
        $setInfo = self::getSetByRedis($redis, $words);
        //对上一步的value计数
        $count = count($setInfo, 0);
        $pageMax = $count % 8 == 0 ? $count/8 : intval($count/8) +1;

        //请求的页数非法
        if ($page <= 0 || $page > $pageMax)
        {
           abort('404');
        }
        //如果我传入HASH的key得不到值， 怎么办？
        $hashInfo = self::getHashByRedis($redis, array_slice($setInfo, ($page-1)*8, 8));
//        var_dump($hashInfo);

        /**
         * @todo 这里应该发送一个HTTP请求到数据容器那边
         */

        /**
         * info 是数据信息
         * pageMax 是最大页面
         * pageIndex 是当前页面索引
         * word 是搜索关键词
         * url 是页面的基本URL。 在VIEW页面需要组合
         */
        return view('platform.search')->with('info', $hashInfo)->with('pageMax', 10)->with('pageIndex', $page)->with('word', $_GET['query'])->with('url', '/user/search');
    }

    /**
     * @power 从redis中取集合中的数据
     * @param array $setKeys 集合的主键
     * @return info
     */
    private function getSetByRedis($redis, array $setKeys = [])
    {

        /**
         * @todo 这里应该先过虑array中的非法数组
         * 应该单独放置到一个方法中
         */
//        var_dump($setKeys);
        $illegal = $redis::command('SMEMBERS', ['illegal']);
//        var_dump( $illegal);
        $del = function ($var) use(&$illegal) {

            return !in_array($var, $illegal);
        };
        $setKeys = array_filter($setKeys, $del);
//        var_dump($setKeys);
        //对集合执行并集操作。获取$setKeys 每一个元素的set
        $setValues = $redis::command('SUNION', $setKeys);

//        var_dump($setValues);
        return $setValues;
    }

    /**
     * @power 从HASH类型中取得数据
     * @param $setValues array SET类型中的值（部分）
     * @return array 信息
     */
    private function getHashByRedis($redis, $setValues)
    {
        //array 容器
        $hash = [];
        $i = 0;
        //FOREACH 取得每一个key的散列类型
        foreach ($setValues as $value) {
            //从redis中取得HASH。
            $result = $redis::command('HGETALL', [$value]);
            if (!empty($result)) {
                $hash[$i] = $result;
                $i ++;
            }
        }

        return $hash;
    }
}