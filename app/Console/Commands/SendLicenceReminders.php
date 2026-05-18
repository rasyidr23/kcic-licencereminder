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

        // Bug Fix 1: Fetch settings ONCE outside the loop, not inside it
        $targetEmailsSetting = \App\Models\Setting::where('key', 'target_emails')->value('value');
        if (!$targetEmailsSetting) {
            $this->warn('No target emails configured in settings. Aborting.');
            return;
        }
        $emails = array_filter(array_map('trim', explode(',', $targetEmailsSetting)), fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL));

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

            $shouldSend = false;
            $periodLabel = '';

            // Bug Fix 2: Only send "expired" notification on the day of expiry up to 7 days after
            // (not EVERY day forever for expired licences)
            $daysSinceExpiry = $expiredDate->diffInDays($today, false);
            if ($expiredDate->lessThanOrEqualTo($today) && $daysSinceExpiry >= 0 && $daysSinceExpiry <= 7) {
                $shouldSend = true;
                $periodLabel = $daysSinceExpiry === 0 ? 'Expired Hari Ini' : 'Telah Expired (Lewat Waktu)';
            } else {
                foreach ($reminders as $rem) {
                    if (isset($targetDates[$rem])) {
                        if ($targetDates[$rem]->format('Y-m-d') === $expiredDate->format('Y-m-d')) {
                            $shouldSend = true;
                            $periodLabel = $labels[$rem];
                            break;
                        }
                    }
                }
            }

            if ($shouldSend) {
                foreach ($emails as $email) {
                    Mail::to($email)->send(new LicenceReminderMail($licence, $periodLabel));
                    $this->info("Reminder sent for: {$licence->name} ({$periodLabel}) to {$email}");
                    $totalSent++;
                }
            }
        }

        $this->info("Successfully sent {$totalSent} reminder emails.");
    }
}
