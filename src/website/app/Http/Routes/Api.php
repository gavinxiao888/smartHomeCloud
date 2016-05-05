<?php

Route::group(['namespace' => 'Api'], function () {
    route::get('/index', 'UserController@toTry');

route::get('/superapp/user/isRegistered', 'UserController@isRegistered');

route::post('/user/activate', 'UserController@activate');

route::post('/user/login', 'SSOController@login');


/*
 * 获取验证码
 */
route::post('/user/getAuthCode', 'UserController@getAuthCode');

/*
 * 用户注册激活
 */
route::post('/user/doRegister', 'UserController@doRegister');

/*
 * 第三方注册登录操作
 */
//route::post('/users/thirdregister', 'UserController@registerOAuth');

route::post('/users/thirdLogin', 'UserController@oauthActivate');
/*
 *测试发送邮件
 */
route::get('/pushEmail', 'UserController@PushEmail');


/**
 * 5.1 修改用户信息
 */
route::post('/users/editProfile', 'UserController@editProfile');

/**
 * 5.2 获取用户信息
 */
route::post('/users/profile', 'UserController@userProfile');


/**
 * 5.4 根据用户信息获取token
 */
route::post('/superapp/getToken', 'UserController@selectTokenForDevice');

/**
 * 5.2 获取用户信息
 */
route::post('/users/thirdBind', 'UserController@thirdBind');

/**
 * 1.8 绑定手机号/邮箱获取验证码
 */
route::post('/user/getBindAuthCode', 'UserController@getBindAuthCode');

/**
 * 1.9 验证手机号/邮箱验证码
 */
route::post('/users/doBind', 'UserController@doBind');

/**
 *  4.2 获取当前登陆账户的账号绑定信息
 */
route::post('/users/infoBind', 'UserController@infoBind');


/**
 *  4.3 获取网关绑定的sip账户信息
 *  默认创建5个场景
 */
route::post('/gateway/register', 'SipController@gatewayForSip');


/**
 *  4.4 用户与网管的初次绑定
 *  create_function() 2015.10.13
 */
route::post('/gateway/bind', 'GateWayController@gatewayBind');


///**
// *  4.5 测试推送
// *  create_function() 2015.10.13
// */
//route::get('/push/index', 'PushController@index');
//
///**
// *  4.6 测试单播android
// *  create_function() 2015.10.13
// */
//route::get('/android/push', 'PushController@create');


///**
// *  4.7 测试单播iOS
// *  create_function() 2015.10.13
// */
//route::get('/ios/push', 'PushController@store');
//

/**
 *  4.3 sip测试
 */
route::post('/sip/return', 'SipController@sipReturn');

/**
 *  4.9 上传网关终端设备日志
 */
route::post('/gateway/device/log', 'LogController@gatewayLog');


/**
 *  5.0 获取设备信息
 */
route::post('/device/detail', 'AppController@deviceDetail');


/**
 *  5.1 上报设备信息
 */
route::post('/gateway/upload', 'PushController@uploadMsgFromGateway');

/**
 *  5.2 获取网关列表
 */
route::post('/gateway/list', 'AppController@userGatewayList');


/**
 *  5.3 获取设备列表
 */
route::post('/device/list', 'AppController@deviceListArray');

/**
 *  5.5 修改设备名称
 */
route::post('/editor/device/name', 'AppController@deviceName');

/**
 *  5.6 获取设备日志
 */
route::post('/device/log', 'AppController@getAppLog');

/**
 *  5.7 上拉加载设备日至
 */
route::post('/device/log/reload', 'AppController@getAppLogBeTime');


/**
 *  5.9 删除设备信息
 */
route::post('/delete/device', 'GateWayController@deleteDevice');


/**
 *  6.0 测试sip服务器通讯
 */
route::get('/test/sip', 'SipServerController@testMsgToSipServer');

/**
 *  6.0 测试sip服务器通讯
 */
route::post('/test/back', 'SipServerController@backCurlPost');

/**
 *  6.1 查看是否绑定该网关
 */
route::post('/gateway/bind/status', 'AppController@appGatewayStatus');


/**
 *  6.2 查看是否绑定该网关
 */
route::post('/gateway/search/info', 'AppController@searchGatewayInfo');

/**
 *  6.3 修改网关名称
 */
route::post('/edit/gateway/name', 'AppController@editGatewayNickname');


///**
// *  6.4 解除网关绑定
// */
//route::post('/remove/gateway/bind', 'AppController@removeUserAndGatewayStatus');


/**
 *  6.5 获取网关日志
 */
route::post('/search/gateway/log', 'LogController@serachGatewayLog');


/**
 *  6.6 获取网关日志
 */
route::post('/reload/gateway/log', 'LogController@reloadGatewayLog');


/**
 *  6.7 解除设备绑定
 */
route::post('/remove/device/bind', 'AppController@removeGatewayDeviceBind');

/**
 *  6.8 获取网关ip
 */
route::post('/search/gateway/ip', 'AppController@searchgatewayIP');


/**
 *  6.9 获取用户条款
 */
route::post('/search/terms/service', 'AppController@getTermsOfService');


/**
 *  7.0 获取action信息
 */
route::post('/search/action', 'GateWayController@syncAction');

/**
 *  7.1 获取device_action信息
 */
route::post('/search/device/action', 'GateWayController@syncDeviceAction');


/**
 *  7.2 获取device_action信息
 */
route::post('/push/array', 'GateWayController@getArrayForApp');


/**
 *  7.3 同步ctrl信息
 */
route::post('/sync/ctrl', 'SyncController@syncCtrlForGateway');

/**
 *  7.4 获取device_action信息
 */
route::post('/gateway/search/device/type', 'GateWayController@gatewaySearchDeviceType');



/**
 *  7.5 获取device_action信息
 */
route::post('/app/get/ctrl', 'AppController@getGatewayCtrl');



/**
 *  7.6 APP获取定时信息
 */
route::post('/app/search/timer', 'AppController@serachDefineTimer');

/**
 *  7.7 网关新增定时信息
 */
route::post('/define/timer', 'GateWayController@appDefineTimer');

/**
 *  7.8 网关获取定时信息
 */
route::post('/gateway/search/timer', 'GateWayController@serachDefineTimer');


/**
 *  7.9 网关编辑定时信息
 */
route::post('/edit/timer', 'GateWayController@editDefineTimer');


/**
 *  8.0 网关删除定时信息
 */
route::post('/delete/timer', 'GateWayController@deleteDefineTimer');

/**
 *  8.1 网关新建场景信息
 */
route::post('/define/robot', 'GateWayController@defineRobot');

/**
 *  8.2 网关编辑场景信息
 */
route::post('/edit/robot', 'GateWayController@editRobot');

/**
 *  8.3 网关删除场景信息
 */
route::post('/delete/robot', 'GateWayController@deleteDefineRobot');

/**
 *  8.4 网关获取场景信息
 */
route::post('/gateway/search/robot', 'GateWayController@searchRobot');

/**
 *  8.5 网关获取场景详细信息
 */
route::post('/gateway/info/robot', 'GateWayController@searchRobotInfo');

/**
 *  8.6 APP获取场景信息
 */
route::post('/app/search/robot', 'AppController@searchRobot');

/**
 *  8.7 APP获取场景详细信息
 */
route::post('/app/info/robot', 'AppController@searchRobotInfo');


/**
 *  8.8 网关获取有效的ctrl信息
 */
route::post('/gateway/search/ctrl', 'SyncController@searchCtrl');


/**
 *  8.9 网关定义联动信息
 */
route::post('/gateway/sync/linkAge', 'SyncController@syncLinkAge');

/**
 *  9.0 网关删除联动信息
 */
route::post('/gateway/delete/linkAge', 'SyncController@deleteLinkAge');

/**
 *  9.1 网关编辑联动信息
 */
route::post('/gateway/edit/linkAge', 'SyncController@editLinkAge');

/**
 *  9.2 网关获取联动列表
 */
route::post('/gateway/search/linkAge/list', 'SyncController@searchDefineLinkAgeList');

/**
 *  9.3 网关获取联动详细
 */
route::post('/gateway/search/linkAge/info', 'SyncController@searchLinkAgeInfo');


/**
 *  9.4 APP获取联动列表
 */
route::post('/app/search/linkAge/list', 'AppController@searchDefineLinkAgeList');

/**
 *  9.5 APP获取联动详细
 */
route::post('/app/search/linkAge/info', 'AppController@searchLinkAgeInfo');



/**
 *  9.6 网关修改联动启用状态
 */
route::post('/gateway/edit/linkAge/enable', 'SyncController@editLinkAgeEnable');


/**
 *  9.7 网关修改场景启用状态
 */
route::post('/gateway/edit/robot/enable', 'GateWayController@editRobotEnable');

/**
 *  9.8 网关新增设备
 *  @modify 网关多次上报会加重复数据->添加之前做判断 2016-03-19 于汝意
 */
route::post('/gateway/added/device', 'GateWayController@addedDevice');


/**
 *  9.9 网关查询ctrl详细
 */
route::post('/gateway/search/ctrl/info', 'SyncController@searchCtrlForGatewayAsOnce');


/**
 *  10.0 网关解除绑定操作 使用中
 */
route::post('/gateway/remove/bind', 'GateWayController@removeGatewayBind');

/**
 *  网关自动升级
 */
route::get('/gateway/upgrade','GateWayController@downLoadUpgradeFile');

/**
 *  10.1 网关检测最新版本信息
 */
route::post('/gateway/version/newest', 'GateWayController@searchNewestVersion');

/**
 *  10.2 网关检测自身在线状态
 */
route::post('/gateway/search/status', 'GateWayController@searchGatewayStatus');

/**
 *  10.3 解除设备绑定
 */
route::post('/gateway/remove/device', 'GateWayController@removeGatewayDeviceBind');

/**
 *  9.9 网关查询ctrl详细
 */
route::post('/gateway/edit/timer/enable', 'SyncController@editTimerEnable');

/**
 *  10.2 网关新增设备对应关系备份
 *  先清后加 where gateway_id and device_id
 *  网关添加设备时对应的 device_id，devicetype之间的应射
 *  table device_relation
 */
route::post('/gateway/add/deviceRelation', 'GateWayController@addDeviceRelation');

/**
 *  10.3 删除网关新增设备对应关系备份
 *  网关添加设备时对应的 device_id，devicetype之间的应射
 *  table device_relation
 */
route::post('/gateway/delete/deviceRelation', 'GateWayController@deleteDeviceRelation');

/**
 *  10.4 获取网关新增设备对应关系备份
 *  网关添加设备时对应的 device_id，devicetype之间的应射
 *  table device_relation
 */
route::post('/gateway/list/deviceRelation', 'GateWayController@listDeviceRelation');

/**
 *  10.5 网关上报ip信息
 */
route::post('/gateway/sync/ip', 'SyncController@syncGatewayIP');

/**
 *  10.6 按设备操控性分类获取设备列表
 *  1条件+控制,2条件-,3-控制,4--
 */
route::post('/app/device/listByCondition', 'AppController@deviceListByCondition');

/**
 *  10.7  APP获取设备版本信息，型号，创建时间等信息
 */
route::post('/app/device/getDeviceVersion', 'AppController@getDeviceVersion');

/**
 *  10.2 app检测网关在线状态  route::post('/gateway/search/status', 'GateWayController@searchGatewayStatus');
 */
route::post('/app/gateway/status', 'AppController@getGatewayStatus');


/**
 *  10.8 接收app发送的用户反馈
 */
route::post('/app/feedback', 'AppController@receiveUserFeedback');

/**
 *  10.9 查询网关版本信息
 *   名称，网关型号，软件版本，添加时间
 */
route::post('/gateway/getgatewayinfo', 'AppController@getGatewayInfo');


/**
 *  5.4 修改设备名称
 */
route::get('/try/time', function(){
    $test = explode('.', 'abc');
    dd($test);//output txt
	$now = microtime();
	$time = date('Y-m-d H:i:s', time());
    var_dump(md5('123')) ;var_dump(md5('123',true)) ;
	echo '结果是'.'$val';
	print('现在时间是：'.$now);
	print('arg时间为：'.$time);
});//'AppController@deviceName');

route::get('/get/env', 'UserController@getEnv');
});