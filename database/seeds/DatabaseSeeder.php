<?php

use App\Category;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Transaction::flushEventListeners(); // disble model events

        $userQuantity=1000;
        $categoryQuantity=30;
        $productQuantity=1000;
        $transactionQuantity=1000;

        factory(User::class,$userQuantity)->create();
        factory(Category::class,$userQuantity)->create();
           //product and category relationship withn category_product table

           factory(Product::class,$productQuantity)->create()->each(function($product){

           	$categories = Category::all()->random(mt_rand(1,5))
           								 ->pluck('id');
        		//attach method receives an arrray of categories   								 
           	$product->categories()->attach($categories);							 

           });
        factory(Transaction::class,$userQuantity)->create();



    }
}
