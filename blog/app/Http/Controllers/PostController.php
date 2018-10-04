<?php

namespace App\Http\Controllers;

use App\User;
use App\Category;
use App\Post;//as we are using post model
use App\Tag;
use Session;
use Auth;
use Illuminate\Http\Request;
use Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$users = User::all();
        
        return view('admin.posts.index')->with('posts',Post::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories =Category::all();

        $tags=Tag::all();

        if($categories->count() == 0 || $tags->count()== 0)
        {
            Session::flash('info','You must have some Categories and tags before adding new post.');
            return redirect()->back();
        }
        return view('admin.posts.create')->with('categories',Category::all())->with('tags',Tag::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        

        $this->validate($request,[
            'title'=>'required',
            'featured'=>'required|image',
            'content'=>'required',
            'category_id'=>'required',
            'tags'=>'required'
        ]);



        $featured=$request->featured;
        $featured_new_name= time().$featured->getClientOriginalName();
        //$featured->move('uploads/posts',$featured_new_name);

        //image upload on s3
        //$image_s3 = $request->file('featured');
        //$featured_new_name_s3= time().$image_s3->getClientOriginalName();
        $filePath = 'uploads/posts/'.$featured_new_name;
        $disk = Storage::disk('s3');
        $disk->put($filePath, file_get_contents($featured));
        $featured->move('uploads/posts',$featured_new_name);
        //

        //$post = new Post; // this is the first method to insert data
        //use second method which is faster
        $post=Post::create([
            'title'=>$request->title,
            'content'=>$request->content,
            'featured'=>'uploads/posts/'.$featured_new_name,
            'category_id'=>$request->category_id,
            'slug'=>str_slug($request->title),
            'user_id'=>Auth::id()
        ]);

        $post->tags()->attach($request->tags);

        Session::flash('success','Post Created Successfully.');
        return redirect()->route('posts');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post=Post::find($id);
        return view('admin.posts.edit')->with('post',$post)->with('categories',Category::all())->with('tags',Tag::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request,[
            'title'=>'required',
            //'featured'=>'required|image', it is not required because user dont want to change in that case
            'content'=>'required',
            'category_id'=>'required'
        ]);

        $post=Post::find($id);

        if($request->hasFile('featured'))
        {
            //checks if there is file in the request.
            $featured=$request->featured;
            $featured_new_name= time().$featured->getClientOriginalName();
            $featured->move('uploads/posts',$featured_new_name);
            $post->featured='uploads/posts/'.$featured_new_name;
        }
        $post->title=$request->title;
        $post->content=$request->content;
        $post->category_id=$request->category_id;

        $post->save();

        $post->tags()->sync($request->tags);

        Session::flash('success','Post Updated Successfully.');
        return redirect()->route('posts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post=Post::find($id);
        $post->delete();
        Session::flash('success','The Post Was Just Trashed.');
        return redirect()->back();
    }

    public function trashed()
    {
        $posts=Post::onlyTrashed()->get();  //onlyTrashed returns query builder instance

        return view('admin.posts.trashed')->with('posts',$posts);

    }

    public function kill($id)
    {
        $post=Post::withTrashed()->where('id',$id)->first(); //returns instance of the class
       
        $pathinfo = pathinfo($post->featured);
        $image_name=$pathinfo['filename'].'.'.$pathinfo['extension'];
        $path='uploads/posts/'.$image_name;
        if(Storage::disk('s3')->exists($path)) {

            Storage::disk('s3')->delete($path);
           
        }

        $post->forceDelete();
        Session::flash('success','Post Deleted Permanantely');
        return redirect()->back();
    }

    public function restore($id)
    {
        $post=Post::withTrashed()->where('id',$id)->first();
        $post->restore();
        Session::flash('success','Post Restored Successfully');
        return redirect()->route('posts');
    }
}
