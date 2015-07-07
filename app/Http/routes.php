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

// Route group for API versioning
Route::group(['prefix' => 'api/v1', 'middleware' => 'guest'], function() {
    Route::post('user', ['uses' => 'UserController@store']);
    Route::post('login', ['uses' => 'UserController@login']);
    Route::post('password/remind', ['uses' => 'UserController@remind']);
    Route::post('password/reset', ['uses' => 'UserController@reset']);

    Route::get('bookmark/public', ['uses' => 'BookmarkController@index_public']);
});

Route::group(['prefix' => 'api/v1', 'middleware' => 'auth'], function() {
    Route::put('user', ['uses' => 'UserController@update']);
    Route::get('logout', ['uses' => 'UserController@logout']);

    Route::resource('bookmark', 'BookmarkController');
    Route::resource('tag', 'TagController');
    Route::get('autocomplete', ['uses' => 'TagController@autocomplete']);
});

Route::get('link/{id}', ['uses' => 'HomeController@link']);

Route::get('password/reset/{token}', ['middleware' => 'guest', function($token) {
    return View::make('index')->with('token', $token);
}]);

Route::get('{any}', function() {
    return View::make('index')->with('token', '');
})->where('any', '(.*)');

