<?php

namespace Database\Seeders;

use App\Models\tblActor;
use App\Models\tblCommodity;
use App\Models\tblOperator;
use App\Models\tblOrder;
use App\Models\tblWarehouse;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // tblWarehouse::factory()
        // ->has(tblCommodity::factory()->count(5), 'commodities')
        // ->for(User::factory(), 'user')
        // ->has(tblOperator::factory()->count(1), 'operators')
        // ->count(16)
        // ->create();

        tblOrder::factory()
        ->count(50)
        ->create();

        // tblActor::factory()
        // ->count(6)
        // ->create();
    }
}
