<?php

namespace App\Http\Controllers;

use App\Setting;
use App\Category;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    public function index()
    {
    	return view('index')->with('title',Setting::first()->site_name)
    						->with('categories',Category::take(5)->get())
    						->with('first_post',Post::orderBy('created_at','desc')->first())
    						->with('second_post',Post::orderBy('created_at','desc')->skip(1)->take(1)->get()->first())
    						->with('third_post',Post::orderBy('created_at','desc')->skip(2)->take(1)->get()->first())
    						->with('node',Category::find(3))
    						->with('java',Category::find(5))
    						->with('settings',Setting::first());
    }

    public function single($slug)
    {
    	$post=Post::where('slug',$slug)->first();

    	$next_post=Post::where('id','>',$post->id)->min('id');  // if post id upper is 5 then it will get 6 
    	$pre_post=Post::where('id','<',$post->id)->max('id');

    	return view('single')->with('post',$post)
    						->with('title',$post->title)
    						->with('categories',Category::take(5)->get())
    						->with('settings',Setting::first())
    						->with('next',Post::find($next_post))
    						->with('pre',Post::find($pre_post))
    						->with('tags',Tag::all());
    }

    public function category($id)
    {
    	$category=Category::find($id);

    	return view('category')->with('category',$category)
    							->with('title',$category->name)
    							->with('settings',Setting::first())
    							->with('categories',Category::take(5)->get())
    							->with('tags',Tag::all());;
    }
    public function tag($id)
    {
    	$tags=Tag::find($id);

    	return view('tag')->with('tags',$tags)
    							->with('title',$tags->tag)
    							->with('settings',Setting::first())
    							->with('categories',Category::take(5)->get())
    							->with('all_tag',Tag::all());
    }
}
