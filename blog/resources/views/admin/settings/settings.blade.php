@extends('layouts.app')

@section('content')

	@if(count($errors) > 0)
		<ul class="list-group">
			@foreach(@errors as $error)
				<li class="list-group-item text-danger">
					{{$error}}
				</li>
			@endforeach
		</ul>
	@endif

	<div class="panel panel-default">
		<div class="panel-heading">
			Edit Blog Settings
		</div>
		<div class="panel-body">
			<form action="{{route('settings.update')}}" method="post" enctype="multipart/form-data">
				{{csrf_field()}}
				
				<div class="form-group">
					<label for="site_name">Site Name</label>
					<input type="text" name="site_name" value="{{$settings->site_name}}" class="form-control"> 
				</div>
				<div class="form-group">
					<label for="address">Address</label>
					<input type="text" name="address" value="{{$settings->address}}" class="form-control">
				</div>
				<div class="form-group">
					<label for="contact_number">Site Contact NUmber</label>
					<input type="text" name="contact_number" value="{{$settings->contact_number}}" class="form-control">
				</div>
				<div class="form-group">
					<label for="contact_email">Site Email</label>
					<input type="text" name="contact_email" value="{{$settings->contact_email}}" class="form-control">
				</div>
				<div class="form-group">
					<div class="text-center">
						<button class="btn btn-success">Update Settings</button>
					</div>
				</div> 
			</form>
		</div>
	</div>

@stop