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
            [
                'establishment_id' => 1,
                'user_id' => 3,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 4,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 5,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 6,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 7,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 8,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 9,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 10,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 11,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 12,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 13,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 14,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 15,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 16,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 17,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 18,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 19,
            ],
            [
                'establishment_id' => 1,
                'user_id' => 20,
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
