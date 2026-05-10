<?php

namespace App\Http\Controllers;

use App\Models\Licence;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Get statistics for Subscription vs Perpetual
        $subscriptionCount = Licence::where('licence_type', 'Subscription')->count();
        $perpetualCount = Licence::where('licence_type', 'Perpetual')->count();

        // 2. Get statistics for Active vs Inactive (Expired)
        $today = Carbon::today();
        
        // Active: Perpetual OR Subscription with period_end >= today
        $activeCount = Licence::where(function($q) use ($today) {
            $q->where('licence_type', 'Perpetual')
              ->orWhere(function($subQ) use ($today) {
                  $subQ->where('licence_type', 'Subscription')
                       ->whereNotNull('period_end')
                       ->whereDate('period_end', '>=', $today);
              });
        })->count();

        // Inactive: Subscription with period_end < today OR null
        $inactiveCount = Licence::where('licence_type', 'Subscription')
            ->where(function($q) use ($today) {
                $q->whereNull('period_end')
                  ->orWhereDate('period_end', '<', $today);
            })->count();

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
