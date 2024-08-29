<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
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
                'name' => 'Em adamento',
            ],
            [
                'name' => 'Aguardando atendimento',
            ],
            [
                'name' => 'Concluído',
            ],
            [
                'name' => 'Desistir',
            ],
        ];

        foreach ($data as $key => $value) {
            Status::firstOrCreate([
                'name' => $value['name'],
            ]);
        }
    }
}
