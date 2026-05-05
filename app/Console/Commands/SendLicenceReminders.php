<?php

namespace App\Console\Commands;

use App\Mail\LicenceReminderMail;
use App\Models\Licence;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendLicenceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-licence-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for licences expiring in 1 month, 2 weeks, 1 week, and already expired.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        $totalSent = 0;

        $licences = Licence::where('licence_type', 'Subscription')
                           ->whereNotNull('period_end')
                           ->get();

        foreach ($licences as $licence) {
            $reminders = is_array($licence->reminder_days) ? $licence->reminder_days : [];
            if (empty($reminders)) continue;

            $expiredDate = Carbon::parse($licence->period_end)->startOfDay();
            
            $targetDates = [
                '3_months' => $today->copy()->addMonths(3),
                '2_months' => $today->copy()->addMonths(2),
                '1_month'  => $today->copy()->addMonth(),
                '2_weeks'  => $today->copy()->addWeeks(2),
                '0_days'   => $today->copy(),
            ];

            $labels = [
                '3_months' => '3 Bulan Lagi',
                '2_months' => '2 Bulan Lagi',
                '1_month'  => '1 Bulan Lagi',
                '2_weeks'  => '2 Minggu Lagi',
                '0_days'   => 'Sudah Expired Hari Ini'
            ];

            foreach ($reminders as $rem) {
                if (isset($targetDates[$rem])) {
                    if ($targetDates[$rem]->format('Y-m-d') === $expiredDate->format('Y-m-d')) {
                        $periodLabel = $labels[$rem];
                        
                        $targetEmailsSetting = \App\Models\Setting::where('key', 'target_emails')->value('value');
                        if ($targetEmailsSetting) {
                            $emails = array_map('trim', explode(',', $targetEmailsSetting));
                            foreach ($emails as $email) {
                                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    Mail::to($email)->send(new LicenceReminderMail($licence, $periodLabel));
                                    $this->info("Reminder sent for: {$licence->name} ({$periodLabel}) to {$email}");
                                    $totalSent++;
                                }
                            }
                        } else {
                            $this->warn("No target emails configured in settings for {$licence->name}");
                        }
                        
                        // Prevent sending multiple emails for the same licence on the same day
                        break;
                    }
                }
            }
        }

        $this->info("Successfully sent {$totalSent} reminder emails.");
    }
}
