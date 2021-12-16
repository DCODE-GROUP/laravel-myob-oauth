@extends(config('laravel-myob-oauth.admin_app_layout'))

@section('content')
    <div>
        <h2>@lang('myob-oauth-translations::myob.label.header')</h2>

        @if (!$token || $token->toOAuth2Token()->hasExpired())
            <div>
                <p><i>@lang('myob-oauth-translations::myob.status.unauthorized')</i></p>
                <a href="{{ route('myob.auth') }}"
                   class="text-blue-400 underline">@lang('myob-oauth-translations::myob.button.authorize')</a>
            </div>
        @else
            <p><i>@lang('myob-oauth-translations::myob.status.authorized') </i></p>
        @endif
        <hr/>
        <h3>@lang('myob-oauth-translations::myob.label.accounts')</h3>
        <div>
            @foreach($tenants as $index => $tenant)
                <form action="{{ route('myob.tenant.update', $tenant->tenantId) }}"
                      method="POST"
                      novalidate>
                    @csrf
                    <div class="bg-white text-center
                    @if($index !== 0)
                            ml-2
@endif
                            ">
                        <h1>{{$tenant->tenantType}}</h1>
                        <div>
                            <p>{{$tenant->tenantName}}</p>
                        </div>
                        <button
                                class="
                            @if($tenant->tenantId === $currentTenantId) bg-gray-400 @else bg-blue-400 @endif"
                                @if($tenant->tenantId === $currentTenantId) disabled @endif
                        >@lang('myob-oauth-translations::myob.button.select')</button>
                    </div>
                </form>
            @endforeach
        </div>

    </div>
@endsection