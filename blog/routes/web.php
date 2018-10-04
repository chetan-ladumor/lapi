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

Route::get('/', [
	'uses'=>'FrontEndController@index',
	'as'=>'index'
]);

Route::get('/blog/{slug}',[
	'uses'=>'FrontEndController@single',
	'as'=>'post.single'
]);

Route::get('/category/{id}',[
	'uses'=>'FrontEndController@category',
	'as'=>'category.single'
]);

Route::get('/tag/{id}',[
	'uses'=>'FrontEndController@tag',
	'as'=>'tag.single'
]);

Route::get('/results',function(){
	$posts=\App\Post::where('title','like', '%'.request('query') . '%')->get();

	return view('results')->with('posts',$posts)
						  ->with('title', 'Search results : ' . request('query'))
    						->with('categories',\App\Category::take(5)->get())
    						->with('settings',\App\Setting::first())
    						->with('query',request('query'))
    						->with('all_tag',\App\Tag::all());

});

Auth::routes();



Route::group(['prefix'=>'admin','middleware'=>'auth'],function(){

	Route::get('/home',[
		'uses'=>'HomeController@index',
		'as'=>'home'
	]);

	Route::get('/category/create',[
		'uses'=>'CategoriesController@create',
		'as'=>'category.create'
	]);
	
	Route::post('/category/store',[
		'uses'=>'categoriesController@store',
		'as'=>'category.store'
	]);

	Route::get('/categories',[
		'uses'=>'CategoriesController@index',
		'as'=>'categories'
	]);
	Route::get('/category/edit/{id}',[
		'uses'=>'CategoriesController@edit',
		'as'=>'category.edit'
	]);
	Route::post('/category/update/{id}',[
		'uses'=>'categoriesController@update',
		'as'=>'category.update'
	]);
	Route::get('/category/delete/{id}',[
		'uses'=>'CategoriesController@destroy',
		'as'=>'category.delete'
	]);

	Route::get('/post/create',[
		'uses'=>'PostController@create',
		'as'=>'posts.create'
	]);

	Route::post('/post/store',[
		'uses'=>'PostController@store',
		'as'=>'posts.store'
	]);

	Route::get('posts',[
		'uses'=>'PostController@index',
		'as'=>'posts'
	]);

	Route::get('/post/edit/{id}',[
		'uses'=>'PostController@edit',
		'as'=>'posts.edit'
	]);
	Route::post('/post/update/{id}',[
		'uses'=>'PostController@update',
		'as'=>'posts.update'
	]);
	Route::get('/post/delete/{id}',[
		'uses'=>'PostController@destroy',
		'as'=>'posts.delete'
	]);

	Route::get('/post/trashed',[
		'uses'=>'PostController@trashed',
		'as'=>'posts.trashed'
	]);

	Route::get('/post/kill/{id}',[
		'uses'=>'PostController@kill',
		'as'=>'posts.kill'
	]);

	Route::get('/post/restore/{id}',[
		'uses'=>'PostController@restore',
		'as'=>'posts.restore'
	]);

	Route::get('tags',[
		'uses'=>'TagsController@index',
		'as'=>'tags'
	]);

	Route::get('tag/create',[
		'uses'=>'TagsController@create',
		'as'=>'tags.create'
	]);
	Route::post('tag/store',[
		'uses'=>'TagsController@store',
		'as'=>'tag.store'
	]);

	Route::get('tag/edit/{id}',[
		'uses'=>'TagsController@edit',
		'as'=>'tag.edit'
	]);

	Route::post('tag/update/{id}',[
		'uses'=>'TagsController@update',
		'as'=>'tag.update'
	]);

	Route::get('tag/delete/{id}',[
		'uses'=>'TagsController@destroy',
		'as'=>'tag.delete'
	]);

	Route::get('/test',function(){
		//return App\Category::find(1)->posts;
		//return App\Post::find(6)->category;
		//return App\Post::find(7)->tags;
		//return App\User::find(1)->profile;
		return App\Profile::find(1)->user;

	});

	Route::get('/users',[
		'uses'=>'UsersController@index',
		'as'=>'users'
	]);

	Route::get('/user/create/',[
		'uses'=>'UsersController@create',
		'as'=>'user.create'
	]);

	Route::post('user/store/',[
		'uses'=>'UsersController@store',
		'as'=>'user.store'
	]);

	Route::get('/user/admin/{id}',[
		'uses'=>'UsersController@admin',
		'as'=>'user.admin'
	]);

	Route::get('/user/not-admin/{id}',[
		'uses'=>'UsersController@not_admin',
		'as'=>'user.not.admin'
	]);

	Route::get('/user/profile',[
		'uses'=>'ProfilesController@index',
		'as'=>'user.profile'
	]);

	Route::post('/user/profile/update',[
		'uses'=>'ProfilesController@update',
		'as'=>'user.profile.update'
	]);

	Route::get('/user/delete/{id}',[
		'uses'=>'UsersController@destroy',
		'as'=>'user.delete'
	]);

	Route::get('/settings',[
		'uses'=>'SettingsController@index',
		'as'=>'settings'
	])->middleware('admin');

	Route::post('/settings/update',[
		'uses'=>'SettingsController@update',
		'as'=>'settings.update'
	])->middleware('admin');

});


