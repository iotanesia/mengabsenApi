@extends('layouts.auth')
@section('content')
<div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
    <div class="w-100 d-lg-flex align-items-center justify-content-center px-5"><img class="img-fluid" src="{{asset('/')}}app-assets/images/pages/forgot-password-v2.svg" alt="Forgot password V2" /></div>
</div>
<div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
    <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
        <h2 class="card-title font-weight-bold mb-1">Forgot Password? 🔒</h2>
        <p class="card-text mb-2">Enter your email and we'll send you instructions to reset your password</p>
        <form class="auth-forgot-password-form mt-2" action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="forgot-password-email">Email</label>
                <input class="form-control @error('email') is-invalid @enderror" id="forgot-password-email" type="text" name="email" placeholder="john@example.com" aria-describedby="login-email" autofocus="" tabindex="1" />
                @error('email') <span class="invalid-feedback"> {{ $message }} </span> @enderror 
                
            </div>
            <button class="btn btn-primary btn-block" tabindex="2">Send reset link</button>
        </form>
        <p class="text-center mt-2"><a href="{{route('login')}}"><i data-feather="chevron-left"></i> Back to login</a></p>
    </div>
</div>
@endsection