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
Route::post('add-friend', 'API\UserController@add_friend');
Route::post('get-friend', 'API\UserController@get_friend');
Route::post('add-invite', 'API\UserController@add_invite');
Route::post('get-invite', 'API\UserController@get_invite');
Route::post('accept-invite', 'API\UserController@accept_invite');
Route::post('remove-invite', 'API\UserController@remove_invite');
Route::post('remove-invite-room', 'API\UserController@remove_invite_room');
Route::post('get-user-list', 'API\UserController@get_user_list');

//Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => ['auth:api']], function(){
    Route::get('getProfile', 'API\UserController@get_profile'); //Get Profile

    Route::post('request-email-confirm', 'API\UserController@request_email_confirm_code'); //Request confirm code for email verification
    Route::get('getCampaign', 'API\UserController@getCampaign'); //Get Campaign
    Route::post('saveCampaign', 'API\UserController@saveCampaign'); //Save Campaign
    Route::get('get-user-score', 'API\UserController@getUserScore'); //Get user score
    Route::get('get-user-rank', 'API\UserController@getUserRank'); //Get all ranking
    Route::post('contactUs', 'API\UserController@contactUs'); //Contact Us
    Route::post('updateProfile', 'API\UserController@updateProfile'); //Update User Profile
    Route::put('updateBankDetails', 'API\BankController@updateBankDetails'); //Update Bank Details

    Route::post("start_game","API\GameController@start_game");
    Route::post("end_game","API\GameController@end_game");
    Route::any("game_history","API\GameController@game_history");
    Route::get('get-game-setting','API\GameController@get_game_setting');

    Route::post("charge-customer","API\BankController@chargeCustomer");
    Route::get("get-coin","API\UserController@getCoins");
    Route::post("update-coin","API\UserController@updateCoins");
    Route::post("withdraw-coin","API\UserController@withDrawCoin");

    Route::get("check-block-status","API\UserController@checkBlockStatus");
});
