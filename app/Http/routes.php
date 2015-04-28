<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::controllers([
//	'auth' => 'Auth\AuthController',
//	'password' => 'Auth\PasswordController',
//]);

// Route group for API versioning
Route::group(array('prefix' => 'api/v1', 'before' => 'guest'), function() {
    Route::post('user', array('uses' => 'UserController@store'));
    Route::post('login', array('uses' => 'UserController@login'));
    Route::post('password/remind', array('uses' => 'UserController@remind'));
    Route::post('password/reset', array('before' => 'guest', 'uses' => 'UserController@reset'));

    Route::get('bookmark/public', array('uses' => 'BookmarkController@index_public'));
});

Route::group(array('prefix' => 'api/v1', 'before' => 'auth.basic'), function() {
    Route::put('user', array('uses' => 'UserController@update'));
    Route::get('logout', array('uses' => 'UserController@logout'));

    Route::resource('bookmark', 'BookmarkController');
    Route::resource('tag', 'TagController');
    Route::get('autocomplete', array('uses' => 'TagController@autocomplete'));
});

Route::get('link/{id}', array('uses' => 'HomeController@link'));

Route::get('password/reset/{token}', array('before' => 'guest', function($token) {
    return View::make('index')->with('token', $token);
}));

Route::get('{any}', function() {
    return View::make('index')->with('token', '');
})->where('any', '(.*)');

