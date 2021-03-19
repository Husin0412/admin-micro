<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Dashboard\DashboarController;
use App\Http\Controllers\Master\UserGroupController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\StudentController;
// use Modules\ServiceCourse\Http\Controllers\MentorsController;

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

/**/
Route::namespace('\Modules\ServiceCourse\Http\Controllers')->middleware('auth_user')->group(function() {
    route::prefix('/mentors')->group(function() {
        Route::get('/', 'MentorsController@index');
        Route::get('/add', 'MentorsController@add');
        Route::post('/save', 'MentorsController@save');
        Route::match(['get','post'], '/edit', 'MentorsController@edit');
        Route::post('/update', 'MentorsController@update');
        Route::post('/delete', 'MentorsController@delete');
    });

    route::prefix('/courses')->group(function() {
        Route::get('/', 'CoursesController@index');
        Route::get('/add', 'CoursesController@add');
        Route::post('/save', 'CoursesController@save');
        Route::match(['get','post'], '/edit', 'CoursesController@edit');
        Route::post('/update', 'CoursesController@update');
        Route::post('/delete', 'CoursesController@delete');
        /*course image*/
        Route::get('/image', 'CoursesController@addImage');
        Route::post('/saveImage', 'CoursesController@saveImage');
        Route::delete('/deleteImage', 'CoursesController@deleteImage');
    });

    route::prefix('/chapters')->group(function() {
        Route::get('/', 'ChaptersController@index');
        Route::get('/add', 'ChaptersController@add');
        Route::post('/save', 'ChaptersController@save');
        Route::match(['get','post'], '/edit', 'ChaptersController@edit');
        Route::post('/update', 'ChaptersController@update');
        Route::post('/delete', 'ChaptersController@delete');
    });

    route::prefix('/lessons')->group(function() {
        Route::get('/', 'LessonsController@index');
        Route::get('/add', 'LessonsController@add');
        Route::post('/save', 'LessonsController@save');
        Route::match(['get','post'], '/edit', 'LessonsController@edit');
        Route::post('/update', 'LessonsController@update');
        Route::post('/delete', 'LessonsController@delete');
    });
});

