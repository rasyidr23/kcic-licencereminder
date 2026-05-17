<?php

namespace App\Http\Controllers;

use App\Models\Licence;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1 & 2. Get statistics for Subscription vs Perpetual and Active vs Inactive in a SINGLE query
        $today = Carbon::today();
        $todayStr = $today->toDateString();
        $stats = \Illuminate\Support\Facades\DB::table('licences')
            ->selectRaw("
                SUM(CASE WHEN licence_type = 'Subscription' THEN 1 ELSE 0 END) as subscription_count,
                SUM(CASE WHEN licence_type = 'Perpetual' THEN 1 ELSE 0 END) as perpetual_count,
                SUM(CASE WHEN licence_type = 'Perpetual' OR (licence_type = 'Subscription' AND period_end >= ?) THEN 1 ELSE 0 END) as active_count,
                SUM(CASE WHEN licence_type = 'Subscription' AND (period_end < ? OR period_end IS NULL) THEN 1 ELSE 0 END) as inactive_count
            ", [$todayStr, $todayStr])
            ->first();

        $subscriptionCount = $stats->subscription_count ?? 0;
        $perpetualCount = $stats->perpetual_count ?? 0;
        $activeCount = $stats->active_count ?? 0;
        $inactiveCount = $stats->inactive_count ?? 0;

        // 3. Get Top 10 Licences Expiring Soon (or already expired recently)
        $expiringSoonLicences = Licence::where('licence_type', 'Subscription')
            ->whereNotNull('period_end')
            ->orderBy('period_end', 'asc') // This will put older/expired ones first.
            // If we only want ones expiring in the future or not too long ago:
            ->whereDate('period_end', '>=', $today->copy()->subDays(30)) // exclude those expired more than 30 days ago
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'subscriptionCount',
            'perpetualCount',
            'activeCount',
            'inactiveCount',
            'expiringSoonLicences'
        ));
    }
}
