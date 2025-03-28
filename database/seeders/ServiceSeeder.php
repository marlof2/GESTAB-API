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
                'establishment_id' => 1,
                'name' => 'Corte simples',
                'amount' => 25.00,
                'time' => '30:00'
            ],
            [
                'establishment_id' => 1,
                'name' => 'Corte e barba',
                'amount' => 30.00,
                'time' => '45:00'
            ],
            [
                'establishment_id' => 1,
                'name' => 'Barba',
                'amount' => 15.00,
                'time' => '30:00'
            ],
            [
                'establishment_id' => 1,
                'name' => 'Sobrancelha',
                'amount' => 15.00,
                'time' => '10:00'
            ],

        ];

        foreach ($data as $key => $value) {
            Services::firstOrCreate([
                'name' => $value['name'],
                'establishment_id' => $value['establishment_id'],
                'amount' => $value['amount'],
                'time' => $value['time'],
            ]);
        }
    }
}
