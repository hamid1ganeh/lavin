<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Enums\genderType;
use App\Enums\Status;


class OtherSeeder extends Seeder
{
    public function run()
    {
        DB::table('warehouses')->insert([
            'name' => 'انبار لیزر',
            'status' => Status::Active,
        ]);

    }
}
