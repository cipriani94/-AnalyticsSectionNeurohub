@component('mail::message')
    {{ __('mail/sendcredentialuser.blade.welcome').' '.$user->name}},

    {{__('mail/sendcredentialuser.blade.firstLine')}}: <br>
    <ul>
        <li>
            Email: {{$user->email}}
        </li>
        <li>
            Password: {{$clear_password}}
        </li>
    </ul>

    @component('mail::button', ['url' => config('app.url').'/admin'])
        {{__('mail/sendcredentialuser.blade.loginButton')}}
    @endcomponent

    {{__('mail/sendcredentialuser.blade.endThanks')}},<br>
    {{ config('app.name') }}
@endcomponent
