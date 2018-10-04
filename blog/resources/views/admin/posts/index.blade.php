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
					<!-- <th>
						Content
					</th> -->
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
					@foreach($posts as $post)
						<tr>
							<td>
								<img src="{{$post->featured}}" width="90px" height="90px">
							</td>
							<td>
								{{$post->title}}
							</td>
							<!-- <td>
								{{$post->content}}
							</td> -->
							<td>
								{{$post->category_id}}
							</td>
							<td>
								<a href="{{ route('posts.edit',[ 'id' => $post->id ]) }}" class="btn btn-xs btn-info">
								Edit	
								</a>
							</td>
							<td>
								<a href="{{ route('posts.delete',[ 'id' => $post->id ]) }}" class="btn btn-xs btn-danger">
								Trash
								</a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

@stop