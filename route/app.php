<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');
Route::post('upload/:id','index/upload');
Route::get('download','index/download');

Route::resource('user','user');
Route::resource('classes','classes');
Route::resource('course','course');
Route::resource('record','record');
Route::resource('relation','relation');
Route::resource('role','role');
Route::resource('task','task');
Route::resource('term','term');

Route::get('user/:id/task', 'User/task');
Route::get('classes/:id/task', 'Classes/task');


//登录模块
Route::group(function (){
    Route::post('login','user/login');
    Route::post('check','user/check');
    Route::post('logout','user/logout');
});
