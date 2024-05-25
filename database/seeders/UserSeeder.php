<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = [
            [
                'name' => 'Marlo',
                'profile_id' => 1,
                'phone' => '71991717209',
                'cpf' => '03296244581',
                'email' => 'marlosilva.f2@gmail.com',
                'password' => Hash::make('123'),
            ],
            [
                'name' => 'Juliana',
                'profile_id' => 1,
                'phone' => '71991717209',
                'cpf' => '85873615543',
                'email' => 'jumatosk@gmail.com',
                'password' => Hash::make('123'),
            ]
        ];


        foreach ($users as $key => $value) {
            User::firstOrCreate([
                'name' => $value['name'],
                'profile_id' => $value['profile_id'],
                'phone' => $value['phone'],
                'cpf' => $value['cpf'],
                'email' => $value['email'],
                'password' => $value['password'],
            ]);
        }
    }
}
