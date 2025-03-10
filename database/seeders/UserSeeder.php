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
                'email' => 'marlosilva.f3@gmail.com',
                'password' => Hash::make('123'),
                'type_schedule' => null,
            ],
            [
                'name' => 'Juliana',
                'profile_id' => 2,
                'phone' => '71991717209',
                'cpf' => '03296244582',
                'email' => 'jumatosk@gmail.com',
                'password' => Hash::make('123'),
                'type_schedule' => null,
            ],
            ['name' => 'Ricardo', 'profile_id' => 3, 'phone' => '71991717220', 'cpf' => '03296244582', 'email' => 'ricardo@example.com', 'password' => Hash::make('123'), 'type_schedule' => 'HM'],
            ['name' => 'Patrícia', 'profile_id' => 3, 'phone' => '71991717221', 'cpf' => '03296244583', 'email' => 'patricia@example.com', 'password' => Hash::make('123'), 'type_schedule' => 'OC'],
            // ['name' => 'Mateus', 'profile_id' => 2, 'phone' => '71991717222', 'cpf' => '74562319845', 'email' => 'mateus@example.com', 'password' => Hash::make('123'), 'type_schedule' => null],
            // ['name' => 'Sofia', 'profile_id' => 2, 'phone' => '71991717223', 'cpf' => '85963214789', 'email' => 'sofia@example.com', 'password' => Hash::make('123'), 'type_schedule' => null],
            // ['name' => 'Thiago', 'profile_id' => 3, 'phone' => '71991717224', 'cpf' => '45896321478', 'email' => 'thiago@example.com', 'password' => Hash::make('123'), 'type_schedule' => 'OC'],
            // ['name' => 'Isabela', 'profile_id' => 3, 'phone' => '71991717225', 'cpf' => '78521496358', 'email' => 'isabela@example.com', 'password' => Hash::make('123'), 'type_schedule' => 'HM'],
            // ['name' => 'Felipe', 'profile_id' => 3, 'phone' => '71991717226', 'cpf' => '89652314785', 'email' => 'felipe@example.com', 'password' => Hash::make('123'), 'type_schedule' => 'HM'],
            // ['name' => 'Camila', 'profile_id' => 2, 'phone' => '71991717227', 'cpf' => '78945612378', 'email' => 'camila@example.com', 'password' => Hash::make('123'), 'type_schedule' => null],
            // ['name' => 'Daniel', 'profile_id' => 3, 'phone' => '71991717228', 'cpf' => '69854712365', 'email' => 'daniel@example.com', 'password' => Hash::make('123'), 'type_schedule' => null],
            // ['name' => 'Larissa', 'profile_id' => 2, 'phone' => '71991717229', 'cpf' => '78521436985', 'email' => 'larissa@example.com', 'password' => Hash::make('123'), 'type_schedule' => null],
            // ['name' => 'Gustavo', 'profile_id' => 3, 'phone' => '71991717230', 'cpf' => '56987412385', 'email' => 'gustavo@example.com', 'password' => Hash::make('123'), 'type_schedule' => null],
            // ['name' => 'Renata', 'profile_id' => 2, 'phone' => '71991717231', 'cpf' => '78563214789', 'email' => 'renata@example.com', 'password' => Hash::make('123'), 'type_schedule' => null],
            // ['name' => 'Marcos', 'profile_id' => 3, 'phone' => '71991717232', 'cpf' => '68954712365', 'email' => 'marcos@example.com', 'password' => Hash::make('123'), 'type_schedule' => null],
            // ['name' => 'Elisa', 'profile_id' => 2, 'phone' => '71991717233', 'cpf' => '78536921478', 'email' => 'elisa@example.com', 'password' => Hash::make('123')],
            // ['name' => 'Rafael', 'profile_id' => 3, 'phone' => '71991717234', 'cpf' => '89632514789', 'email' => 'rafael@example.com', 'password' => Hash::make('123')],
            // ['name' => 'Manuela', 'profile_id' => 2, 'phone' => '71991717235', 'cpf' => '78521496352', 'email' => 'manuela@example.com', 'password' => Hash::make('123')],
            // ['name' => 'Alex', 'profile_id' => 3, 'phone' => '71991717236', 'cpf' => '69874123658', 'email' => 'alex@example.com', 'password' => Hash::make('123')],
            // ['name' => 'Mariana', 'profile_id' => 2, 'phone' => '71991717237', 'cpf' => '78541236985', 'email' => 'mariana@example.com', 'password' => Hash::make('123')],
        ];


        foreach ($users as $key => $value) {
            User::firstOrCreate([
                'name' => $value['name'],
                'profile_id' => $value['profile_id'],
                'phone' => $value['phone'],
                'cpf' => $value['cpf'],
                'email' => $value['email'],
                'password' => $value['password'],
                'type_schedule' => $value['type_schedule'],
            ]);
        }
    }
}
