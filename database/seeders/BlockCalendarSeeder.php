<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BlockCalendarSeeder extends Seeder
{
    private const PERIODS = [
        'allday' => [
            'start' => null,
            'end' => null
        ],
        'morning' => [
            'start' => '06:00:00',
            'end' => '12:00:00'
        ],
        'afternoon' => [
            'start' => '12:00:00',
            'end' => '18:00:00'
        ],
        'night' => [
            'start' => '18:00:00',
            'end' => '23:59:59'
        ]
    ];

    public function run(): void
    {
        $establishments = DB::table('establishment')->pluck('id');
        $users = DB::table('users')->pluck('id');

        // Create entries for each specific period
        foreach (self::PERIODS as $period => $times) {
            // Create 5 entries for each period
            for ($i = 0; $i < 5; $i++) {
                $date = Carbon::now()->addDays(rand(1, 30));

                DB::table('block_calendar')->insert([
                    'establishment_id' => $establishments->random(),
                    'user_id' => $users->random(),
                    'period' => $period,
                    'date' => $date->format('Y-m-d'),
                    'time_start' => $times['start'],
                    'time_end' => $times['end'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Create personalized period entries
        for ($i = 0; $i < 1; $i++) {
            $date = Carbon::now()->addDays(rand(1, Carbon::now()->daysInMonth - Carbon::now()->day));
            $timeStart = Carbon::createFromTime(8, 0, 0)->format('H:i:s');
            $timeEnd = Carbon::createFromTime(10, 0, 0)->format('H:i:s');

            DB::table('block_calendar')->insert([
                'establishment_id' => $establishments->random(),
                'user_id' => $users->random(),
                'period' => 'personalized',
                'date' => $date->format('Y-m-d'),
                'time_start' => $timeStart,
                'time_end' => $timeEnd,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
