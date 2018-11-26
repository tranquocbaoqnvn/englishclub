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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('index');
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix'=>'dashboard','as'=>'dashboard:', 'middleware' => 'auth'], function(){
    Route::get('/', ['as' => 'index', 'uses' => 'Admin\DashboardController@index'])->name('index');
//----------------------------------------------Config--------------------------------------------------
    Route::get('/config/edit', 'Admin\ConfigController@config')->name('config');
    Route::post('/config/edit', 'Admin\ConfigController@postConfig')->name('postConfig');
//----------------------------------------------News--------------------------------------------------
    Route::get('/news', 'Admin\NewsController@news')->name('news');
    Route::get('/news/edit/{id}', 'Admin\NewsController@newsEdit')->name('editNews')->where('id', '[0-9]+');
    Route::post('/news/edit/{id}', 'Admin\NewsController@postNews')->name('postNews');
    Route::get('/news/getlist', 'Admin\NewsController@getList')->name('newsList');
    Route::get('/news/delete/{id}', 'Admin\NewsController@deleteNews')->name('deleteNews');
    Route::get('/news/add', 'Admin\NewsController@addNews')->name('addNews');
    Route::post('/news/add', 'Admin\NewsController@postAddNews')->name('postAddNews');
//----------------------------------------------Upload image TinyMCE--------------------------------------------------
    Route::post('/tinymce/upload', 'Admin\NewsController@tinymceUpload')->name('tinymce');
//----------------------------------------------Find Tags--------------------------------------------------
    Route::get('/tags/find', 'Admin\TagsController@find')->name('findTags');
//--------------------------------------------Posts-------------------------------------------------
    Route::get('/posts', 'Admin\PostController@posts')->name('posts');
    Route::get('/posts/add', 'Admin\PostController@addPosts')->name('addPosts');
    Route::post('/posts/add', 'Admin\PostController@postAddPosts')->name('postAddPosts');
    Route::post('/posts/edit/{id}', 'Admin\PostController@postEditPosts')->name('postEditPosts');
    Route::get('/posts/getlist', 'Admin\PostController@getList')->name('postsList');
    Route::get('/posts/delete/{id}', 'Admin\PostController@deletePosts')->name('deletePosts');
    Route::get('/posts/edit/{id}', 'Admin\PostController@editPosts')->name('editPosts');
//--------------------------------------------Get tags id Ajax-------------------------------------------------   
    Route::post('/news/get-tags-id', 'Admin\NewsController@postGetTagsID')->name('postGetTagsID');
//--------------------------------------------Add new tags-------------------------------------------------  
    Route::post('/news/add/add-new-tags', 'Admin\TagsController@postAddNewTags')->name('postAddNewTags');

});