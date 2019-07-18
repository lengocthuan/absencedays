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
    //auth
    Route::post('auth/login', 'Auth\AuthController@login');
    Route::post('auth/logout', 'Auth\AuthController@logout');
    Route::post('auth/google', 'Auth\AuthGoogleController@login');

    //user information
    Route::get('me', 'UsersController@me');
    Route::post('registers', 'UsersController@register');
    Route::get('get_users_by_team/{id}', 'UsersController@getUsersByTeam');
    Route::get('get_mails', 'UsersController@getMail');

    Route::resource('absences', 'RegistrationsController');

    Route::get('tests', 'RegistrationsController@test');
    Route::put('updated_1/{id}', 'RegistrationsController@updateStatus1'); //approved
    Route::put('updated_2/{id}', 'RegistrationsController@updateStatus2'); //disapproved
    Route::put('updated_3/{id}', 'RegistrationsController@updateMessage'); //send message and no update status;

    Route::get('searches', 'RegistrationsController@search');
    Route::get('search_regispending', 'RegistrationsController@searchRegistrationPending'); //search trong danh sách mấy cái đang chờ duyệt;

    Route::get('informations', 'RegistrationsController@getStatus');
    Route::get('pending', 'RegistrationsController@getRegistrationPending');
    Route::get('sum/{id}', 'TimeAbsencesController@statistic');

    Route::resource('types', 'TypesController');
    Route::resource('teams', 'TeamsController');
    Route::resource('positions', 'PositionsController');
    Route::resource('approvers', 'ApproversController');

    // Route::get('mails_to', 'ApproversController@getMailto');
    // Route::get('mails_cc', 'ApproversController@getMailcc');
    
    Route::resource('tracks', 'TracksController');
    Route::resource('time_absences', 'TimeAbsencesController');

    Route::get('statistical', 'TracksController@getStatistical');
    Route::get('update_all_users', 'TracksController@updateFromUser');
    Route::get('exports', 'TracksController@export');
    Route::get('exports_statistical', 'TracksController@exportStatistical');

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
});
