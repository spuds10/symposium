<?php

/**
 * Public
 */
Route::get('/', function () {
    return View::make('home');
});

Route::get('what-is-this', function () {
    return View::make('what-is-this');
});

Route::get('speakers', [
    'as' => 'speakers-public.index',
    'uses' => 'PublicProfileController@index'
]);
Route::get('u/{profileSlug}', [
    'as' => 'speakers-public.show',
    'uses' => 'PublicProfileController@show'
]);
Route::get('u/{profileSlug}/talks/{talkId}', [
    'as' => 'speakers-public.talks.show',
    'uses' => 'PublicProfileController@showTalk'
]);
Route::get('u/{profileSlug}/bios/{bioId}', [
    'as' => 'speakers-public.bios.show',
    'uses' => 'PublicProfileController@showBio'
]);
Route::get('u/{profileSlug}/email', [
    'as' => 'speakers-public.email',
    'uses' => 'PublicProfileController@getEmail'
]);
Route::post('u/{profileSlug}/email', [
    'as' => 'speakers-public.email',
    'uses' => 'PublicProfileController@postEmail'
]);

// temp fix
Route::get('conferences/create', ['middleware' => 'auth', 'as' => 'conferences.create', 'uses' => 'ConferencesController@create']);

Route::get('conferences/{id}', ['as' => 'conferences.public', 'uses' => 'ConferencesController@show']);

/**
 * App
 */
Route::get('log-out', ['as' => 'log-out', 'uses' => 'AuthController@logout']);

Route::group(['middleware' => 'guest'], function () {
    Route::get('password/email', 'Auth\PasswordController@getEmail');
    Route::post('password/email', 'Auth\PasswordController@postEmail');

    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
    Route::post('password/reset', 'Auth\PasswordController@postReset');

    Route::get('log-in', ['as' => 'log-in', 'uses' => 'AuthController@getLogin']);
    Route::post('log-in', 'AuthController@postLogin');

    Route::get('sign-up', ['as' => 'sign-up', 'uses' => 'AccountController@create']);
    Route::post('sign-up', 'AccountController@store');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('account', ['as' => 'account.show', 'uses' => 'AccountController@show']);
    Route::get('account/edit', ['as' => 'account.edit', 'uses' => 'AccountController@edit']);
    Route::put('account/edit', 'AccountController@update');
    Route::get('account/delete', ['as' => 'account.delete', 'uses' => 'AccountController@delete']);
    Route::post('account/delete', 'AccountController@destroy');
    Route::get('account/export', ['as' => 'account.export', 'uses' => 'AccountController@export']);

    Route::post('submissions', 'SubmissionsController@store');
    Route::delete('submissions', 'SubmissionsController@destroy');

    // Joind.in (@todo separate controller)
    Route::get('conferences/joindin/import/{eventId}', 'ConferencesController@joindinImport');
    Route::get('conferences/joindin/import', 'ConferencesController@joindinImportList');
    Route::get('conferences/joindin/all', 'ConferencesController@joindinImportAll');

    Route::get('conferences/{id}/favorite', 'ConferencesController@favorite');
    Route::get('conferences/{id}/unfavorite', 'ConferencesController@unfavorite');

    // Necessary for GET-friendly delete because lazy
    Route::get('talks/{id}/delete', ['as' => 'talks.delete', 'uses' => 'TalksController@destroy']);
    Route::get('conferences/{id}/delete', ['as' => 'conferences.delete', 'uses' => 'ConferencesController@destroy']);
    Route::get('bios/{id}/delete', ['as' => 'bios.delete', 'uses' => 'BiosController@destroy']);

    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
    Route::resource('talks', 'TalksController');
    Route::resource('conferences', 'ConferencesController');
    Route::resource('bios', 'BiosController');
});

/**
 * API
 */
Route::group(['prefix' => 'api', 'namespace' => 'Api', 'middleware' => 'oauth'], function () {
    Route::get('me', 'MeController@index');
    Route::get('bios/{bioId}', 'BiosController@show');
    Route::get('user/{userId}/bios', 'UserBiosController@index');
    Route::get('talks/{talkId}', 'TalksController@show');
    Route::get('user/{userId}/talks', 'UserTalksController@index');
    Route::get('conferences/{id}', 'ConferencesController@show');
    Route::get('conferences', 'ConferencesController@index');
});

/**
 * OAuth
 */
Route::group(['middleware' => 'auth'], function () {
    Route::get('oauth/authorize', [
        'middleware' => ['check-authorization-params', 'auth'],
        'as' => 'get-oauth-authorize',
        'uses' => 'OAuthController@getAuthorize'
    ]);

    Route::post('oauth/authorize', [
        'middleware' => ['check-authorization-params', 'auth'],
        'as' => 'post-oauth-authorize',
        'uses' => 'OAuthController@postAuthorize'
    ]);
});

Route::post('oauth/access-token', [
    'as' => 'oauth-access-token',
    'uses' => 'OAuthController@postAccessToken'
]);
