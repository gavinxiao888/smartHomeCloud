<?php
Route::get('/maxuejie', function() {
    var_dump("智能遮阳挡");
    var_dump(putenv("NAME=能遮阳挡22"));
    var_dump(getenv("NAME"));
    echo env('ADMIN_NAV_DEVICE_NAME');
});
require_once realpath(__DIR__) . '/Routes/Platform.php';
require_once realpath(__DIR__). '/Routes/Api.php';
require_once realpath(__DIR__). '/Routes/Web.php';
require_once realpath(__DIR__). '/Routes/Admin.php';