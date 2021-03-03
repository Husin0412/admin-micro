<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Dashboard\DashboarController;
use App\Http\Controllers\Master\UserGroupController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\StudentController;
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

Route::get('500', function (Request $request) {
    return view('error-page.500', ['response' => $request->all()['response'], 'message' => $request->all()['message']]);
})->name('500');

Route::match(['get', 'post'], 'login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth_user'], function () {
    Route::prefix('/')->group(function() {
        Route::get('/', [DashboarController::class, 'index']);
    });

    route::prefix('/user')->group(function() {
        Route::get('/group', [UserGroupController::class, 'index'] );
        Route::get('/group/add', [UserGroupController::class, 'add'] );
        Route::post('/group/save', [UserGroupController::class, 'save'] );
        Route::match(['get','post'],'/group/edit', [UserGroupController::class, 'edit'] );
        Route::post('/group/update', [UserGroupController::class, 'update'] );
        Route::post('/group/delete', [UserGroupController::class, 'delete'] );
    });

    route::prefix('/users')->group(function() {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/add', [UserController::class, 'add']);
        Route::post('/save', [UserController::class, 'save']);
        Route::match(['get','post'], '/edit', [UserController::class, 'edit']);
        Route::post('/update', [UserController::class, 'update']);
        Route::post('/delete', [UserController::class, 'delete']);
    });

    route::prefix('/student')->group(function() {
        Route::get('/', [StudentController::class, 'index']);
        Route::post('/details', [StudentController::class, 'details']);
    });

});


