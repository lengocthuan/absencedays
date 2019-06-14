<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

/**
 * Authorized resources
 */
Route::group(['prefix' => 'v1'], function () {
    Route::post('auth/login', 'Auth\AuthController@login');
    //auth
    Route::post('auth/logout', 'Auth\AuthController@logout');
    Route::post('auth/facebook', 'Auth\AuthFacebookController@login');
    Route::post('password/forgot/request', 'Auth\ForgotPasswordController@getResetToken');
    Route::post('password/forgot/reset', 'Auth\ResetPasswordController@reset');

    //user information
    Route::get('me', 'UsersController@me');
    //registration a new user
    Route::post('register', 'UsersController@register');

    //create a new user from supper admin with another role


    //edit a user

    //remove a user

    //create a new team
    //edit a team
    //remove a team
    //create a new position
    //edit a position
    //remove a position
    //create new type
    //edit a type
    //remove a type
    //create new registration
    // Route::post('absence/registration', 'RegistrationsController@store');
    Route::resource('absence', 'RegistrationsController');

    Route::post('test', 'RegistrationsController@test');
    // //edit a registration
    // Route::post('absence/edit', 'RegistrationsController@update');
    // //remove a gistration
    // Route::post('absence/remove', 'RegistrationsController@destroy');
});

Route::group(['prefix' => 'v1'], function () {
    //auth
    Route::post('auth/logout', 'Auth\AuthController@logout');
    //password
    Route::post('password/change', 'UsersController@changePass');
    //user information
    Route::get('me', 'UsersController@me');
    //users
    Route::resource('users', 'UsersController');
    //promotions
    // images
    Route::resource('images', 'ImagesController')->only(['store', 'destroy']);
});
