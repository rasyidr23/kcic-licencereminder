<?php

namespace App\Http\Controllers;

use App\Models\Licence;
use Illuminate\Http\Request;

class LicenceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $status = $request->input('status', 'all');
        $sortBy = $request->input('sort_by');
        $sortDir = $request->input('sort_dir');
        $query = Licence::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where(function($q) {
                $q->where('licence_type', 'Perpetual')
                  ->orWhere(function($subQ) {
                      $subQ->where('licence_type', 'Subscription')
                           ->whereNotNull('period_end')
                           ->whereDate('period_end', '>=', \Carbon\Carbon::today());
                  });
            });
        } elseif ($status === 'inactive') {
            $query->where('licence_type', 'Subscription')
                  ->where(function($q) {
                      $q->whereNull('period_end')
                        ->orWhereDate('period_end', '<', \Carbon\Carbon::today());
                  });
        }

        $allowedSortColumns = ['id', 'name', 'vendor_name', 'licence_type', 'period_end'];
        if ($sortBy && in_array($sortBy, $allowedSortColumns) && in_array($sortDir, ['asc', 'desc'])) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->latest();
        }

        $licences = $query->paginate($perPage)->appends([
            'search' => $search, 
            'status' => $status,
            'sort_by' => $sortBy,
            'sort_dir' => $sortDir
        ]);

        return view('licences.index', compact('licences', 'search', 'perPage', 'status', 'sortBy', 'sortDir'));
    }

    public function create()
    {
        return view('licences.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'vendor_name' => 'nullable|string|max:255',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|required_if:licence_type,Subscription|date|after_or_equal:period_start',
            'licence_type' => 'required|in:Perpetual,Subscription',
            'reminder_days' => 'nullable|array',
            'reminder_days.*' => 'in:3_months,2_months,1_month,2_weeks',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        if ($data['licence_type'] === 'Perpetual') {
            $data['period_end'] = null;
            $data['expired_date'] = null; // Just in case, to keep DB clean
            $data['reminder_days'] = null;
        } else {
            $reminders = $request->input('reminder_days', []);
            if (!in_array('0_days', $reminders)) {
                $reminders[] = '0_days';
            }
            $data['reminder_days'] = $reminders;
        }

        $licence = Licence::create($data);

        $licence->logs()->create([
            'vendor_name' => $licence->vendor_name,
            'period_start' => $licence->period_start,
            'period_end' => $licence->period_end,
        ]);

        return redirect()->route('licences.index')
            ->with('success', 'Licence created successfully.');
    }

    public function show(Licence $licence)
    {
        return view('licences.show', compact('licence'));
    }

    public function edit(Licence $licence)
    {
        return view('licences.edit', compact('licence'));
    }

    public function update(Request $request, Licence $licence)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'vendor_name' => 'nullable|string|max:255',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|required_if:licence_type,Subscription|date|after_or_equal:period_start',
            'licence_type' => 'required|in:Perpetual,Subscription',
            'reminder_days' => 'nullable|array',
            'reminder_days.*' => 'in:3_months,2_months,1_month,2_weeks',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        if ($data['licence_type'] === 'Perpetual') {
            $data['period_end'] = null;
            $data['expired_date'] = null;
            $data['reminder_days'] = null;
        } else {
            $reminders = $request->input('reminder_days', []);
            if (!in_array('0_days', $reminders)) {
                $reminders[] = '0_days';
            }
            $data['reminder_days'] = $reminders;
        }

        // Track old values to check if there are changes
        $oldVendor = $licence->vendor_name;
        $oldPeriodStart = $licence->period_start;
        $oldPeriodEnd = $licence->period_end;

        $licence->update($data);

        // Check if any of the logged fields changed
        if (
            $oldVendor !== $licence->vendor_name || 
            $oldPeriodStart !== $licence->period_start || 
            $oldPeriodEnd !== $licence->period_end
        ) {
            $licence->logs()->create([
                'vendor_name' => $licence->vendor_name,
                'period_start' => $licence->period_start,
                'period_end' => $licence->period_end,
            ]);
        }

        return redirect()->route('licences.index')
            ->with('success', 'Licence updated successfully.');
    }

    public function destroy(Licence $licence)
    {
        $licence->delete();

        return redirect()->route('licences.index')
            ->with('success', 'Licence deleted successfully.');
    }
}
