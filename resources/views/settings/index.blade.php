@extends('layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-envelope-circle-check text-primary"></i> {{ __('messages.settings_title') }}</h2>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                        <div class="form-group">
                            <label class="form-label fw-bold">{{ __('messages.target_emails') }} <span class="text-danger">*</span></label>
                            <textarea name="target_emails" class="form-control form-control-lg bg-light" rows="4" placeholder="e.g. admin@domain.com, manager@domain.com">{{ $targetEmails }}</textarea>
                            <div class="form-text mt-2"><i class="fa-solid fa-circle-info"></i> {{ __('messages.target_emails_help') }}</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-end">
                        <button type="submit" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm"><i class="fa-solid fa-floppy-disk"></i> {{ __('messages.update_settings') }}</button>
                    </div>
                </div>
            </form>

            <hr class="my-5 opacity-25">
            
            <div class="d-flex justify-content-between align-items-center bg-light p-4 rounded-4 border">
                <div>
                    <h5 class="mb-1 fw-bold"><i class="fa-solid fa-paper-plane text-success"></i> Uji Coba Pengiriman Email</h5>
                    <p class="text-muted mb-0 small">Kirim email simulasi untuk memastikan konfigurasi SMTP (Gmail) Anda sudah berjalan dengan baik.</p>
                </div>
                <form action="{{ route('settings.test_email') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-success rounded-pill px-4 shadow-sm"><i class="fa-solid fa-vial"></i> Kirim Email Tes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
