<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $targetEmails = Setting::where('key', 'target_emails')->value('value');
        return view('settings.index', compact('targetEmails'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'target_emails' => 'nullable|string'
        ]);

        Setting::updateOrCreate(
            ['key' => 'target_emails'],
            ['value' => $request->target_emails]
        );

        return redirect()->route('settings.index')->with('success', 'Notification settings updated successfully.');
    }

    public function sendTestEmail()
    {
        $targetEmailsSetting = Setting::where('key', 'target_emails')->value('value');
        if (!$targetEmailsSetting) {
            return redirect()->back()->with('error', 'Please configure target emails first before testing.');
        }

        $emails = array_map('trim', explode(',', $targetEmailsSetting));
        
        $dummyLicence = \App\Models\Licence::first();
        if (!$dummyLicence) {
            $dummyLicence = current(array_filter([\App\Models\Licence::make([
                'name' => 'Demo Licence (Uji Coba Sistem)',
                'vendor_name' => 'PT Vendor Teknologi Dummy',
                'period_end' => now()->addWeeks(2)->format('Y-m-d'),
                'licence_type' => 'Subscription',
            ])], function($l) { $l->id = 999; return true; }));
        }
        
        try {
            foreach ($emails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\LicenceReminderMail($dummyLicence, '2 Minggu Lagi (TEST)'));
                }
            }
            return redirect()->back()->with('success', 'Test email sent successfully! Please check your inbox.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}
