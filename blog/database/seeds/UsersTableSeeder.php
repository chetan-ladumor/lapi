<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=App\User::create([
        	'name'=>'chetan',
        	'email'=>'ladumorchetan@yahoo.com',
        	'password'=>bcrypt('Chetan'),
            'admin'=>1
        ]);

        App\Profile::create([
            'user_id'=>$user->id,
            'avatar'=>'uploads/avatars/abc.png',
            'about'=>'Lorem ipsum some dummy text',
            'facebook'=>'https://facebook.com',
            'youtube'=>'https://youtube.com'
        ]);
    }
}
