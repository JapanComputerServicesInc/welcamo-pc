<?php

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
Route::redirect('/', 'login');


/*
|--------------------------------------------------------------------------
| 全ユーザー ルート
|--------------------------------------------------------------------------
|
| 全ユーザーがアクセスできるルート
|
|
*/
Route::middleware(['throttle:20,1'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 認証 ルート
    |--------------------------------------------------------------------------
    | 
    | ユーザーを認証する為のルートでAuthパッケージで定義されるルール
    | 
    */
    Route::namespace('Auth')->group(function() {
        Route::get('/login'  , 'LoginController@showLoginForm')->name('login');  // ログイン画面表示
        Route::post('/login' , 'LoginController@login');                         // ログインアクション
        Route::post('/logout', 'LoginController@logout')->name('logout');        // ログアウトアクション
    });

});


/*
|--------------------------------------------------------------------------
| 認証済ユーザー（一般） ルート
|--------------------------------------------------------------------------
|
| 認証済のユーザー、且つ、一般ユーザー（※）がアクセスできるルート
|
| ※管理者権限を持たないユーザー
|
*/
Route::middleware(['auth', 'throttle:60,1'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 入館予定 ルート
    |--------------------------------------------------------------------------
    | 
    | ログイン後の初期表示画面、且つ、本アプリケーションのデフォルト画面のルート
    | 
    */
    Route::prefix('schedules')->group(function() {
        Route::get('/'        , 'SchedulesController@index')->name('schedules');
        Route::post('/store'  , 'SchedulesController@store')->name('store_schedule');
        Route::post('/delete' , 'SchedulesController@delete')->name('delete_schedule');
    });

    /*
    |--------------------------------------------------------------------------
    | 入館管理 ルート
    |--------------------------------------------------------------------------
    | 
    | 入館一覧、入館内容詳細画面用のルート
    | 
    */
    Route::prefix('entries')->group(function() {
        Route::get('/'        , 'EntriesController@index')->name('entries');
        Route::post('/show'   , 'EntriesController@show')->name('show_exit_all');
        Route::post('/exit'   , 'EntriesController@exit')->name('exit_all');
    });

    /*
    |--------------------------------------------------------------------------
    | 入退館履歴 ルート
    |--------------------------------------------------------------------------
    | 
    | 入退館履歴一覧、入退館内容詳細画面用のルート
    | 
    */
    Route::prefix('histories')->group(function() {
        Route::get('/'         , 'HistoriesController@index')->name('histories');
        Route::get('/search'   , 'HistoriesController@search')->name('search_histories');
        Route::post('/search'  , 'HistoriesController@search');
        Route::get('/history'  , 'HistoriesController@history');
        Route::post('/history' , 'HistoriesController@history')->name('history');
        Route::post('/update'  , 'HistoriesController@update')->name('update_history');
        Route::post('/delete'  , 'HistoriesController@delete')->name('delete_history');
    });

    /*
    |--------------------------------------------------------------------------
    | 入館者情報 ルート
    |--------------------------------------------------------------------------
    | 
    | 入館者情報の更新、退館、退館解除アクション用のルート
    | 
    */
    Route::prefix('visitor')->group(function() {
        Route::post('/update'         , 'VisitorsController@update')->name('update_visitor');
        Route::post('/exit'           , 'VisitorsController@exit')->name('exit_visitor');
        Route::post('/cancel'         , 'VisitorsController@cancel')->name('cancel_exit');
        Route::get('/sigunature/{id?}', 'VisitorsController@signature')
            ->where('id', '[0-9]+')->name('signature'); // サイン画像表示
    });

    /*
    |--------------------------------------------------------------------------
    | パスワード変更 ルート
    |--------------------------------------------------------------------------
    | 
    | ログインユーザーのパスワード変更アクション用のルート
    | 
    */
    Route::prefix('change_password')->group(function() {
        Route::get('/'        , 'PasswordController@index')->name('change_password');
        Route::post('/update' , 'PasswordController@update')->name('update_password');
    });

});


/*
|--------------------------------------------------------------------------
| 認証済ユーザー（管理者） ルート
|--------------------------------------------------------------------------
|
| 認証済のユーザー、且つ、管理者ユーザーのみがアクセスできるルート
|
*/
Route::middleware(['auth', 'can:admin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 責任者確認 ルート
    |--------------------------------------------------------------------------
    | 
    | 責任者確認一覧、入退館内容詳細画面用のルート
    | 
    */
    Route::prefix('approvals')->group(function() {
        Route::get('/'          , 'ApprovalsController@index')->name('approvals');
        Route::get('/search'    , 'ApprovalsController@search')->name('search_approvals');
        Route::post('/search'   , 'ApprovalsController@search');
        Route::post('/approval' , 'ApprovalsController@approval')->name('approval');
    });

    /*
    |--------------------------------------------------------------------------
    | ユーザー管理 ルート
    |--------------------------------------------------------------------------
    | 
    | ユーザー管理機能用のルート
    | 
    */
    Route::prefix('users')->group(function() {
        Route::get('/'        , 'UsersController@index')->name('users');
        Route::post('/'       , 'UsersController@index');
        Route::post('/store'  , 'UsersController@store')->name('store_user');
        Route::post('/update' , 'UsersController@update')->name('update_user');
        Route::post('/delete' , 'UsersController@delete')->name('delete_user');
    });

    /*
    |--------------------------------------------------------------------------
    | 入館理由管理 ルート
    |--------------------------------------------------------------------------
    | 
    | 入館理由管理機能用のルート
    | 
    */
    Route::prefix('purposes')->group(function() {
        Route::get('/'        , 'PurposesController@index')->name('purposes');
        Route::post('/store'  , 'PurposesController@store')->name('store_purpose');
        Route::post('/update' , 'PurposesController@update')->name('update_purpose');
        Route::post('/delete' , 'PurposesController@delete')->name('delete_purpose');
    });

    /*
    |--------------------------------------------------------------------------
    | 入館証管理 ルート
    |--------------------------------------------------------------------------
    | 
    | 入館証管理機能用のルート
    | 
    */
    Route::prefix('admissions')->group(function() {
        Route::get('/'        , 'AdmissionsController@index')->name('admissions');
        Route::post('/store'  , 'AdmissionsController@store')->name('store_admission');
        Route::post('/update' , 'AdmissionsController@update')->name('update_admission');
        Route::post('/delete' , 'AdmissionsController@delete')->name('delete_admission');
    });

});
