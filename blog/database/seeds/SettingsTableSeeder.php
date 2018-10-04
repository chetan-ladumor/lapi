<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Setting::create([
        	'site_name'=>"Laravel's Blog",
        	'address'=>'surat',
        	'contact_number'=>'8469535440',
        	'contact_email'=>'abc@gmail.com'
        ]);
    }
}
