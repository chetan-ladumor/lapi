<?php

namespace App\Http\Controllers;

use Session;
use App\category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category=Category::all();
        if($category->count() == 0)
        {
            Session::flash('info','You must have to add category first to add new post.');
            return redirect()->back();
        }
        return view('admin.categories.index')->with('categories',$category);//use category model to get all categories
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
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
            'name'=>'required'
        ]);

        $category=new Category;
        $category->name=$request->name;
        $category->save();
        Session::flash('success','You successfully created a new category.');
        return redirect()->route('categories');
        //dd($request->all());
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
        $category=Category::find($id);
        return view('admin.categories.edit')->with('category',$category);
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
        $category=Category::find($id);
        $category->name=$request->name;
        $category->save();
        Session::flash('success','You successfully updated a category.');
        return redirect()->route('categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category=Category::find($id);
        //delete the post associated with the category when deletig the category.
        foreach($category->posts as $post)
        {
            $post->delete(); //trash
            //$post->forceDelete(); // permanantly delete
        }

        $category->delete();
        Session::flash('success','You successfully deleted a category.');
        return redirect()->route('categories');
    }
}
