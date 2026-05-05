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
}
