<?php

namespace Database\Seeders;

use App\Models\TypeOfUser;
use Illuminate\Database\Seeder;

class TypeOfUserSeeder extends Seeder
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
                'name' => 'Admin',
            ],
            [
                'name' => 'Profissional',
            ],
            [
                'name' => 'Cliente',
            ],

        ];

        foreach ($data as $key => $value) {
            TypeOfUser::firstOrCreate([
                'name' => $value['name'],
            ]);
        }
    }
}
