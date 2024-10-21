<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\Status;


class DiffrentCategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('service_categories')->insert([
            'parent_id'=>0,
            'title' =>'لیزر',
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
