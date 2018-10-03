<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use App\User;
use Illuminate\Http\Request;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        return $this->showAll($seller->products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $seller) // User becausewhen seller publishes his first product at that time he is not seller he is user in the system.
    {
        $this->validate($request,[
            'name'=>'required',
            'description'=>'required',
            'quantity'=>'required|integer|min:1',
            'image'=>'required|image',

        ]);
        $data= $request->all();
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['image'] = 'kishan.jpg';
        $data['seller_id'] = $seller->id;
        $product = Product::create($data);
        return $this->showOne($product);
    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller,Product $product)
    {
        //if updater is not the owner of project then he cant update the product
        // cant change the product status to available untill thhis product has atleat one category.
        $this->validate($request,[

            'quantity'=>'integer|min:1',
            'status'=>'in:'.Product::AVAILABLE_PRODUCT.','.Product::UNAVAILABLE_PRODUCT,
            //'image'=>'image',

        ]);

        $this->checkSeller($seller,$product);

        $product->fill($request->all());

        if($request->has('status')){
            $product->status = $request->status;
            if( $product->isAvailable()  &&  $product->categories()->count() == 0 ){
                return $this->errorResponse('An active product must have at least one category',409);
            }
        }
        if($product->isClean()){
            return $this->errorResponse('You need to specify different value to update',422);
        }

        $product->save();
        return $this->showOne($product);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller,Product $product)
    {
        $this->checkSeller($seller,$product);
        $product->delete();
        return $this->showOne($product);

    }

    protected function checkSeller($seller,$product)
    {
        if($seller->id != $product->seller_id)
        {
            throw new HttpException(422,'The Specified seller is not the actual seller of the product.');
        }
    }

}
