
@component('mail::message')
# Hello {{$user->name}}

YOu have changed your email.Please verify your new  email using below button.

@component('mail::button', ['url' => route('verify',['token'=>$user->verification_token])])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
