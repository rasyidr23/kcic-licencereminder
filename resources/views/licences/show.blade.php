@extends('layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left mb-4">
                <h2>{{ __('messages.show_licence_details') }}</h2>
            </div>
            <div class="pull-right mb-4">
                <a class="btn btn-secondary" href="{{ route('licences.index') }}"> {{ __('messages.back') }}</a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <strong>{{ __('messages.name') }}:</strong>
                        <p>{{ $licence->name }}</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <strong>{{ __('messages.vendor_name') }}:</strong>
                        <p>{{ $licence->vendor_name ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <strong>{{ __('messages.type') }}:</strong>
                        <p><span class="badge {{ $licence->licence_type == 'Perpetual' ? 'bg-success' : 'bg-primary' }}">{{ $licence->licence_type }}</span></p>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <strong>{{ __('messages.period') }}:</strong>
                        <p>
                            @if($licence->period_start && $licence->period_end)
                                {{ \Carbon\Carbon::parse($licence->period_start)->format('d M Y') }} {{ __('messages.to') }} {{ \Carbon\Carbon::parse($licence->period_end)->format('d M Y') }}
                            @elseif($licence->period_start)
                                {{ __('messages.since', ['date' => \Carbon\Carbon::parse($licence->period_start)->format('d M Y')]) }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 mb-3">
                    <div class="form-group">
                        <strong>{{ __('messages.custom_reminder') }}:</strong>
                        <p>
                            @php
                                $reminders = is_array($licence->reminder_days) ? $licence->reminder_days : [];
                                $labels = [
                                    '3_months' => __('messages.3_months'),
                                    '2_months' => __('messages.2_months'),
                                    '1_month' => __('messages.1_month'),
                                    '2_weeks' => __('messages.2_weeks'),
                                    '0_days' => __('messages.0_days')
                                ];
                            @endphp
                            @if(count($reminders) > 0)
                                <ul>
                                    @foreach($reminders as $rem)
                                        <li>{{ $labels[$rem] ?? $rem }}</li>
                                    @endforeach
                                </ul>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                    <div class="form-group">
                        <strong>{{ __('messages.description') }}:</strong>
                        <p>{{ $licence->description ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">{{ __('messages.update_history_log') }}</h5>
        </div>
        <div class="card-body">
            @if($licence->logs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('messages.timestamp') }}</th>
                                <th>{{ __('messages.vendor_name') }}</th>
                                <th>{{ __('messages.period') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($licence->logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d M Y, H:i') }}</td>
                                    <td>{{ $log->vendor_name ?? '-' }}</td>
                                    <td>
                                        @if($log->period_start && $log->period_end)
                                            {{ \Carbon\Carbon::parse($log->period_start)->format('d M Y') }} {{ __('messages.to') }} {{ \Carbon\Carbon::parse($log->period_end)->format('d M Y') }}
                                        @elseif($log->period_start)
                                            {{ __('messages.since', ['date' => \Carbon\Carbon::parse($log->period_start)->format('d M Y')]) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">{{ __('messages.no_history') }}</p>
            @endif
        </div>
    </div>
@endsection
