<?php

namespace Database\Seeders;

use App\Models\TypeOfPerson;
use Illuminate\Database\Seeder;

class TypeOfPersonSeeder extends Seeder
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
                'name' => 'Pessoa Física',
            ],
            [
                'name' => 'Pessoa Jurídica',
            ],

        ];

        foreach ($data as $key => $value) {
            TypeOfPerson::firstOrCreate([
                'name' => $value['name'],
            ]);
        }
    }
}
