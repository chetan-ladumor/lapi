<?php 
	
	namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

	//use Illuminate\Database\Eloquent\Collection;
	use Illuminate\Support\Collection;

	trait ApiResponser
	{
		private function successResponse($data,$code)
		{
			return response()->json($data,$code);
		}

		protected function errorResponse($message,$code)
		{
			return response()->json(['error'=>$message,'code'=>$code]);
		}

		protected function showAll(Collection $collection,$code = 200)
		{
			if( $collection->isEmpty() )
			{
				return $this->successResponse(['data'=>$collection],$code);
			}
			$transformer = $collection->first()->transformer;
			$collection=$this->filterData($collection,$transformer);
			$collection = $this->sortData($collection,$transformer);
			$collection =$this->paginate($collection);
			$collection = $this->transformData($collection,$transformer);
			$collection = $this->cacheResponse($collection);

			return $this->successResponse($collection,$code);
		}

		protected function showOne(Model $model , $code=200)
		{
			$transformer = $model->transformer;
			$model = $this->transformData($model,$transformer);
			return $this->successResponse($model,$code);
		}

		protected function showMessage($message,$code = 200)
		{
			return $this->successResponse(['data'=>$message],$code);
		}

		protected function filterData(Collection $collection,$transformer)
		{
			foreach( request()->query() as $key =>$value )
			{
				$attribute = $transformer::originalAttribute( $key );
				if(isset($attribute,$value))
				{
					$collection = $collection->where($attribute,$value);
				}
				
			}

			return $collection;
		}

		protected function sortData(Collection $collection,$transformer)
		{
			if( request()->has('sort_by') )
			{
				$attribute = $transformer::originalAttribute(request()->sort_by);
				$collection = $collection->sortBy->{$attribute}; // hack order message method
				// when attribute is not present,it avoids error and sorts the data randomly.
			}
			return $collection;
		}

		private function transformData($data,$transformer)
		{
			$transformation = fractal($data,new $transformer);
			return $transformation->toArray();
		}

		protected function paginate($collection)
		{
			$rules=[
					'per_page'=>'integer|min:2|max:50',
			];

			Validator::validate( request() ->all(), $rules);

			//we are not in controller, we are in trait and we dont know where are going to use this trait so we cant validate like previously we did.
			//independent of controller structure



			$page= LengthAwarePaginator::resolveCurrentPage();

			$perPage =15;
			if( request()->has('per_page') )
			{
				$perPage=(int)request()->per_page;
			}

			$results = $collection->slice( ($page -1 )*$perPage ,$perPage )->values();

			$paginated =new LengthAwarePaginator(
				$results,$collection->count(),$perPage,$page,
				[
					'path'=>LengthAwarePaginator::resolveCurrentPath(), //next or previous page depending on current page
				]
			);

			$paginated->appends( request()->all() );  // if we dont use this the the other parameters are ignot\red like sotr_by or filterdata parameters are ignored.
			//use all the parameters of request
			return $paginated;
		}

		protected function cacheResponse($data)
		{
			$url = request()->url();
			$queryParams = request()->query();
			ksort($queryParams);
			$queryString = http_build_query($queryParams);
			$fullUrl = "{$url}?{$queryString}";

			return Cache::remember($fullUrl,30/60,function() use ($data){
				return $data;
			});
		}

	}
