<?php

namespace Database\Seeders;

use App\Models\EstablishmentUser;
use Illuminate\Database\Seeder;

class EstablishmentUserSeeder extends Seeder
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
                'user_id' => 1,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 2,
            ],
        ];

        foreach ($data as $key => $value) {
            EstablishmentUser::firstOrCreate([
                'establishment_id' => $value['establishment_id'],
                'user_id' => $value['user_id'],
            ]);
        }
    }
}
