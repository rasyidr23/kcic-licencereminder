@extends('layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left mb-4">
                <h2>{{ __('messages.show_licence_details') }}</h2>
            </div>
            <div class="pull-right mb-4">
                <a class="btn btn-secondary me-2" href="{{ route('licences.index') }}"> {{ __('messages.back') }}</a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $licence->id }}">
                    {{ __('messages.edit') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal{{ $licence->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $licence->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('licences.update', $licence->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{ $licence->id }}">{{ __('messages.edit_modal_title') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6 mb-3">
                                <div class="form-group">
                                    <strong>{{ __('messages.licence_name') }}:</strong>
                                    <input type="text" name="name" value="{{ $licence->name }}" class="form-control" placeholder="{{ __('messages.licence_name') }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 mb-3">
                                <div class="form-group">
                                    <strong>{{ __('messages.vendor_name') }}:</strong>
                                    <input type="text" name="vendor_name" value="{{ $licence->vendor_name }}" class="form-control" placeholder="{{ __('messages.vendor_name') }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 mb-3">
                                <div class="form-group">
                                    <strong>{{ __('messages.licence_type') }}:</strong>
                                    <select name="licence_type" class="form-select edit-licence-type" data-id="{{ $licence->id }}">
                                        <option value="Subscription" {{ $licence->licence_type == 'Subscription' ? 'selected' : '' }}>{{ __('messages.subscription') }}</option>
                                        <option value="Perpetual" {{ $licence->licence_type == 'Perpetual' ? 'selected' : '' }}>{{ __('messages.perpetual') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-6 mb-3">
                                <div class="form-group">
                                    <strong>{{ __('messages.period_start') }}:</strong>
                                    <input type="date" name="period_start" value="{{ $licence->period_start ? \Carbon\Carbon::parse($licence->period_start)->format('Y-m-d') : '' }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 mb-3 edit-period-end-wrapper-{{ $licence->id }}">
                                <div class="form-group">
                                    <strong>{{ __('messages.period_end') }}:</strong>
                                    <input type="date" name="period_end" value="{{ $licence->period_end ? \Carbon\Carbon::parse($licence->period_end)->format('Y-m-d') : '' }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-3 edit-reminder-wrapper-{{ $licence->id }}">
                                <div class="form-group">
                                    <strong>{{ __('messages.custom_reminder') }}:</strong><br>
                                    @php
                                        $reminders = is_array($licence->reminder_days) ? $licence->reminder_days : [];
                                    @endphp
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="reminder_days[]" value="3_months" id="rem1_{{ $licence->id }}" {{ in_array('3_months', $reminders) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rem1_{{ $licence->id }}">{{ __('messages.3_months') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="reminder_days[]" value="2_months" id="rem2_{{ $licence->id }}" {{ in_array('2_months', $reminders) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rem2_{{ $licence->id }}">{{ __('messages.2_months') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="reminder_days[]" value="1_month" id="rem3_{{ $licence->id }}" {{ in_array('1_month', $reminders) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rem3_{{ $licence->id }}">{{ __('messages.1_month') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="reminder_days[]" value="2_weeks" id="rem4_{{ $licence->id }}" {{ in_array('2_weeks', $reminders) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rem4_{{ $licence->id }}">{{ __('messages.2_weeks') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="form-group">
                                    <strong>{{ __('messages.description') }}:</strong>
                                    <textarea name="description" class="form-control" rows="3" placeholder="{{ __('messages.description') }}">{{ $licence->description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('messages.save_changes') }}</button>
                    </div>
                </form>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var editTypeSelects = document.querySelectorAll('.edit-licence-type');
            editTypeSelects.forEach(function(select) {
                var id = select.getAttribute('data-id');
                var periodEndWrapper = document.querySelector('.edit-period-end-wrapper-' + id);
                var reminderWrapper = document.querySelector('.edit-reminder-wrapper-' + id);

                function toggleEditFields() {
                    if (select.value === 'Perpetual') {
                        if(periodEndWrapper) periodEndWrapper.style.display = 'none';
                        if(reminderWrapper) reminderWrapper.style.display = 'none';
                    } else {
                        if(periodEndWrapper) periodEndWrapper.style.display = 'block';
                        if(reminderWrapper) reminderWrapper.style.display = 'block';
                    }
                }

                select.addEventListener('change', toggleEditFields);
                toggleEditFields();

                var editForm = select.closest('form');
                if (editForm) {
                    var editStart = editForm.querySelector('input[name="period_start"]');
                    var editEnd = editForm.querySelector('input[name="period_end"]');
                    if (editStart && editEnd) {
                        editStart.addEventListener('change', function() {
                            editEnd.min = this.value;
                        });
                        if(editStart.value) editEnd.min = editStart.value;
                    }
                }
            });
        });
    </script>
@endsection
