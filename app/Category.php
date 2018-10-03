<?php

namespace App;

use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;
	public $transformer=CategoryTransformer::class;
	protected $dates=['deleted_at'];
    protected $fillable= ['name','description']; //mass assign is possible in laravel by fillable.

    public function products()
    {
    	return $this->belongsToMany('App\Product');
    }
}
