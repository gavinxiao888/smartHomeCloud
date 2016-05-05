<?php

Route::group(['namespace' => 'Platform'], function () {


	route::get('/', 'PlatformLoginC@view_login');
	Route::get('/user/registration','RegistrationC@view_registration');//注册页面
	route::get('/weibologin','ThirdLoginController@weibologin');//微博第三方登录授权
	route::get('/weibothird','ThirdLoginController@weibothird');//微博第三方登录获取信息
	//route::get('/thirdlogin/callback','ThirdLoginController@qqlogin');//QQ第三方登录
	//Route::get('/user/thirdregistration','RegistrationC@view_thirdregistration');//第三方登录授权通过后的注册页面
    route::get('/users/login', 'PlatformLoginC@view_login');//登陆页面https://confluence.jetbrains.com/display/PhpStorm/Tutorials
	route::post('/user/loginaction', 'PlatformLoginC@loginaction');
    route::post('/user/loginaction1', array('middleware' => 'PlatformLoginAction', 'uses' => 'PlatformLoginC@login'));//提交登陆的方法
    route::post('/user/code', 'CodeC@platform_reg_code');//验证云平台的接口问题。0为session不存在;1为正确;2为不正确
    route::post("/user/registrationplatform", 'RegistrationC@platform');
	route::get('/user/forgotpassword','BindEmailC@view_forgotpassword');//显示忘记密码页面
	route::post('/user/mailexist', 'BindEmailC@mailexist');//验证邮箱是否存在
	route::post('/user/checkquestion', 'BindEmailC@checkquestion');//验证密保问题
	route::get('/user/loginout','PlatformLoginC@logout');//退出
	route::get('/code/platformcode','CodeC@platformcode');//获取随机数
	//route::post('/personalcenter/uploadheadimg',array('middleware' => 'cat'));//uploadimg
		


    Route::group(array('middleware' => 'PlatformCompetence'), function ()//云平台的权限验证过滤器
    {
        /**
         * @power 搜索模块
         * @param GET方式传值
         */
        //搜索第一页
        route::get('/user/search', 'PlatformSearchC@show');
        //搜索分页
        route::get('/user/search/p{id}', 'PlatformSearchC@show')->where('id', '[0-9]+');
		
		route::get('/personalcenter/personalcenter','PeopleCenterC@view_personal_center'); //显示我的个人中心页面
		route::get('/personalcenter/device','PeopleCenterC@view_device'); //显示我的设备页面
		route::get('/personalcenter/viewdevice','PeopleCenterC@view_add_device');//显示我的设备添加设备页面
		route::post('/personalcenter/adddevice','PeopleCenterC@add_device');//添加到我的设备
		
		route::get('/customservice/center','ServiceRepairController@view_custom_repair');
		route::get('/customservice/repair','ServiceRepairController@view_custom_repair');//显示我的维修页面
		route::get('/customservice/repairstatus','ServiceRepairController@view_repair_status');//显示维修状态页面
		route::post('/customservice/addrepair','ServiceRepairController@addrepair');//添加维修
		route::get('/customservice/return','ServiceRepairController@view_custom_return');//显示退换货页面
		route::get('/customservice/returnstatus','ServiceRepairController@view_return_status');//显示退换货状态页面
		route::post('/customservice/addreturn','ServiceRepairController@addreturn');//添加退换货
		route::get('/customservice/appointment','ServiceRepairController@view_appointment');//显示我的预约服务页面
		
		route::get('/accountmanagement/returnaddressmanagement','AccountManagementController@view_return_address_management');//显示收货地址管理页面 
		route::post('/accountmanagement/addreturnaddress','AccountManagementController@add_return_address');//添加收货地址
		route::get('/accountmanagement/delreturnaddress','AccountManagementController@del_return_address');//删除收货地址
		route::get('/accountmanagement/defaultreturnaddress','AccountManagementController@default_return_address');//设置默认收货地址
		route::get('/accountmanagement/getcity','AccountManagementController@getsubject');//获取市
		route::get('/accountmanagement/gettown','AccountManagementController@getunit');//获取区
		
		
        route::get('/user/center', 'PeopleCenterC@view_personal_center');//显示个人中心
		route::get('/user/safelevel', 'PeopleCenterC@view_safelevel');//显示如何提高安全等级页面
        route::get('/user/headimg', 'HeadimgC@showHeadimg');//显示头像页面
		route::get('/user/getheadimg.php', function(){
			//header("Content-Type:image/png");
			//echo '<img id="generated" src="/upfile/avatar/b_'. $_SESSION['user']['user']['id'] .'.jpg" style="height:120px;width:120px;margin-left:25px;margin-top:10px;" />';
			echo '/upfile/avatar/b_'.$_SESSION['user']['user']['id'].'.jpg';
		});//重新获取头像
		route::get('/user/account', 'AccountManagementController@view_account');//显示我的爱悠账号
		route::get('/user/nickname','PeopleCenterC@view_nickname'); //显示昵称设置
		route::post('/user/changenickname','PeopleCenterC@change_nickname'); //修改昵称
        route::get('/ajax/headimg/exist', 'HeadimgC@exist');//查看头像文件在不在
		route::get('/ajax/headimg/imgexist', 'HeadimgC@img_exists');
		//上传头像的接受代码
		route::post('/plug-in/FileDrop/demo/upload.php',  function(){
			ob_start();
			// error_reporting(0);//这个地方要反复审查
			@session_start();//开启session	
			 
			// Callback name is passed if upload happens via iframe, not AJAX (FileAPI).
			//$callback = &$_REQUEST['fd-callback'];

			// Upload data can be POST'ed as raw form data or uploaded via <iframe> and <form>
			// using regular multipart/form-data enctype (which is handled by PHP $_FILES).
			if (!empty($_FILES['fd-file']) and is_uploaded_file($_FILES['fd-file']['tmp_name'])) //不知道怎么的，IE7就进入true的分支了
			{
			  // Regular multipart/form-data upload.
			  // $name = $_FILES['fd-file']['name'];
			  // $data = file_get_contents($_FILES['fd-file']['tmp_name']);
			  
				if(!in_array($_FILES['fd-file']['type'], array('image/jpg','image/jpeg','image/png','image/pjpeg','image/gif','image/bmp','image/x-png')))
				//检查文件类型
				{
					echo -2;
					exit;
				}
				if(3145728 <$_FILES['fd-file']['size'])
				//检查文件大小
				{
					echo -1;
					exit;
				}

				if(!move_uploaded_file($_FILES['fd-file']['tmp_name'], public_path() . '/upfile/avatar/'.$_SESSION['user']['user']['id'].'.jpg'))			
				{
					echo 0;
					exit;
				}	
				else
				{
					echo 1;
					die();
				}

			} else {
				
				if(!in_array($_SERVER['HTTP_X_FILE_TYPE'],array('image/jpg','image/jpeg','image/png','image/pjpeg','image/gif','image/bmp','image/x-png')))
				{
					echo -2;
					die();
				}
			  // Raw POST data.
			  // $name = urldecode(@$_SERVER['HTTP_X_FILE_NAME']);
			  $data = file_get_contents("php://input");//得到二进制参数
			  // $size = getimagesize($name);
			  
			  //以下被注销的4行是develop时的测试代码
			  // session_start();
			  // $_SESSION["FILES"]=$_FILES;
			  // $_SESSION["file"] = $data;
			  // $_SESSION['server']=$_SERVER;
			  

				if(strlen($data)>3145728)//判断文件长度
				{
					echo -1;
					die();
				}	
				else{//IO操作
					try
					{			
						
						// require_once '../../vendor/laravel/framework/src/Illuminate/mycustom/GetInfo.php';
						// $time = new GetInfo();
						
						// ini_set('session.gc_maxlifetime',$time::SESSION_TIME());
					
						
						// $_SESSION["globals"] =$GLOBALS[HTTP_RAW_POST_DATA];
						
						$filename=$_SESSION['user']['user']['id'].".jpg";//要生成的图片名字

						$xmlstr =  $GLOBALS['HTTP_RAW_POST_DATA'];
						// if(empty($xmlstr)) $xmlstr =   $data;

						$jpg = $xmlstr;//得到post过来的二进制原始数据
						// $file = fopen("../../avatar".$filename,"w");//打开文件准备写入。这里的路由是相对与该php文件的路径
						$file = fopen(public_path() . '/upfile/avatar/'.$filename,"w");//打开文件准备写入。这里的路由是相对与该php文件的路径
				
						fwrite($file,$jpg);//写入
						fclose($file);//关闭
						
						// $i = include_once '../../../plug-in/FileDrop/demo/handleimg.php';
						
						// $handleimg = new handler();
						
						// $handleimg -> imagecropper('../../../upfile/avatar/'.$filename,50,50,'../../../upfile/avatar/m_'.$filename);
						
						// $handleimg -> imagecropper('../../../upfile/avatar/'.$filename,30,30,'../../../upfile/avatar/l_'.$filename);
						
						// $handleimg -> imagecropper('../../../upfile/avatar/'.$filename,180,180,'../../../upfile/avatar/b_'.$filename);
						
						echo 1;
						die();
					}
					catch (Exception $e)
					{
						echo 0;
						Log::wraning('{人为错误：用户ID}'.$_SESSION['user']['user']['id'].'错误事件:上传头像出错 错误代码:'.__FILE__.__LINE__.'}');
						die();
					}
				}
			}
		});
		
		route::post('/static/platform/upload/resize_and_crop.php', function(){
			$filename=$_SESSION['user']['user']['id'].".jpg";
			$img = Image::make(public_path() . $_POST['imageSource']);
			$img->resize($_POST['viewPortW'], $_POST['viewPortH']);
			$img->save(public_path() . '/1.jpg');
			$img->crop( $_POST['selectorW'], $_POST['selectorH'], intval($_POST['selectorX']), intval($_POST['selectorY']));
			$img->save(public_path() . '/upfile/avatar/b_' . $filename);
			$img->resize(50,50);
			$img->save(public_path() . '/upfile/avatar/m_' . $filename);
			$img->resize(30,30);
			$img->save(public_path() . '/upfile/avatar/l_' . $filename);
			echo '1';
		});

        route::get('/user/password/edit', 'AccountManagementController@view_change_password');//显示修改密码页面
        route::post('/user/reg_passwd', 'PasswordC@platfrom_passwd_reg');//ajax验证输入的密码对不对
        route::post('/user/password/editaction', 'PasswordC@platfrom_editaction');//修改密码的动作
        route::get('/user/securityquestion', 'SecurityQuestionC@platform_securityquestion_show');//显示密保问题的页面
        route::post('/user/securityquestion/addaction', 'SecurityQuestionC@platform_securityquestion_addaction');//添加密保问题
        route::get('/user/bindemail', 'BindEmailC@platform_bindemail_show');//显示绑定邮箱
        route::post('/user/bindemailaction', 'BindEmailC@platformC_bindemailaction');//绑定邮箱的动作:提交邮箱
        route::post('/user/platform_bindeamail_code', 'BindEmailC@platform_code');//验证code
        route::get('/user/bindphone', 'BindEmailC@view_bindphone'); //显示绑定手机页面
		route::post('/user/bindphoneaction', 'BindEmailC@platformC_bindphoneaction');//绑定手机的动作:提交手机
		route::post('/user/platform_bindphone_code', 'BindEmailC@platform_phone_code');//验证手机code
		route::get('/user/changepassword', 'PasswordC@view_changepassword'); //显示修改密码页面
    });
});