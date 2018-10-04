@extends('layouts.app')

@section('content')

	<div class="panel panel-default">
		<div class="panel-heading">
			Users
		</div>
		<div class="panel-body">
			<table class="table table-hover">
				<thead>
					<th>
						Image
					</th>
					<th>
						Name
					</th>
					<th>
						Permission
					</th>
					<th>
						Delete
					</th>
				</thead>
				<tbody>
					@if($users->count() > 0)
						@foreach($users as $user)
							<tr>
								<td>
									<img src="{{ asset($user->profile->avatar) }}" width="90px" height="90px" style="border-radius: 50%;">
								</td>
								<td>
									{{$user->name}}
								</td>
								<td>
									@if($user->admin)
										<a class="btn btn-xs btn-danger" href="{{route('user.not.admin',['id'=>$user->id])}}">
											Remove Permission
										</a>
									@else
										<a class="btn btn-xs btn-success" href="{{route('user.admin',['id'=>$user->id])}}">
											Make Admin
										</a>
									@endif
								</td>
								<td>
									@if(Auth::id() !== $user->id)
										<a href="{{  route('user.delete',['id'=>$user->id]) }}" class="btn btn-danger">Delete</a>
									@endif
									
								</td>
								
							</tr>
						@endforeach
					@else
						<tr>
							<th colspan="5" class="text-center">NO Users Availbale at the moment.</th>
						</tr>
					@endif		
				</tbody>
			</table>
		</div>
	</div>

@stop