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
    Route::get('getusbyteam/{id}', 'UsersController@getusbyteam');
    Route::post('auth/login', 'Auth\AuthController@login');
    //auth
    Route::post('auth/logout', 'Auth\AuthController@logout');
    Route::post('auth/facebook', 'Auth\AuthFacebookController@login');
    Route::post('auth/google', 'Auth\AuthGoogleController@login');
    Route::post('password/forgot/request', 'Auth\ForgotPasswordController@getResetToken');
    Route::post('password/forgot/reset', 'Auth\ResetPasswordController@reset');

    //user information
    Route::get('me', 'UsersController@me');
    // Route::get('teamlead', 'UsersController@getUsTeamLead');
    //registration a new user
    Route::post('register', 'UsersController@register');

    //create a new user from supper admin with another role
    // Route::resource('timeabsence', 'TimeAbsencesController');

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

    Route::get('test', 'RegistrationsController@test');
    Route::put('updated/{id}', 'RegistrationsController@updateStatusRegis'); //cái này gửi thông tin và thay đổi trạng thái đã duyệt
    Route::put('updated2/{id}', 'RegistrationsController@updateStatusRegis2'); //cái này gửi thông tin và thay đổi trạng thái ko duyệt
    Route::put('updated3/{id}', 'RegistrationsController@updateMessage'); //cái này dùng khi chỉ gửi mess mà ko update thông tin;

    Route::get('search', 'RegistrationsController@search');
    Route::get('searchpending', 'RegistrationsController@searchPending');
    Route::get('searchapproved', 'RegistrationsController@searchApproved');
    Route::get('searchdisapproved', 'RegistrationsController@searchDisApproved');
    Route::get('searchregispending', 'RegistrationsController@searchRegisPending'); //search trong danh sách mấy cái đang chờ duyệt;

    Route::get('information', 'RegistrationsController@getProfile');
    Route::get('approved', 'RegistrationsController@getApproved');
    Route::get('disapproved', 'RegistrationsController@getDisApproved');
    Route::get('pending', 'RegistrationsController@getRegisPending');
    Route::get('sum/{id}', 'TimeAbsencesController@statistic');

    Route::get('statistic', 'UsersController@getInformation');

    
    // //edit a registration
    // Route::post('absence/edit', 'RegistrationsController@update');
    // //remove a gistration
    // Route::post('absence/remove', 'RegistrationsController@destroy');
    Route::resource('type', 'TypesController');
    Route::resource('team', 'TeamsController');
    Route::resource('position', 'PositionsController');
    Route::resource('approver', 'ApproversController');
    Route::get('to', 'ApproversController@getMailto');
    Route::get('cc', 'ApproversController@getMailcc');
    Route::resource('track', 'TracksController');

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
