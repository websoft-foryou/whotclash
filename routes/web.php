<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Auth::routes();

Route::get('/', function () {
    //return view('welcome');
    return redirect("admin/index");
});

Route::get('/apidocs', function () {
    return view('apidocs/index');
})->name('apidocs');


/** Admin Routes */
Route::group(["prefix" => "admin","namespace" => "Admin"],function(){
    Route::get("login","HomeController@open_login_page")->name("login");
    Route::post("login","HomeController@login")->name("admin-login");
    Route::get("admin-logout","HomeController@logout")->name("admin-logout");

    Route::group(["middleware" => "auth:admin"],function(){
        Route::get("index","DashboardController@index")->name("open-dashboard-page");
        Route::get("user-management","UserManagementController@index")->name("open-usermanagement-page");
        Route::post("update-user-info","UserManagementController@update")->name("update-user-info");
        Route::post("block-user","UserManagementController@block")->name("block-user");
        Route::post("remove-user","UserManagementController@remove")->name("remove-user");
        Route::post("user-game-history","UserManagementController@game_history")->name("user-game-history");
        Route::get("game-history","GameHistoryController@index")->name("open-gamehistory-page");
        Route::get("change-password","ProfileController@open_change_password_page")->name("open-change-password-page");
        Route::post("change-password","ProfileController@change_password")->name("change-password");
    });
});

