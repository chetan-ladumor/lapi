<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'index'=>(int)$user->id,
            'uname'=>(string)$user->name,
            'uemail'=>(string)$user->email,
            'isVerified'=>(int)$user->verified,
            'isAdmin'=>($user->admin === 'true'),
            'creationDate'=>(string)$user->created_at,
            'updateddate'=>(string)$user->updated_at,
            'deletedDate'=>isset($user->deleted_at) ? (string)$user->deleted_at : null,
        ];
    }

    //return mapping between transform attibute and original attribute

    public static function originalAttribute($index)
    {
        $attributes= [
            'index'=>'id',
            'uname'=>'name',
            'uemail'=>'email',
            'isVerified'=>'verified',
            'isAdmin'=>'admin',
            'creationDate'=>'created_at',
            'updateddate'=>'updated_at',
            'deletedDate'=>'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] :null;
    }

}
