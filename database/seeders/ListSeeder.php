<?php

namespace Database\Seeders;

use App\Models\Lista;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ListSeeder extends Seeder
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
                'time' => '08:00',
                'date' => Carbon::now()->format('Y/m/d'),
                'establishment_id' => 1,
                'service_id' => 1,
                'user_id' => 1,
                'professional_id' => 3,
                'status_id' => 1,
            ],
            [
                'time' => '09:00',
                'date' => Carbon::now()->format('Y/m/d'),
                'establishment_id' => 1,
                'service_id' => 1,
                'user_id' => 2,
                'professional_id' => 3,
                'status_id' => 1,
            ],
            [
                'time' => '10:00',
                'date' => Carbon::now()->format('Y/m/d'),
                'establishment_id' => 1,
                'service_id' => 1,
                'user_id' => 3,
                'professional_id' => 3,
                'status_id' => 1,
            ],
            [
                'time' => '11:00',
                'date' => Carbon::now()->format('Y/m/d'),
                'establishment_id' => 1,
                'service_id' => 1,
                'user_id' => 3,
                'professional_id' => 3,
                'status_id' => 1,
            ],
            [
                'time' => '12:00',
                'date' => Carbon::now()->format('Y/m/d'),
                'establishment_id' => 1,
                'service_id' => 1,
                'user_id' => 2,
                'professional_id' => 2,
                'status_id' => 1,
            ],
        ];

        foreach ($data as $key => $value) {
            Lista::firstOrCreate([
                'time' => $value['time'],
                'date' => $value['date'],
                'establishment_id' => $value['establishment_id'],
                'service_id' => $value['service_id'],
                'user_id' => $value['user_id'],
                'professional_id' => $value['professional_id'],
                'status_id' => $value['status_id'],
            ]);
        }
    }
}
