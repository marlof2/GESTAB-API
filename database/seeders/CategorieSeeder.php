<?php

namespace Database\Seeders;

use App\Models\Categories;
use App\Models\TypeOfPerson;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
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
                'name' => 'Relatar um erro',
            ],
            [
                'name' => 'Melhorias',
            ],
            [
                'name' => 'Feedbacks',
            ],

        ];

        foreach ($data as $key => $value) {
            Categories::firstOrCreate([
                'name' => $value['name'],
            ]);
        }
    }
}
