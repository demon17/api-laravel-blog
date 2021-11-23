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

/**********************************   Categories   *******************************************/
Route::get('categories','CategoryController@index')->middleware('auth:api');
Route::post('category/check/title','CategoryController@checkTitle')->middleware('auth:api');
Route::post('category/check/description','CategoryController@checkDescription')->middleware('auth:api');
Route::post('category/store','CategoryController@store')->middleware('auth:api');
Route::get('category/{id}/show','CategoryController@show');
Route::post('category/edit/check/title','CategoryController@checkEditTitle')->middleware('auth:api');
Route::post('category/edit/check/description','CategoryController@checkEditDescription')->middleware('auth:api');
Route::post('category/update','CategoryController@update')->middleware('auth:api');
Route::post('category/remove','CategoryController@remove')->middleware('auth:api');
Route::get('category/{keyword}/search','CategoryController@searchCategory');
// Top categories
Route::get('category/top','CategoryController@topCategories');

/**********************************   Articles   *******************************************/
Route::get('articles','ArticleController@index')->middleware('auth:api');
Route::post('article/check/title','ArticleController@checkTitle')->middleware('auth:api');
Route::post('article/check/category','ArticleController@checkCategory')->middleware('auth:api');
Route::post('article/check/description','ArticleController@checkDescription')->middleware('auth:api');
Route::get('article/{id}/show','ArticleController@show');
// Articles CRUD
Route::post('article/store','ArticleController@store')->middleware('auth:api');
Route::post('article/update','ArticleController@update')->middleware('auth:api');
Route::post('article/remove','ArticleController@remove')->middleware('auth:api');
// Search articles
Route::post('article/search','ArticleController@searchArticle');

/**********************************   Author   *******************************************/
Route::get('authors','AuthorController@index')->middleware('auth:api');
Route::post('author/check/name','AuthorController@checkName');
Route::post('author/check/email','AuthorController@checkEmail');
Route::post('author/check/password','AuthorController@checkPassword');
Route::post('register','AuthorController@register');
Route::post('login','AuthorController@login');
Route::get('author/detail','AuthorController@getAuthor')->middleware('auth:api');
Route::post('logout','AuthorController@logout')->middleware('auth:api');
