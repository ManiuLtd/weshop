<?php

Route::match(["get", "post"],
             "region/children/{region?}",
             "RegionController@children")
    ->name("region.children");
Route::match(["get", "post"],
             "express",
             "ExpressController@fetch")
    ->name("express");

Route::domain("admin.mafkj.com")->group(function () {
    Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name("admin.login");
    Route::post('/login', 'AdminAuth\LoginController@login')->name("admin.login");
});