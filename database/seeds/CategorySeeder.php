<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Enums\genderType;
use App\Enums\Status;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('service_categories')->insert([
            'parent_id'=>0,
            'title' =>'لیزر',
            'status' => Status::Active,
        ]);

        DB::table('goods_sub_categories')->insert([
            'main_id' => 1,
            'title' =>'تیوب',
            'status' => Status::Active,
        ]);

        DB::table('goods_main_categories')->insert([
            'title' =>'لیزر',
            'status' => Status::Active,
        ]);

        DB::table('goods_sub_categories')->insert([
            'main_id' => 1,
            'title' =>'تیوب',
            'status' => Status::Active,
        ]);
    }
}
