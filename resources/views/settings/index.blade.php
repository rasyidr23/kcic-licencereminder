@extends('layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left mb-4">
                <h2>{{ __('messages.settings_title') }}</h2>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                        <div class="form-group">
                            <strong>{{ __('messages.target_emails') }}:</strong>
                            <textarea name="target_emails" class="form-control" rows="3" placeholder="e.g. admin@domain.com, manager@domain.com">{{ $targetEmails }}</textarea>
                            <small class="text-muted">{{ __('messages.target_emails_help') }}</small>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">{{ __('messages.update_settings') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
