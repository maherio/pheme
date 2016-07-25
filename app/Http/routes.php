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

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');

//twitter routes
Route::get('twitter/login', [
    'uses' => 'TwitterController@loginWithTwitter',
    'as' => 'twitter.login',
]);
Route::get('twitter/callback', [
    'uses' => 'TwitterController@loginCallback',
    'as' => 'twitter.callback',
]);

//yahoo routes
Route::get('yahoo/login', [
    'uses' => 'YahooController@loginWithYahoo',
    'as' => 'yahoo.login',
]);
