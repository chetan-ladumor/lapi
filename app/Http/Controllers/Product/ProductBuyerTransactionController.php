<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Product $product, User $buyer)
    {
        $this->validate($request,[
            'quantity'=>'required|integer|min:1',
        ]);

        if( $buyer->id == $product->seller_id )
        {
            return $this->errorResponse('The buyer must be different form the seller.',409);
        }

        if(!$buyer->isverified())
        {
            return $this->errorResponse('Buyer must be verified user.',409);
        }

        if(!$product->seller->isverified())
        {
            return $this->errorResponse('Seller must be verified user.',409);
        }

        if(!$product->isAvailable())
        {
            return $this->errorResponse('This product is not available.',409);
        }

        if($product->quantity < $request->quantity)
        {
            return $this->errorResponse('The requested quantity of product is not availablewith seller.',409);
        }

        //create transaction

        return DB::transaction(function() use ($request,$product,$buyer){
            $product->quantity -=$request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity'=>$request->quantity,
                'buyer_id'=>$buyer->id,
                'product_id'=>$product->id,
            ]);
            return $this->showOne($transaction,201);
        } );

    }

   
}
