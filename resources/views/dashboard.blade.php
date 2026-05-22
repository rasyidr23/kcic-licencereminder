@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('messages.statistics_dashboard') }}</h2>
</div>

<div class="row">
    <!-- Active vs Inactive Pie Chart -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100 rounded-4">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="card-title fw-bold text-secondary">{{ __('messages.licence_status') }}</h5>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <div style="width: 80%; max-width: 400px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription vs Perpetual Pie Chart -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100 rounded-4">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="card-title fw-bold text-secondary">{{ __('messages.licence_types') }}</h5>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <div style="width: 80%; max-width: 400px;">
                    <canvas id="typeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="card-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation"></i> {{ __('messages.expiring_soon') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('messages.name') }}</th>
                                <th>{{ __('messages.vendor_name') }}</th>
                                <th>{{ __('messages.expired_date') }}</th>
                                <th>{{ __('messages.days_left') }}</th>
                                <th>{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expiringSoonLicences as $licence)
                                @php
                                    $endDate = \Carbon\Carbon::parse($licence->period_end)->startOfDay();
                                    $today = \Carbon\Carbon::today();
                                    $daysLeft = $today->diffInDays($endDate, false); // false for negative if passed
                                    
                                    if ($daysLeft < 0) {
                                        $badgeClass = 'bg-danger';
                                        $statusText = __('messages.expired');
                                    } elseif ($daysLeft <= 14) {
                                        $badgeClass = 'bg-warning text-dark';
                                        $statusText = $daysLeft . ' ' . __('messages.days');
                                    } else {
                                        $badgeClass = 'bg-info text-dark';
                                        $statusText = $daysLeft . ' ' . __('messages.days');
                                    }
                                @endphp
                                <tr>
                                    <td class="fw-bold">{{ $licence->name }}</td>
                                    <td>{{ $licence->vendor_name ?? '-' }}</td>
                                    <td>{{ $endDate->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ $badgeClass }} fs-6">{{ $statusText }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('licences.show', $licence->id) }}" class="btn btn-sm btn-outline-primary">
                                            {{ __('messages.show') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada lisensi yang mendesak.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Status Chart (Active vs Inactive)
        var ctxStatus = document.getElementById('statusChart').getContext('2d');
        var statusChart = new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['{{ __('messages.active_status') }}', '{{ __('messages.inactive_status') }}'],
                datasets: [{
                    data: [{{ $activeCount }}, {{ $inactiveCount }}],
                    backgroundColor: ['#198754', '#dc3545'], // Success Green, Danger Red
                    hoverBackgroundColor: ['#157347', '#c82333'],
                    borderWidth: 0,
                    hoverOffset: 30
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, font: { size: 14 } }
                    }
                },
                cutout: '60%'
            }
        });

        // Type Chart (Subscription vs Perpetual)
        var ctxType = document.getElementById('typeChart').getContext('2d');
        var typeChart = new Chart(ctxType, {
            type: 'doughnut',
            data: {
                labels: ['Subscription', 'Perpetual'],
                datasets: [{
                    data: [{{ $subscriptionCount }}, {{ $perpetualCount }}],
                    backgroundColor: ['#0d6efd', '#ffc107'], // Primary Blue, Warning Yellow
                    hoverBackgroundColor: ['#0b5ed7', '#e0a800'],
                    borderWidth: 0,
                    hoverOffset: 30
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, font: { size: 14 } }
                    }
                },
                cutout: '60%'
            }
        });
    });
</script>
@endsection
