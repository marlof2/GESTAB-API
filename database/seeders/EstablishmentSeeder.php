<?php

namespace Database\Seeders;

use App\Models\Establishment;
use App\Models\TypeOfUser;
use Illuminate\Database\Seeder;

class EstablishmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'type_of_person_id' => 1,
                'name' => 'Clube da Navalha',
                'cpf' => '25674675058',
                'cnpj' => '',
                'phone' => '71991717209'
            ],
            [
                'type_of_person_id' => 2,
                'name' => 'Tiquinho Styles',
                'cpf' => '',
                'cnpj' => '31397084000172',
                'phone' => '71991717209'
            ],
            [
                'type_of_person_id' => 1,
                'name' => 'Barbearia teste',
                'cpf' => '25674675051',
                'cnpj' => '',
                'phone' => '71991717209'
            ],

        ];

        foreach ($data as $key => $value) {
            Establishment::firstOrCreate([
                'type_of_person_id' => $value['type_of_person_id'],
                'name' => $value['name'],
                'cnpj' => $value['cnpj'],
                'cpf' => $value['cpf'],
                'phone' => $value['phone']
            ]);
        }
    }
}
