@extends('layouts.app')

@section('content')

	<div class="panel panel-default">
		<div class="panel-body">
			<table class="table table-hover">
				<thead>
					<th>
						Image
					</th>
					<th>
						Title
					</th>
					<th>
						Content
					</th>
					<th>
						Category
					</th>
					<th>
						Edit
					</th>
					<th>
						Delete
					</th>
				</thead>
				<tbody>
					@if($posts->count() > 0)
						@foreach($posts as $post)
							<tr>
								<td>
									<img src="{{$post->featured}}" width="90px" height="90px">
								</td>
								<td>
									{{$post->title}}
								</td>
								<td>
									{{$post->content}}
								</td>
								<td>
									{{$post->category_id}}
								</td>
								<td>
									<a href="{{ route('posts.kill',[ 'id' => $post->id ]) }}" class="btn btn-xs btn-danger">
									Delete	
									</a>
								</td>
								<td>
									<a href="{{ route('posts.restore',[ 'id' => $post->id ]) }}" class="btn btn-xs btn-success">
									Restore
									</a>
								</td>
							</tr>
						@endforeach
					@else
						<tr>
							<th colspan="5" class="text-center">NO trashed posts.</th>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>

@stop