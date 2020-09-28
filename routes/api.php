<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login-true', 'API\UserController@login');                                   // Login API
Route::post('signup-true', 'API\UserController@request_phone_verify');                   // SignUp API - Request verification code
Route::post('resend-verify-code', 'API\UserController@retry_phone_verify');                     // Request verification code again
Route::post('verify-true', 'API\UserController@phone_verify');                       // Check verification code
//Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => ['auth:api']], function(){
    Route::get('getProfile', 'API\UserController@get_profile'); //Get Profile
});
