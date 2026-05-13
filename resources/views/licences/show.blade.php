@extends('layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-file-lines text-primary"></i> {{ __('messages.show_licence_details') }}</h2>
        <div class="d-flex gap-2">
            <a class="btn btn-light rounded-pill px-4 shadow-sm border" href="{{ route('licences.index') }}">
                <i class="fa-solid fa-arrow-left"></i> {{ __('messages.back') }}
            </a>
            @if(auth()->user()->role === 'admin')
            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $licence->id }}">
                <i class="fa-solid fa-pen"></i> {{ __('messages.edit') }}
            </button>
            @endif
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal{{ $licence->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $licence->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <form action="{{ route('licences.update', $licence->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="editModalLabel{{ $licence->id }}"><i class="fa-solid fa-pen text-primary"></i> {{ __('messages.edit_modal_title') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.licence_name') }}</label>
                                <input type="text" name="name" value="{{ $licence->name }}" class="form-control bg-light" placeholder="{{ __('messages.licence_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.vendor_name') }}</label>
                                <input type="text" name="vendor_name" value="{{ $licence->vendor_name }}" class="form-control bg-light" placeholder="{{ __('messages.vendor_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.licence_type') }}</label>
                                <select name="licence_type" class="form-select bg-light edit-licence-type" data-id="{{ $licence->id }}">
                                    <option value="Subscription" {{ $licence->licence_type == 'Subscription' ? 'selected' : '' }}>{{ __('messages.subscription') }}</option>
                                    <option value="Perpetual" {{ $licence->licence_type == 'Perpetual' ? 'selected' : '' }}>{{ __('messages.perpetual') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.period_start') }}</label>
                                <input type="date" name="period_start" value="{{ $licence->period_start ? \Carbon\Carbon::parse($licence->period_start)->format('Y-m-d') : '' }}" class="form-control bg-light">
                            </div>
                            <div class="col-md-6 edit-period-end-wrapper-{{ $licence->id }}">
                                <label class="form-label fw-bold">{{ __('messages.period_end') }}</label>
                                <input type="date" name="period_end" value="{{ $licence->period_end ? \Carbon\Carbon::parse($licence->period_end)->format('Y-m-d') : '' }}" class="form-control bg-light">
                            </div>
                            <div class="col-12 edit-reminder-wrapper-{{ $licence->id }}">
                                <label class="form-label fw-bold">{{ __('messages.custom_reminder') }}</label>
                                <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded-3 border">
                                    @php $reminders = is_array($licence->reminder_days) ? $licence->reminder_days : []; @endphp
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="reminder_days[]" value="3_months" id="rem1_{{ $licence->id }}" {{ in_array('3_months', $reminders) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rem1_{{ $licence->id }}">{{ __('messages.3_months') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="reminder_days[]" value="2_months" id="rem2_{{ $licence->id }}" {{ in_array('2_months', $reminders) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rem2_{{ $licence->id }}">{{ __('messages.2_months') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="reminder_days[]" value="1_month" id="rem3_{{ $licence->id }}" {{ in_array('1_month', $reminders) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rem3_{{ $licence->id }}">{{ __('messages.1_month') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="reminder_days[]" value="2_weeks" id="rem4_{{ $licence->id }}" {{ in_array('2_weeks', $reminders) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rem4_{{ $licence->id }}">{{ __('messages.2_weeks') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('messages.description') }}</label>
                                <textarea name="description" class="form-control bg-light" rows="3" placeholder="{{ __('messages.description') }}">{{ $licence->description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fa-solid fa-floppy-disk"></i> {{ __('messages.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <p class="text-muted mb-1 fs-6">{{ __('messages.name') }}</p>
                    <h5 class="fw-bold">{{ $licence->name }}</h5>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1 fs-6">{{ __('messages.vendor_name') }}</p>
                    <h5 class="fw-bold">{{ $licence->vendor_name ?? '-' }}</h5>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1 fs-6">{{ __('messages.type') }}</p>
                    <span class="badge rounded-pill {{ $licence->licence_type == 'Perpetual' ? 'bg-warning text-dark' : '' }} fs-6" style="{{ $licence->licence_type == 'Subscription' ? 'background-color: #0d6efd; color: white;' : '' }}">{{ $licence->licence_type }}</span>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1 fs-6">{{ __('messages.period') }}</p>
                    <h5 class="fw-bold">
                        @if($licence->period_start && $licence->period_end)
                            {{ \Carbon\Carbon::parse($licence->period_start)->format('d M Y') }} <span class="text-muted fw-normal mx-1">{{ __('messages.to') }}</span> {{ \Carbon\Carbon::parse($licence->period_end)->format('d M Y') }}
                        @elseif($licence->period_start)
                            {{ __('messages.since', ['date' => \Carbon\Carbon::parse($licence->period_start)->format('d M Y')]) }}
                        @else
                            -
                        @endif
                    </h5>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1 fs-6">{{ __('messages.custom_reminder') }}</p>
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
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach($reminders as $rem)
                                <span class="badge bg-light text-dark border"><i class="fa-regular fa-clock"></i> {{ $labels[$rem] ?? $rem }}</span>
                            @endforeach
                        </div>
                    @else
                        <h5 class="fw-bold">-</h5>
                    @endif
                </div>
                <div class="col-md-12">
                    <p class="text-muted mb-1 fs-6">{{ __('messages.description') }}</p>
                    <div class="p-3 bg-light rounded-3 border">
                        <p class="mb-0">{{ $licence->description ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mt-4">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h5 class="fw-bold mb-0 text-secondary"><i class="fa-solid fa-clock-rotate-left"></i> {{ __('messages.update_history_log') }}</h5>
        </div>
        <div class="card-body p-4">
            @if($licence->logs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">{{ __('messages.timestamp') }}</th>
                                <th class="border-0">{{ __('messages.vendor_name') }}</th>
                                <th class="border-0">{{ __('messages.period') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($licence->logs as $log)
                                <tr>
                                    <td class="text-muted"><i class="fa-regular fa-calendar-check"></i> {{ $log->created_at->format('d M Y, H:i') }}</td>
                                    <td class="fw-bold">{{ $log->vendor_name ?? '-' }}</td>
                                    <td>
                                        @if($log->period_start && $log->period_end)
                                            <span class="badge bg-light text-dark border">{{ \Carbon\Carbon::parse($log->period_start)->format('d M Y') }}</span> 
                                            <i class="fa-solid fa-arrow-right text-muted mx-1"></i> 
                                            <span class="badge bg-light text-dark border">{{ \Carbon\Carbon::parse($log->period_end)->format('d M Y') }}</span>
                                        @elseif($log->period_start)
                                            <span class="badge bg-light text-dark border">{{ __('messages.since', ['date' => \Carbon\Carbon::parse($log->period_start)->format('d M Y')]) }}</span>
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
                <div class="text-center py-4">
                    <i class="fa-solid fa-clock-rotate-left fs-1 text-muted opacity-25 mb-3"></i>
                    <p class="text-muted mb-0">{{ __('messages.no_history') }}</p>
                </div>
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
