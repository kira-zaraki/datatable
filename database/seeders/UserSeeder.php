<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\City;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'ADMIN', 'guard_name' => 'web']);

        $user = new User();
        $user->name = 'rabi kadda';
        $user->email = 'root@gmail.com';
        $user->city_id = City::first()->id;
        $user->password = Hash::make('123456');
        $user->save();

        $user->assignRole('ADMIN');

        $user = new User();
        $user->name = 'jack lorem';
        $user->email = 'jack@gmail.com';
        $user->city_id = City::first()->id;
        $user->password = Hash::make('123456');
        $user->save();

        $user = new User();
        $user->name = 'khalid ipsum';
        $user->email = 'khalid@gmail.com';
        $user->city_id = City::first()->id;
        $user->password = Hash::make('123456');
        $user->save();
    }
}
