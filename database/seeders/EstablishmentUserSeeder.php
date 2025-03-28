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
                'created_by_functionality' => 'ME'
            ],
            [
                'establishment_id' => 1,
                'user_id' => 3,
                'created_by_functionality' => 'EP'
            ],
            [
                'establishment_id' => 1,
                'user_id' => 4,
                'created_by_functionality' => 'EP'
            ],
            // [
            //     'establishment_id' => 1,
            //     'user_id' => 4,
            //     'created_by_functionality' => 'EP'
            // ],
            // [
            //     'establishment_id' => 1,
            //     'user_id' => 4,
            //     'created_by_functionality' => 'EP'
            // ],
            // [
            //     'establishment_id' => 1,
            //     'user_id' => 5,
            //     'created_by_functionality' => 'EP'
            // ],
            // [
            //     'establishment_id' => 1,
            //     'user_id' => 6,
            //     'created_by_functionality' => 'EP'
            // ],
            // [
            //     'establishment_id' => 1,
            //     'user_id' => 7,
            //     'created_by_functionality' => 'EP'
            // ],
            // [
            //     'establishment_id' => 1,
            //     'user_id' => 8,
            //     'created_by_functionality' => 'EP'
            // ],
            // [
            //     'establishment_id' => 1,
            //     'user_id' => 9,
            //     'created_by_functionality' => 'EP'
            // ],
            // [
            //     'establishment_id' => 1,
            //     'user_id' => 10,
            //     'created_by_functionality' => 'EP'
            // ],
            // [
            //     'establishment_id' => 1,
            //     'user_id' => 11,
            //     'created_by_functionality' => 'EP'
            // ],
            // [
            //     'establishment_id' => 1,
            //     'user_id' => 12,
            //     'created_by_functionality' => 'EP'
            // ],
            // [
            //     'establishment_id' => 1,
            //     'user_id' => 13,
            //     'created_by_functionality' => 'EP'
            // ],
            // [
            //     'establishment_id' => 1,
            //     'user_id' => 14,
            //     'created_by_functionality' => 'EP'
            // ],
            // [
            //     'establishment_id' => 1,
            //     'user_id' => 15,
            //     'created_by_functionality' => 'EP'
            // ],
        ];

        foreach ($data as $key => $value) {
            EstablishmentUser::firstOrCreate([
                'establishment_id' => $value['establishment_id'],
                'user_id' => $value['user_id'],
                'created_by_functionality' => $value['created_by_functionality'],
            ]);
        }
    }
}
