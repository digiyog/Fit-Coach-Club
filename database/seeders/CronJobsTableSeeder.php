<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CronJobs;

class CronJobsTableSeeder extends Seeder
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
                'name' => 'booking-reminder',
            ],
            [
                'name' => 'booking-auto-complete-by-styla',
            ],
            [
                'name' => 'points-expire',
            ],
            [
                'name' => 'mlm-monthly-recharge',
            ],
            [
                'name' => 'acceptance-rating-reset',
            ],
            [
                'name' => 'stylist-book-no-response',
            ],
        ];

        foreach($data as $key => $value){
            CronJobs::firstOrCreate([
                'name' => $value['name'],
            ]);
        }
    }
}
