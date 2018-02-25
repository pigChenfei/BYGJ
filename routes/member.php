<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/26
 * Time: 15:18
 */



Route::group(['namespace' => 'Member'],function(){

    Route::resource('players','PlayerController');

});
