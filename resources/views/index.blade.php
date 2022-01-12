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
        @if($currentCompanyId === null)
            <div>
                <p>{{ __('myob-oauth-translations::myob.message.no_tenant_selected') }}</p>
            </div>
        @endif
        <h3>@lang('myob-oauth-translations::myob.label.accounts')</h3>
        <div>
            @foreach($companies as $company)
                @php($isCurrent = $company['Uri'] === $currentCompanyId)
                <form action="{{ route('myob.tenant.update', $company['Id']) }}"
                      method="POST"
                      novalidate>
                    @csrf
                    <div class="bg-white text-center">
                        <h4>{{$company['Name']}}</h4>
                        @if($isCurrent)
                        <div>
                            <p>{{ __('myob-oauth-translations::myob.label.current_tenant') }}</p>
                        </div>
                        @endif
                        <button class="@if($isCurrent) bg-gray-400 @else bg-blue-400 @endif"
                                @if($isCurrent) disabled @endif>
                            @lang('myob-oauth-translations::myob.button.select')
                        </button>
                    </div>
                </form>
            @endforeach
        </div>

    </div>
@endsection
