<?php

namespace App\Http\Controllers;

use App\Models\Licence;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $stats = \Illuminate\Support\Facades\Cache::remember('dashboard_stats', 600, function () use ($today) {
            $todayStr = $today->toDateString();
            $result = \Illuminate\Support\Facades\DB::table('licences')
                ->selectRaw("
                    SUM(CASE WHEN licence_type = 'Subscription' THEN 1 ELSE 0 END) as subscription_count,
                    SUM(CASE WHEN licence_type = 'Perpetual' THEN 1 ELSE 0 END) as perpetual_count,
                    SUM(CASE WHEN licence_type = 'Perpetual' OR (licence_type = 'Subscription' AND period_end >= ?) THEN 1 ELSE 0 END) as active_count,
                    SUM(CASE WHEN licence_type = 'Subscription' AND (period_end < ? OR period_end IS NULL) THEN 1 ELSE 0 END) as inactive_count
                ", [$todayStr, $todayStr])
                ->first();
            return (array) $result;
        });

        $subscriptionCount = $stats['subscription_count'] ?? 0;
        $perpetualCount = $stats['perpetual_count'] ?? 0;
        $activeCount = $stats['active_count'] ?? 0;
        $inactiveCount = $stats['inactive_count'] ?? 0;

        // 3. Get Top 10 Licences Expiring Soon (or already expired recently)
        $expiringSoonLicences = \Illuminate\Support\Facades\Cache::remember('dashboard_expiring', 600, function () use ($today) {
            return Licence::where('licence_type', 'Subscription')
                ->whereNotNull('period_end')
                ->orderBy('period_end', 'asc')
                ->whereDate('period_end', '>=', $today->copy()->subDays(30))
                ->take(10)
                ->get();
        });

        return view('dashboard', compact(
            'subscriptionCount',
            'perpetualCount',
            'activeCount',
            'inactiveCount',
            'expiringSoonLicences'
        ));
    }
}
