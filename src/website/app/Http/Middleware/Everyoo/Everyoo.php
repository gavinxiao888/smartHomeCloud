<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/2/28
 * Time: 15:23
 * @powr验证后台登陆参数
 */
namespace App\Http\Middleware\Everyoo;
use Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use GetInfo;

class Everyoo extends  \Illuminate\Http\Response {

	
	private static $ipServerHost = 'http://ip.taobao.com/service/getIpInfo2.php?ip=';
    
	private static $ipServerHost2 = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=';
	/*
     * @power 共享$cicle_navi 智慧生态圈二级导航
     */
    public function handle($request, $next)
    {
        //判断是否更新
//        if ($this->updateByTime(intval(LARAVEL_START))){
//            $info = $this->getSitemap(intval(LARAVEL_START));
//            $this->writehtml(public_path() . DIRECTORY_SEPARATOR . 'sitemap.html', $info[0]);
//            $this->writeSitemapXml(public_path() . DIRECTORY_SEPARATOR . 'sitemap.xml', $info[1]);
//        }


		//调用函数
		$this->SetCookieIpConfig();
		
        //真实的响应
        $realNext = $next($request);
		// dd($realNext);die;
		// var_dump($realNext->getContent());
        //生成静态缓存， 暂时搁置
        return $realNext;
    }
	
	/**
	 * @power 请求接口，并设置COOKIE
	 */
	private function SetCookieIpConfig() 
	{
		//进行获取IP操作
		$IP = GetInfo::IP();
		if (strstr($IP, '192.168')) {
			$IP = '122.5.51.134';
		} 
		if (isset($_COOKIE['ipConfig'])) {
            // @todo 修改日期2015/08/11 author zzx @reason达不到要求
			if (json_decode($_COOKIE['ipConfig'], 1)['ip'] != $IP) {
				// var_dump(__FILE__ . __LINE__);
				$this->sendRequestForIP($IP);
			} 
		} else {
			// var_dump(__FILE__ . __LINE__);
			$this->sendRequestForIP($IP);
		}
	}	
	
