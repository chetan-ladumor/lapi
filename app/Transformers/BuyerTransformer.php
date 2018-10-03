<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'index'=>(int)$buyer->id,
            'uname'=>(string)$buyer->name,
            'uemail'=>(string)$buyer->email,
            'isVerified'=>(int)$buyer->verified,
            'creationDate'=>(string)$buyer->created_at,
            'updateddate'=>(string)$buyer->updated_at,
            'deletedDate'=>isset($buyer->deleted_at) ? (string)$buyer->deleted_at : null,
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
