@if ($errors->any())
    <div class="alert alert-danger mb-4" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong>Error!</strong>
        @foreach ($errors->all() as $error)
            <div> {{ $error }} </div>
        @endforeach
    </div>
@endif

@if(session()->has('access_denied_error'))
    <div class="alert alert-danger mb-4" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong>Access is denied!</strong>
        <div>{{ session()->get('access_denied_error') }}</div>
    </div>
@endif

@if(session()->has('account_disabled_error'))
    <div class="alert alert-danger mb-4" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong>Account is disabled!</strong>
        <div>{{ session()->get('account_disabled_error') }}</div>
    </div>
@endif
