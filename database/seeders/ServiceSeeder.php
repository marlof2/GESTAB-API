<?php

namespace Database\Seeders;

use App\Models\Services;
use App\Models\TypeOfPerson;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
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
                'name' => 'Corte simples',
                'amount' => 10.00,
                'time' => 20
            ],
            [
                'name' => 'Corte e barba',
                'amount' => 15.00,
                'time' => 30
            ],
            [
                'name' => 'Barba',
                'amount' => 8.00,
                'time' => 30
            ],
            [
                'name' => 'Sobrancelha',
                'amount' => 20.00,
                'time' => 10
            ],

        ];

        foreach ($data as $key => $value) {
            Services::firstOrCreate([
                'name' => $value['name'],
            ]);
        }
    }
}
