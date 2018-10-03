<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'index'=>(int)$category->id,
            'title'=>(string)$category->name,
            'details'=>(string)$category->description,
            'creationDate'=>(string)$category->created_at,
            'updateddate'=>(string)$category->updated_at,
            'deletedDate'=>isset($category->deleted_at) ? (string)$category->deleted_at : null,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes= [
            'index'=>'id',
            'title'=>'name',
            'details'=>'description',
            'creationDate'=>'created_at',
            'updateddate'=>'updated_at',
            'deletedDate'=>'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] :null;
    }
}
