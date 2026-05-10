@extends('layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left mb-4">
                <h2>{{ __('messages.licence_reminder_dashboard') }}</h2>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p class="mb-0">{{ $message }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>{{ __('messages.whoops') }}</strong> {{ __('messages.problems_input') }}<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('licences.index') }}" method="GET" class="row align-items-center">
                <div class="col-md-2 mb-2 mb-md-0">
                    <select name="per_page" class="form-select" onchange="this.form.submit()">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 {{ __('messages.per_page') }}</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 {{ __('messages.per_page') }}</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 {{ __('messages.per_page') }}</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search_placeholder') }}" value="{{ $search }}">
                        <button class="btn btn-outline-secondary" type="submit">{{ __('messages.search') }}</button>
                    </div>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>{{ __('messages.all_status') ?? 'All Status' }}</option>
                        <option value="active" {{ $status == 'active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                        <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                    </select>
                </div>
                <!-- Hidden inputs to preserve sorting when submitting the search/filter form -->
                <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                <input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">
                
                <div class="col-md-4 text-md-end">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                        + {{ __('messages.create_new_licence') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-nowrap">
                    <tr>
                        <th class="border-0">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_dir' => request('sort_by') == 'id' && request('sort_dir') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none d-flex align-items-center gap-1">
                                {{ __('messages.no') }}
                                @if(request('sort_by') == 'id')
                                    <i class="fa-solid {{ request('sort_dir') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }} text-primary"></i>
                                @else
                                    <i class="fa-solid fa-sort text-muted opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="border-0">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_dir' => request('sort_by') == 'name' && request('sort_dir') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none d-flex align-items-center gap-1">
                                {{ __('messages.name') }}
                                @if(request('sort_by') == 'name')
                                    <i class="fa-solid {{ request('sort_dir') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }} text-primary"></i>
                                @else
                                    <i class="fa-solid fa-sort text-muted opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="border-0">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'vendor_name', 'sort_dir' => request('sort_by') == 'vendor_name' && request('sort_dir') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none d-flex align-items-center gap-1">
                                {{ __('messages.vendor_name') }}
                                @if(request('sort_by') == 'vendor_name')
                                    <i class="fa-solid {{ request('sort_dir') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }} text-primary"></i>
                                @else
                                    <i class="fa-solid fa-sort text-muted opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="border-0">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'licence_type', 'sort_dir' => request('sort_by') == 'licence_type' && request('sort_dir') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none d-flex align-items-center gap-1">
                                {{ __('messages.type') }}
                                @if(request('sort_by') == 'licence_type')
                                    <i class="fa-solid {{ request('sort_dir') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }} text-primary"></i>
                                @else
                                    <i class="fa-solid fa-sort text-muted opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="border-0">{{ __('messages.status') }}</th>
                        <th class="border-0">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'period_end', 'sort_dir' => request('sort_by') == 'period_end' && request('sort_dir') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none d-flex align-items-center gap-1">
                                {{ __('messages.expired_date') }}
                                @if(request('sort_by') == 'period_end')
                                    <i class="fa-solid {{ request('sort_dir') == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }} text-primary"></i>
                                @else
                                    <i class="fa-solid fa-sort text-muted opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="border-0 text-center" width="150px">{{ __('messages.action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($licences as $licence)
                        <tr>
                            <td>
                                @if(request('sort_by') == 'id' && request('sort_dir') == 'desc')
                                    {{ $licences->total() - ($licences->firstItem() - 1) - $loop->index }}
                                @else
                                    {{ $licences->firstItem() + $loop->index }}
                                @endif
                            </td>
                            <td>{{ $licence->name }}</td>
                            <td>{{ $licence->vendor_name ?? '-' }}</td>
                            <td>
                                <span class="badge rounded-pill {{ $licence->licence_type == 'Perpetual' ? 'bg-success' : 'bg-primary' }}">
                                    {{ $licence->licence_type }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $isActive = true;
                                    if ($licence->licence_type == 'Subscription') {
                                        if (!$licence->period_end || \Carbon\Carbon::parse($licence->period_end)->startOfDay()->lt(\Carbon\Carbon::today())) {
                                            $isActive = false;
                                        }
                                    }
                                @endphp
                                @if($isActive)
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle"><i class="fa-solid fa-circle-check"></i> {{ __('messages.active') }}</span>
                                @else
                                    <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle"><i class="fa-solid fa-circle-xmark"></i> {{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($licence->licence_type == 'Perpetual')
                                    <span class="text-muted fst-italic">{{ __('messages.permanent') }}</span>
                                @else
                                    {{ $licence->period_end ? \Carbon\Carbon::parse($licence->period_end)->format('d M Y') : '-' }}
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('licences.destroy',$licence->id) }}" method="POST" class="mb-0 delete-form d-flex justify-content-center gap-1">
                                    <a class="btn btn-outline-info btn-sm" href="{{ route('licences.show',$licence->id) }}" title="{{ __('messages.show') }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $licence->id }}" title="{{ __('messages.edit') }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>

                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-outline-danger btn-sm btn-delete" title="{{ __('messages.delete') }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

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
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('messages.no_licences_found') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $licences->links() }}
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <form action="{{ route('licences.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="createModalLabel"><i class="fa-solid fa-plus text-primary"></i> {{ __('messages.create_modal_title') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.licence_name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control bg-light" placeholder="{{ __('messages.licence_name') }}" value="{{ old('name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.vendor_name') }}</label>
                                <input type="text" name="vendor_name" class="form-control bg-light" placeholder="{{ __('messages.vendor_name') }}" value="{{ old('vendor_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.licence_type') }} <span class="text-danger">*</span></label>
                                <select name="licence_type" class="form-select bg-light" id="create_licence_type">
                                    <option value="Subscription" {{ old('licence_type') == 'Subscription' ? 'selected' : '' }}>{{ __('messages.subscription') }}</option>
                                    <option value="Perpetual" {{ old('licence_type') == 'Perpetual' ? 'selected' : '' }}>{{ __('messages.perpetual') }}</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.period_start') }}</label>
                                <input type="date" name="period_start" class="form-control bg-light" value="{{ old('period_start') }}">
                            </div>
                            <div class="col-md-6" id="create_period_end_wrapper">
                                <label class="form-label fw-bold">{{ __('messages.period_end') }}</label>
                                <input type="date" name="period_end" class="form-control bg-light" value="{{ old('period_end') }}">
                            </div>
                            <div class="col-12" id="create_reminder_days_wrapper">
                                <label class="form-label fw-bold">{{ __('messages.custom_reminder') }}</label>
                                <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded-3 border">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="reminder_days[]" value="3_months" id="crem1" {{ is_array(old('reminder_days')) && in_array('3_months', old('reminder_days')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="crem1">{{ __('messages.3_months') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="reminder_days[]" value="2_months" id="crem2" {{ is_array(old('reminder_days')) && in_array('2_months', old('reminder_days')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="crem2">{{ __('messages.2_months') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="reminder_days[]" value="1_month" id="crem3" {{ is_array(old('reminder_days')) && in_array('1_month', old('reminder_days')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="crem3">{{ __('messages.1_month') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="reminder_days[]" value="2_weeks" id="crem4" {{ is_array(old('reminder_days')) && in_array('2_weeks', old('reminder_days')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="crem4">{{ __('messages.2_weeks') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('messages.description') }}</label>
                                <textarea name="description" class="form-control bg-light" rows="3" placeholder="{{ __('messages.description') }}">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fa-solid fa-floppy-disk"></i> {{ __('messages.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Create Modal Logic
            var createTypeSelect = document.getElementById('create_licence_type');
            var createPeriodEndWrapper = document.getElementById('create_period_end_wrapper');
            var createReminderWrapper = document.getElementById('create_reminder_days_wrapper');

            function toggleCreateFields() {
                if (createTypeSelect.value === 'Perpetual') {
                    if(createPeriodEndWrapper) createPeriodEndWrapper.style.display = 'none';
                    if(createReminderWrapper) createReminderWrapper.style.display = 'none';
                } else {
                    if(createPeriodEndWrapper) createPeriodEndWrapper.style.display = 'block';
                    if(createReminderWrapper) createReminderWrapper.style.display = 'block';
                }
            }

            createTypeSelect.addEventListener('change', toggleCreateFields);
            toggleCreateFields();

            // Edit Modal Logic
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
                        // Initialize
                        if(editStart.value) editEnd.min = editStart.value;
                    }
                }
            });

            // Create form period start to period end dynamic min
            var createForm = document.querySelector('#createModal form');
            if (createForm) {
                var createStart = createForm.querySelector('input[name="period_start"]');
                var createEnd = createForm.querySelector('input[name="period_end"]');
                if (createStart && createEnd) {
                    createStart.addEventListener('change', function() {
                        createEnd.min = this.value;
                    });
                    if(createStart.value) createEnd.min = createStart.value;
                }
            }

            @if($errors->any())
                // If there are validation errors, optionally we could re-open a modal here.
                // However, the error banner at the top will alert the user.
            @endif

            // Delete Confirmation with SweetAlert2
            var deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    var form = this.closest('form');
                    
                    Swal.fire({
                        title: "{!! __('messages.are_you_sure') !!}",
                        text: "{!! __('messages.wont_revert') !!}",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: "{!! __('messages.yes_delete') !!}"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
