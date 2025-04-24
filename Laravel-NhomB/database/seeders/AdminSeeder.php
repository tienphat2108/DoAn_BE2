<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(['email' => 'admin1@gmail.com'], [
            'name' => 'Admin One',
            'email' => 'admin1@gmail.com',
            'password' => Hash::make('12345Admin'),
            'is_admin' => true,
        ]);

        User::updateOrCreate(['email' => 'admin2@gmail.com'], [
            'name' => 'Admin Two',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('12345Admin'),
            'is_admin' => true,
        ]);
    }
}