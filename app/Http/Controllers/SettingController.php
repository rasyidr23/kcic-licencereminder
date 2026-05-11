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
        
        try {
            foreach ($emails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    \Illuminate\Support\Facades\Mail::raw("Halo! Ini adalah email uji coba (Test Notification) dari aplikasi KCIC Licence Reminder.\n\nJika Anda menerima email ini, berarti pengaturan email Anda sudah berjalan dengan sempurna. Aplikasi kini siap mengirimkan pengingat lisensi secara otomatis sesuai jadwal.\n\nTerima kasih!", function ($message) use ($email) {
                        $message->to($email)
                                ->subject('Test Notification - KCIC Licence Reminder');
                    });
                }
            }
            return redirect()->back()->with('success', 'Test email sent successfully! Please check your inbox.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}
