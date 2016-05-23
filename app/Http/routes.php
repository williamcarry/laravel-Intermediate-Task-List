<?php

Route::group(['middleware' => ['web']], function () {
    /*
     * <li><a href="{{ url('/login') }}">Login</a></li>
     * 模板中的 url('/login'）为对应view/auth/login.blade.php
     * 注意Route::auth();   view/auth/login 和 url('/login') 他们之间的联系。注意看。。就明白怎么回事了。
     */
    Route::auth();
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/tasks', 'TaskController@index');
    Route::post('/task', 'TaskController@store');
    Route::delete('/task/{task}', 'TaskController@destroy');
});
