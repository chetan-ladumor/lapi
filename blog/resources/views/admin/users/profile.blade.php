@extends('layouts.app')

@section('content')

	@if(count($errors) > 0)
		<ul class="list-group">
			@foreach($errors->all() as $error)
				<li class="list-group-item text-danger">
					{{$error}}
				</li>
			@endforeach
		</ul>
	@endif

	<div class="panel panel-default">
		<div class="panel-heading">
			Edit Your Profile
		</div>
		<div class="panel-body">
			<form action="{{route('user.profile.update')}}" method="post" enctype="multipart/form-data">
				{{csrf_field()}}
				<div class="form-group">
					<label for="name">Name</label>
					<input type="text" name="name" value="{{$user->name}}" class="form-control"> 
				</div>
				
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" name="email" value="{{$user->email}}" class="form-control">
				</div>
				
				<div class="form-group">
					<label for="password">Password</label>
					<input type="text" name="password"  class="form-control"> 
				</div>

				<div class="form-group">
					<label for="avatar">Upload New Avatar</label>
					<input type="file" name="avatar" class="form-control"> 
				</div>

				<div class="form-group">
					<label for="facebook">Facebook Profile</label>
					<input type="text" name="facebook" value="{{$user->profile->facebook}}" class="form-control"> 
				</div>

				<div class="form-group">
					<label for="youtube">Youtube Profile</label>
					<input type="text" name="youtube" value="{{$user->profile->youtube}}" class="form-control"> 
				</div>

				<div class="form-group">
					<label for="about">About You</label>
					<textarea name="about" id="about" cols="30" rows="10" class="form-control">{{$user->profile->about}}</textarea> 
				</div>
				
				<div class="form-group">
					<div class="text-center">
						<button class="btn btn-success">Edit User</button>
					</div>
				</div> 
			</form>
		</div>
	</div>

@stop