<?php

Route::group(['namespace' => 'Admin'], function () {
	
	Route::group(['prefix' => 'admin'], function() {


		/*
		 * 管理后台登录页面
		 * 2015.10.27 create
		 */
		route::get('/pjax', 'AdminController@pjaxViewShow');

		/*
		 * 测试上传图片
		 * 2015.10.27 create
		 */
		route::get('/upload/show', function() {
			return view('admin.uploadImg');
		});

		/*
		 * 测试上传图片
		 * 2015.10.27 create
		 */
		route::post('/upload/img', 'AdminController@uploadImg');


		/*
		 * 管理后台一级一面设备管理
		 * 2015.10.27 create
		 */
		route::get('/1/device/show', 'AdminController@firstDeviceShow');

		/*
		 * 管理后台一级一面用户管理
		 * 2015.10.27 create
		 */
		route::get('/1/user/show', 'AdminController@firstUserShow');

		/*
		 * 管理后台一级一面推送管理
		 * 2015.10.27 create
		 */
		route::get('/1/push/show', 'AdminController@firstPushShow');
		
		/*
		 * 2015.10.27 create
		 */
		route::get('/index/show', 'AdminController@indexShow');
		/*
		 * 管理后台登录页面
		 * 2015.10.27 create
		 */
		route::get('/login', 'AdminController@adminLoginViewShow');

		/*
		 * 用户登陆
		 * 2015.10.27 create
		 */
		route::post('/login', 'AdminController@adminLogin');


		Route::group(['middleware' => 'AdminStatusMiddleware'], function () {

			/*
            * 处理控制遮阳机请求
            * 2016/02/25 create
            */
            route::post('/check/shading', 'AdminController@checkShadingPost');
            /*
			 * 展示遮阳机控制界面
			 * 2016/02/25 create
			 */
            route::get('/check/show', 'AdminController@checkShadingShow');
            /*
			 * 删除版本信息
			 * 2016/02/25 create
			 */
            route::post('/edit/status', 'AdminController@editVersionStatus');

            /*
			 * 修改版本启用状态
			 * 2016/02/25 create
			 */
            route::post('/edit/enable', 'AdminController@editVersionEnable');

            /*
			 * 发布新版本请求
			 * 2016/02/25 create
			 */
            route::post('/new/version', 'AdminController@issueNewestVersion');

            /*
			 * 发布新版本页面
			 * 2016/02/25 create
			 */
            route::get('/new/version', 'AdminController@newVersion');

            /*
			 * 版本管理列表页面
			 * 2016/02/25 create
			 */
            route::get('/version/list', 'AdminController@versionListShow');

			/*
			 * 管理后台首页
			 * 2015.10.27 create
			 */
			route::get('/index', 'AdminController@userListShow');

			/*
			 * 管理员列表
			 * 2015.10.27 create
			 */
			route::get('/admin/list', 'AdminController@adminListShow');

			/*
			 * 管理后台首页
			 * 2015.10.27 create
			 */
			route::get('/admin/new', 'AdminController@adminNew');

			/*
			 * 用户列表
			 * 2015.10.27 create
			 */
			route::get('/user/list', 'AdminController@userListShow');

			/*
			 * 网关列表
			 * 2015.10.27 create
			 */
			route::get('/gateway/list', 'AdminController@gatewayListShow');

			/*
			 * 遮阳机列表
			 * 2015.10.27 create
			 */
			route::get('/shading/list', 'AdminController@shadingListShow');

			/*
			 * 故障列表
			 * 2015.10.27 create
			 */
			route::get('/fault/list', 'AdminController@faultListShow');

			/*
			 * 推送列表
			 * 2015.10.27 create
			 */
			route::get('/push/list', 'AdminController@pushListShow');

			/*
			 * 推送页面
			 * 2015.10.27 create
			 */
			route::get('/push/show', 'AdminController@pushShow');

			/*
			 * 修改密码页面
			 * 2015.10.27 create
			 */
			route::get('/editor/password', 'AdminController@updatePasswordShow');
			
			/*
			 * 新建用户
			 * 2015.10.27 create
			 */
			route::post('/create/admin', 'AdminController@cretaeAdmin');

			
			/*
			 * 删除管理员
			 * 2015.10.27 create
			 */
			route::post('/delete/admin', 'AdminController@deleteAdmin');

			/*
			 * 验证当前管理员密码
			 * 2015.10.27 create
			 */
			route::post('/verification/password', 'AdminController@verificationAdminPassword');

			/*
			 * 修改密码
			 * 2015.10.27 create
			 */
			route::post('/editor/password', 'AdminController@editorAdminPassword');

			/*
			 * 修改密码
			 * 2015.10.27 create
			 */
			route::post('/push/msg', 'AdminController@pushMessage');

			/*
			 * 设备类型管理界面
			 * 2015.11.21 create
			 */
			route::get('/device/type/list', 'AdminController@deviceTypeShow');


			/*
			 * 设备类型管理界面
			 * 2015.11.21 create
			 */
			route::get('/device/type/new', 'AdminController@newDeviceTypeShow');

			/*
			 * 新建设备类型
			 * 2015.11.21 create
			 */
			route::post('/create/device/type', 'AdminController@createDeviceType');

			/*
			 * 编辑管理员资料
			 * 2015.11.21 create
			 */
			route::get('/edit/admin', 'AdminController@editAdmin');

			/*
			 * 提交编辑管理员资料
			 * 2015.11.21 create
			 */
			route::post('/edit/admin', 'AdminController@editAdminInfo');


			/*
			 * 新增故障报备
			 * 2015.12.07 create
			 */
			route::get('/fault/new', 'AdminController@newFault');

			/*
			 * 新增故障报备
			 * 2015.12.07 create
			 */
			route::post('/create/fault', 'AdminController@createFault');


			/*
			 * 编辑故障报备
			 * 2015.12.07 create
			 */
			route::get('/edit/fault', 'AdminController@editFaultShow');

			/*
			 * 编辑故障报备
			 * 2015.12.07 create
			 */
			route::post('/edit/fault', 'AdminController@editFault');

			
			/*
			 * 录入用户资料
			 * 2015.12.07 create
			 */
			route::get('/create/user', 'AdminController@newUserInfo');

			/*
			 * 录入用户资料
			 * 2015.12.07 create
			 */
			route::post('/create/user', 'AdminController@createUserInfo');

			/*
			 * 页面：编辑用户资料
			 * 2015.12.07 create
			 */
			route::get('/edit/user', 'AdminController@editUserInfoShow');

			/*
			 * POST：编辑用户资料
			 * 2015.12.07 create
			 */
			route::post('/edit/user', 'AdminController@editUserInfo');
                        Route::get('users', function()
                        {
                            // Matches The "/admin/users" URL
                        });
                        
                        /*
                         * 输入手机号 或 邮箱查询对应的网关、设备信息
                         * 2016.02.19
                         * yuruyi
                         */
                        route::get('/search/device','AdminController@searchDevice');
                        route::get('/search/device/{findvalue}','AdminController@searchDevice')->where('findvalue','(1[345789][0-9]{9})|([a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+)');
		});
	});
	
});