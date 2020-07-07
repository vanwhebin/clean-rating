<?php
use think\facade\Route;

Route::get('/', 'index/Index/index');


Route::group('api', function () {
    Route::group('v1', function () {
        Route::get('campaign/:campaignID/result', 'api/v1.Campaign/ranking');
        Route::get('campaign/latest', 'api/v1.Campaign/latest');
        Route::get('campaign/ratedDept', 'api/v1.Campaign/ratedDept');
        Route::get('dept/:campaignID', 'api/v1.Dept/collection');
        Route::get('dept/:deptID/score', 'api/v1.Dept/rating');
        Route::put('dept/:deptID/status', 'api/v1.Dept/status');
        Route::get('dept/:deptID/rule', 'api/v1.Dept/rule');
        Route::post('dept/:deptID', 'api/v1.Dept/score');
        Route::post('login', 'api/v1.Auth/email');
    });
})->header('Access-Control-Allow-Origin','*')
    ->header('Access-Control-Allow-Credentials', 'true')
    ->middleware(['loginStatus'])
    ->allowCrossDomain();