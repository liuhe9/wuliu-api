<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Manager;

class ManagerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Manager::create([
            'name' => env('INIT_MANAGER_NAME', 'test'),
            'mobile' => env('INIT_MANAGER_MOBILE', '13122956617'),
            'password' => Hash::make(env('INIT_MANAGER_PASSWORD', '13122956617')),
        ]);
    }
}
