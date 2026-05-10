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
                    hoverOffset: 4
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
                    hoverOffset: 4
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
