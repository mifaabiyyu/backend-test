<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $admin = User::create([
            'name'      => 'admin',
            'email'     => 'admin@admin.com',
            'password'  => bcrypt('password'),
            'phone'     => '12345',
        ]);

        $admin->assignRole('admin');

        $manager = User::create([
            'name'      => 'manager',
            'email'     => 'manager@manager.com',
            'password'  => bcrypt('password'),
            'phone'     => '111',
        ]);

        $manager->assignRole('manager');

        $user = User::create([
            'name'      => 'user',
            'email'     => 'user@user.com',
            'password'  => bcrypt('password'),
            'phone'     => '123',
        ]);

        $user->assignRole('user');
    }
}
