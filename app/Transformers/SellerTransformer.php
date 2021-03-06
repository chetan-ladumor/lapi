<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'index'=>(int)$seller->id,
            'uname'=>(string)$seller->name,
            'uemail'=>(string)$seller->email,
            'isVerified'=>(int)$seller->verified,
            'creationDate'=>(string)$seller->created_at,
            'updateddate'=>(string)$seller->updated_at,
            'deletedDate'=>isset($seller->deleted_at) ? (string)$seller->deleted_at : null,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes= [
            'index'=>'id',
            'uname'=>'name',
            'uemail'=>'email',
            'isVerified'=>'verified',
            'creationDate'=>'created_at',
            'updateddate'=>'updated_at',
            'deletedDate'=>'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] :null;
    }
}
