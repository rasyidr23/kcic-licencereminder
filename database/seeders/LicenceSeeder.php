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
        // 10 Data yang diatur agar memicu email notifikasi HARI INI
        // (2 data untuk masing-masing: Hari H, 3 Bulan, 2 Bulan, 1 Bulan, 2 Minggu)
        $reminderTriggers = [
            ['interval' => 0, 'unit' => 'days', 'reminder' => '0_days'],
            ['interval' => 3, 'unit' => 'months', 'reminder' => '3_months'],
            ['interval' => 2, 'unit' => 'months', 'reminder' => '2_months'],
            ['interval' => 1, 'unit' => 'month', 'reminder' => '1_month'],
            ['interval' => 2, 'unit' => 'weeks', 'reminder' => '2_weeks'],
        ];

        $createdCount = 0;

        foreach ($reminderTriggers as $trigger) {
            for ($i = 0; $i < 2; $i++) {
                if ($trigger['unit'] == 'days') {
                    $targetDate = Carbon::today()->addDays($trigger['interval'])->format('Y-m-d');
                } elseif ($trigger['unit'] == 'weeks') {
                    $targetDate = Carbon::today()->addWeeks($trigger['interval'])->format('Y-m-d');
                } else {
                    $targetDate = Carbon::today()->addMonths($trigger['interval'])->format('Y-m-d');
                }

                $licence = Licence::create([
                    'name' => fake()->company() . ' (Notif Test)',
                    'vendor_name' => 'PT Vendor ' . fake()->company(),
                    'period_start' => Carbon::parse($targetDate)->subYear()->format('Y-m-d'),
                    'period_end' => $targetDate,
                    'licence_type' => 'Subscription',
                    // Kita set reminder_days yang sesuai agar memicu email
                    'reminder_days' => ['0_days', $trigger['reminder']],
                    'description' => 'Test data for ' . $trigger['reminder'] . ' notification.',
                ]);

                $licence->logs()->create([
                    'vendor_name' => $licence->vendor_name,
                    'period_start' => $licence->period_start,
                    'period_end' => $licence->period_end,
                    'created_at' => now()->subMonths(rand(1, 10)),
                ]);

                $createdCount++;
            }
        }

        // Kita butuh total 50 record, jadi sisa 40 record lagi
        // Rasio Subscription : Perpetual = 5 : 1
        // Artinya dari 50, sekitar 42 Subscription dan 8 Perpetual.
        // Karena 10 di atas sudah Subscription, sisa 40 record akan dibagi: 32 Subscription dan 8 Perpetual.
        
        $types = array_merge(array_fill(0, 32, 'Subscription'), array_fill(0, 8, 'Perpetual'));
        shuffle($types); // Acak urutannya

        foreach ($types as $type) {
            $startDate = fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d');
            
            if ($type == 'Perpetual') {
                $endDate = null;
                $reminderDays = null;
            } else {
                // Tanggal random, usahakan tidak pas dengan hari peringatan agar tidak mengganggu test
                $endDate = fake()->dateTimeBetween('+1 week', '+2 years')->format('Y-m-d');
                $reminderDays = array_unique(array_merge(fake()->randomElements(['3_months', '2_months', '1_month', '2_weeks'], fake()->numberBetween(0, 3)), ['0_days']));
            }

            $licence = Licence::create([
                'name' => fake()->company() . ' Software',
                'vendor_name' => 'PT Vendor ' . fake()->company(),
                'period_start' => $startDate,
                'period_end' => $endDate,
                'licence_type' => $type,
                'reminder_days' => $reminderDays,
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
