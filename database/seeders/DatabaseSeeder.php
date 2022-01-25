<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LandingPage;
use App\Models\Account;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(1)->create([
            'email' => 'isaacsouza17@gmail.com'
        ]);

        Account::factory(1)->for(User::first())->create();

        LandingPage::factory(5)->for(Account::first())->create();
    }
}
