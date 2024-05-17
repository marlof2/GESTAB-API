<?php

namespace Database\Seeders;

use App\Models\EstablishmentServices;
use App\Services\EstablishmentService;
use Illuminate\Database\Seeder;

class EstablishmentServiceSeeder extends Seeder
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
                'service_id' => 1,
            ],
            [
                'establishment_id' => 1,
                'service_id' => 2,
            ],
            [
                'establishment_id' => 1,
                'service_id' => 3,
            ],
            [
                'establishment_id' => 1,
                'service_id' => 4,
            ],
        ];

        foreach ($data as $key => $value) {
            EstablishmentServices::firstOrCreate([
                'establishment_id' => $value['establishment_id'],
                'service_id' => $value['service_id'],
            ]);
        }
    }
}
