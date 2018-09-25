<?php
use think\Route;


//单体注册
Route::get('user/:id','user/index');

//分组注册
Route::group('user',function(){
    Route::get('');
    Route::post('');
    Route::delete('');
    Route::put('');
});
//批量注册

Route::get(['user/:id'=>'user/index'],['user/update','user/up'],[],[]);