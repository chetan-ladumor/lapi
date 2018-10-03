<?php

namespace App;

use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $dates=['deleted_at'];
    
    public $transformer=ProductTransformer::class;
	const AVAILABLE_PRODUCT = 'available';
	const UNAVAILABLE_PRODUCT = 'unavailable';//this is for status of product 
    protected $fillable=[
    	'name',
    	'description',
    	'quantity',
    	'status',
    	'image',
    	'seller_id'
    ];

    public function isAvailable()
    {
    	return $this->status = Product::AVAILABLE_PRODUCT; // only return if vailable 
    	//it will return false if unavailable
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function seller()
    {
        return $this->belongsTo('App\Seller');
    }
}
