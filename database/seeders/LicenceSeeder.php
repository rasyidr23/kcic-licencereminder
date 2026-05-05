<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Licence;
use Carbon\Carbon;

class LicenceSeeder extends Seeder
{
    public function run(): void
    {
        // 4 Data dengan tanggal spesifik untuk testing email
        $targetDates = [
            Carbon::today()->addMonth()->format('Y-m-d'),
            Carbon::today()->addWeeks(2)->format('Y-m-d'),
            Carbon::today()->addWeek()->format('Y-m-d'),
            Carbon::today()->format('Y-m-d'), // Expired hari ini
        ];

        $remChoices = ['3_months', '2_months', '1_month', '2_weeks', '0_days'];

        foreach ($targetDates as $date) {
            $licence = Licence::create([
                'name' => fake()->company() . ' (Test Data)',
                'vendor_name' => 'PT Vendor ' . fake()->company(),
                'period_start' => Carbon::parse($date)->subYear()->format('Y-m-d'),
                'period_end' => $date,
                'licence_type' => 'Subscription',
                'reminder_days' => array_unique(array_merge(fake()->randomElements(['3_months', '2_months', '1_month', '2_weeks'], fake()->numberBetween(0, 2)), ['0_days'])),
                'description' => fake()->sentence(),
            ]);

            $licence->logs()->create([
                'vendor_name' => $licence->vendor_name,
                'period_start' => $licence->period_start,
                'period_end' => $licence->period_end,
                'created_at' => now()->subMonths(rand(1, 10)),
            ]);
        }

        // 6 Data random lainnya
        for ($i = 0; $i < 6; $i++) {
            $isPerpetual = fake()->boolean(20); // 20% chance of perpetual
            
            $startDate = fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d');
            $endDate = $isPerpetual ? null : fake()->dateTimeBetween('+2 months', '+2 years')->format('Y-m-d');

            $licence = Licence::create([
                'name' => fake()->company() . ' Software Licence',
                'vendor_name' => 'PT Vendor ' . fake()->company(),
                'period_start' => $startDate,
                'period_end' => $endDate,
                'licence_type' => $isPerpetual ? 'Perpetual' : 'Subscription',
                'reminder_days' => $isPerpetual ? null : array_unique(array_merge(fake()->randomElements(['3_months', '2_months', '1_month', '2_weeks'], fake()->numberBetween(0, 3)), ['0_days'])),
                'description' => fake()->sentence(),
            ]);

            $licence->logs()->create([
                'vendor_name' => $licence->vendor_name,
                'period_start' => $licence->period_start,
                'period_end' => $licence->period_end,
                'created_at' => now()->subMonths(rand(1, 10)),
            ]);
        }
    }
}
