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
                'status_id' => 1,
                'valid' => true,
            ],
        ];

        foreach ($data as $key => $value) {
            Lista::firstOrCreate([
                'time' => $value['time'],
                'date' => $value['date'],
                'establishment_id' => $value['establishment_id'],
                'service_id' => $value['service_id'],
                'user_id' => $value['user_id'],
                'status_id' => $value['status_id'],
                'valid' => $value['valid'],
            ]);
        }
    }
}