	/**
	 * @power
	 * @todo 需要进一步完善
	 */	 
	private function sendRequestForIP($IP)
	{
		$expireTime = intval(LARAVEL_START) + 2592000;
		$result = file_get_contents(self::$ipServerHost . $IP);
		$info = json_decode($result, 1);
		// var_dump(__FILE__ . __LINE__);
		//ip.taobao.com接口返回值中code为1 的话表示没有
        /*
		if ($info['code'] == 1) {
            var_dump(__LINE__);
			$result2 = file_get_contents(self::$ipServerHost2 . $IP);
			$info2 = json_decode($result2, 1);
			if (is_array($info2)) {
				// 这个字段为 1 的话 表示设置了正常
				if ($info1['ret'] == 1) {

					// setCookie('ipConfig[country]', $info2['country'], $expireTime, '/', null, null, 0);
					// setCookie('ipConfig[province]', $info2['province'], $expireTime, '/', null, null, 0);
					// setCookie('ipConfig[city]', $info2['city'], $expireTime, '/', null, null, 0);
					// setCookie('ipConfig[isp]', $info2['isp'], $expireTime, '/', null, null, 0);	
                    //@todo 修改日期2015/08/11 author zzx @reason达不到要求
                    // sinaIP库不如淘宝IP信息全面。主要依靠淘宝IP库
                    setCookie('ipConfig', json_encode(['country' => $info2['country'], 'province' => $info2['province'], 'city' => $info2['city'], 'isp' => $info2['isp'], 'ip' => $IP]), $expireTime, '/', null, null, 0);
			    }
            }
		} else {
			// var_dump(__FILE__ . __LINE__);
			// setCookie('ipConfig[country]', $info['data']['country'], $expireTime, '/', null, null, 0);
			// setCookie('ipConfig[province]', $info['data']['region'], $expireTime, '/', null, null, 0);
			// setCookie('ipConfig[city]', $info['data']['city'], $expireTime, '/', null, null, 0);
			// setCookie('ipConfig[isp]', $info['data']['isp'], $expireTime, '/', null, null, 0);	
            //@todo 修改日期2015/08/11 author zzx @reason达不到要求    
            // setCookie('ipConfig', json_encode(['country' => $info['data']['country'], 'province' => $info['data']['region'], 'city' => $info['data']['city'], 'isp' => $info['data']['isp'], 'ip' => $IP]), $expireTime, '/', null, null, 0);
            setCookie('ipConfig', json_encode($info['data']), $expireTime, '/', null, null, 0);
		}
        */
		// setCookie('ipConfig[ip]', $IP, $expireTime, '/', null, null, 0);

        setCookie('ipConfig', json_encode($info['data']), $expireTime, '/', null, null, 0);
    }	
    /*
     * @power 生产静态文件
     */
    private function createStaicHtml($realNext)
    {
        //过滤地址
        if (in_array(Request::path(), ['search_rst.html'])){
            return false;
        }

        //得到文件绝对路径
        $path = public_path(). DIRECTORY_SEPARATOR . Request::path();
        //得到文件夹绝对路径
        $dir = self::getDir($path);

        //生产文件夹
        if (!file_exists($dir) && $dir != ''){
             mkdir($dir, 0755, true);
        }

        $file = Request::path() == '/' ? 'index.html' : Request::path();
        //生产文件
        self::writeHtml(public_path() . DIRECTORY_SEPARATOR . $file, $realNext->content);
    }
    /*
     * @power 得到传入文件的绝对路径
     * @param 传入文件的地址
     * @retrun 返回绝对路径
     */
    private function getDir($path)
    {
        $dirParam = self::getURISplitWord($path);
        $count = count($dirParam, 0);
        //$dir 是返回路径
        $dir = '';
        for ($i = 0; $i< $count - 1; $i++){
            $dir .= $dirParam[$i] . DIRECTORY_SEPARATOR;
        }

        return $dir;
    }
    /*
     * @power 获取URL中的/后面的参数
     * @path URI路径或者PATH路径
     */
    private function getURISplitWord($path)
    {
        return explode('/', $path);
    }
    /*
     * @power 写入到HTML文件中
     * @param $path是写入地址
     * @param $html 写入内容
     * @return 返回写入结果
     */
    private function writeHtml($path, $html)
    {
      return file_put_contents($path, $html);
    }
    /*
     * @power 写入到xml文件中
     * @xml   写入内容
     * @path  写入地址
     * @return 返回写入结果
     */
    private function writeSitemapXml($path, $xml)
    {
        return file_put_contents($path, $xml);
    }
    /*
     * @power 显示静态化网站地图
     * @param 时间戳
     * @return ARRAY
     */
    private function getSitemap($time)
    {
        //获取当前时间
        $date = date('Y-m-d', $time);
        //总的字符HTML
        $html = '<meta charset="utf-8"><style>body{text-align: left}.all{width: 50%;margin:0 auto;}</style><body><div class="all"><h1>网站地图</h1><h2>爱悠咨询墙</h2>';
        //总的字符XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .'<urlset>'. PHP_EOL;
        //爱悠咨询墙下智能行业快讯
        $news = '<h3><a href="/news.html">智能行业快讯</a></h3><ul>';
        $xml .= '<url>'.PHP_EOL.'<loc>http://www.everyoo.com/news.html</loc>'.PHP_EOL.'<lastmod>' .$date.'</lastmod>'.PHP_EOL.'<changefreq>daily</changefreq>'.PHP_EOL.'<priority>1.0</priority>'.PHP_EOL.'</url>'.PHP_EOL;
          //爱悠咨询墙下爱悠企业新闻
        $info = '<h3><a href="/news/info.html">爱悠企业新闻</a></h3><ul>';
        $xml .= '<url>'.PHP_EOL.'<loc>http://www.everyoo.com/news/info.html</loc>'.PHP_EOL.'<lastmod>' .$date.'</lastmod>'.PHP_EOL.'<changefreq>daily</changefreq>'.PHP_EOL.'<priority>1.0</priority>'.PHP_EOL.'</url>'.PHP_EOL;
        //爱悠咨询墙下媒体眼中的爱悠
        $media = '<h3><a href="/news/media.html">媒体眼中的爱悠</a></h3><ul>';
        $xml .= '<url>'.PHP_EOL.'<loc>http://www.everyoo.com/news/media.html</loc>'.PHP_EOL.'<lastmod>' .$date.'</lastmod>'.PHP_EOL.'<changefreq>daily</changefreq>'.PHP_EOL.'<priority>1.0</priority>'.PHP_EOL.'</url>'.PHP_EOL;
        //荣誉资质
        $honor = '<h2><a href="/honor.html">荣誉资质</a></h2><ul>';
        $xml .= '<url>'.PHP_EOL.'<loc>http://www.everyoo.com/honor.html</loc>'.PHP_EOL.'<lastmod>' .$date.'</lastmod>'.PHP_EOL.'<changefreq>daily</changefreq>'.PHP_EOL.'<priority>1.0</priority>'.PHP_EOL.'</url>'.PHP_EOL;

        //智慧生态圈
        $circle = '<h2>智慧生态圈</h2><ul>';
        //一些静态链接
        $static_link = '<h2><a href="xiaoqu.everyoo.com">爱悠小区</a></h2><h2><a href="bbs.everyoo.com">BBS</a></h2><h2><a href="/contact.html">联系我们</a></h2><h2><a href="/recruitment.html">精英集结号</a></h2><h2><a href="/team.html">爱悠团队</a></h2><h2><a href="/salesnetwork.html">销售网络</a></h2><h2><a href="/merchantsjoin.html">招商加盟</a></h2><h2><a href="/down.html">下载中心</a></h2><h2><a href="/down/manual.html">爱悠资料下载</a></h2><h2><a href="/down/other.html">其他下载</a></h2><h2><a href="/everyoo.html">品牌简介</a></h2>';

        //智慧生态圈DB信息
        $news_info = DB::SELECT('SELECT id, title, type FROM `news` WHERE `is_delete` = 0');

        foreach ($news_info as $k => $v){
            switch (trim($v['type'])){
                case '0':
                    $news .= '<a href="/news/' . $v['id'] . '.html"><li>' . $v['title'].'</li></a>';
                    $xml .= '<url>'.PHP_EOL.'<loc>http://www.everyoo.com/news/' . $v['id'] . '.html</loc>'.PHP_EOL.'<lastmod>' . $date  .'</lastmod>'.PHP_EOL.'<changefreq>weekly</changefreq>'.PHP_EOL.'<priority>1.0</priority>'.PHP_EOL.'</url>'.PHP_EOL;
                    break;
                case '1':
                    $info .= '<a href="/news/info/' . $v['id'] . '.html"><li>' . $v['title'].'</li></a>';
                    $xml .= '<url>'.PHP_EOL.'<loc>http://www.everyoo.com/news/info/' . $v['id'] . '.html</loc>'.PHP_EOL.'<lastmod>' .  $date  .'</lastmod>'.PHP_EOL.'<changefreq>weekly</changefreq>'.PHP_EOL.'<priority>1.0</priority>'.PHP_EOL.'</url>'.PHP_EOL;
                    break;
                case '2':
                    $media .= '<a href="/news/media/' . $v['id'] . '.html"><li>' . $v['title'].'</li></a>';
                    $xml .= '<url>'.PHP_EOL.'<loc>http://www.everyoo.com/news/media/' . $v['id'] . '.html</loc>'.PHP_EOL.'<lastmod>' . $date .'</lastmod>'.PHP_EOL.'<changefreq>weekly</changefreq>'.PHP_EOL.'<priority>1.0</priority>'.PHP_EOL.'</url>'.PHP_EOL;
                    break;
            }
        }
        $html .= $news . '</ul>' . $info . '</ul>' . $media . '</ul>';
//        header("Content-Type:text/html;charset:utf-8;");

        //荣誉资质DB信息
        $honor_info = DB::SELECT('SELECT * FROM `honor` WHERE `is_delete` = 0');

        foreach ($honor_info as $k => $v){
            $honor .= '<a href="/honor/' . $v['id'] . '.html"><li>' . $v['title'] .'</li></a>';
            $xml .= '<url>'.PHP_EOL.'<loc>http://www.everyoo.com/honor/' . $v['id'] . '.html</loc>'.PHP_EOL.'<lastmod>' .$date  .'</lastmod>'.PHP_EOL.'<changefreq>weekly</changefreq>'.PHP_EOL.'<priority>1.0</priority>'.PHP_EOL.'</url>'.PHP_EOL;
        }
        $html .= $honor . '</ul>';

        //取智慧生态圈的导航DB信息
        $circle_navi_temp = $circle_navi = DB::SELECT('SELECT id, title, link FROM `circle_title` WHERE is_delete = 0');

//        foreach ($circle_navi as $k => $v){
//            $circle .= '<a href="/cirlce/'. $v['link'] .'"><li>' . $v['title'] . '</li></a>';
//        }
        //array_walk调用匿名函数
        array_walk($circle_navi, function(&$value){
            $value = array_slice($value, 0, 1);
        });
        $circle_info = $users = DB::table('circle')->whereIn('c_t_id', $circle_navi)->get();
        //嵌套循环
        //把智慧生态圈导航信息放在外循环里面
        foreach ($circle_navi_temp as $navi_v) {
            $circle .= '<a href="/circle/'. $navi_v['link'] .'.html"><li>' . $navi_v['title'] . '</li></a>';

            foreach ($circle_info as $k => $info_v){
                if ($info_v['c_t_id'] == $navi_v['id']){
                    $circle .= '<a href="/circle/' . $navi_v['link'] .'/' . $info_v['id'] .'.html"><li>' . $info_v['title'] . '</li></a>';
                    $xml .= '<url>'.PHP_EOL.'<loc>http://www.everyoo.com/circle/' . $navi_v['link'] .'/' . $info_v['id'] .'.html</loc>'.PHP_EOL.'<lastmod>'. $date  .'</lastmod>'.PHP_EOL.'<changefreq>weekly</changefreq>'.PHP_EOL.'<priority>1.0</priority>'.PHP_EOL.'</url>'.PHP_EOL;
                }
            }
        }
        $html .= $circle . '</ul></div></body></html>';
        return [$html, $xml.'</urlset>'. PHP_EOL];

    }
    /*
     *  @power 判断要不要更新
     *  @param $time是时间戳
     *  @todo 更新时间放在了cache里面
     */
    public function updateByTime($time)
    {
        if (Cache::has('update_sitemap')){
            if (Cache::get('update_sitemap') + 86400 < $time){
                return 1;
            }
        }else{
            Cache::forever('update_sitemap', $time);
        }
        return false;

    }
}