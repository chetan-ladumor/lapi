<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users=User::all();
        //return response()->json(['data'=>$users],200);
        return $this->showAll($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] =User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;
        $user = User::create($data);
        //return response()->json(['data'=>$user],200);
        return $this->showOne($user,201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //$user = User::findOrFail($id);
        //return response()->json(['data'=>$user]);
        return $this->showOne($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //$user = User::findOrFail($id);
        $this->validate($request,[
            'email'=>'email|unique:users,email,'.$user->id,
            'password'=>'min:6|confirmed',
            'admin'=>'in:'.User::ADMIN_USER . ',' .User::REGULAR_USER,
        ]);

        if($request->has('name')){
            $user->name =$request->name;
        }
        if( $request->has('email') && $user->email != $request->email ){
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email=$request->email;
        }
        if($request->has('password')){
            $user->password=bcrypt($request->password);
        }
        if($request->has('admin')){  // if verified user them and only then he can update this field

            if(!$user->isverified()){
                //return response()->json(['error'=>'Only verified users can modify admin field.','code'=>409],409);
                return $this->errorResponse('Only verified users can modify admin field.',409);
            }
            $user->admin = $request->admin;
        }
        if(!$user->isDirty()){
            //return response()->json(['error'=>'You need to specify a different value to update','code'=>422],422);
            return $this->errorResponse('You need to specify a different value to update',409);
        }

        $user->save();
        //return response()->json(['data'=>$user],200);
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //$user= User::findOrFail($id);
        $user->delete();
        //return response()->json(['data'=>$user],200);
        return $this->showOne($user);
    }

    public function verify($token)
    {
        $user = User::where('verification_token',$token)->firstOrFail();
        $user->verified =User::VERIFIED_USER;
        $user->verification_token = null;
        $user->save();

        return $this->showMessage('The Account has been Verified Successfully.');
    }

    public function resend(User $user)
    {
        if($user->isverified())
        {
            return $this->errorResponse('Thi user has been verified already.',409);
        }
        Mail::to($user)->send(new UserCreated($user));
        return $this->showMessage('The mail has been send to confirm the email');
    }
}
