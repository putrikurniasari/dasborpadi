@extends('layouts.app', ['class' => 'register-page', 'page' => __('Register Page'), 'contentClass' => 'register-page'])

@section('content')
    <div class="d-flex justify-content-center align-items-center min-vh-100" >
        <div class="w-100" style="max-width: 400px;">
            <div class="p-4 shadow-lg rounded" style="background: #23243a; border: 1px solid #35376c;">
                <h3 class="text-center mb-4" style="color: #7886C7; font-weight: 700; letter-spacing: 1px;">Daftar Akun</h3>
                <form method="post" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label" style="color:#a3aed6;">Username</label>
                        <input type="text" id="username" name="username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" placeholder="Username" required autofocus>
                        @include('alerts.feedback', ['field' => 'username'])
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label" style="color:#a3aed6;">Password</label>
                        <input type="password" id="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password" required>
                        @include('alerts.feedback', ['field' => 'password'])
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label" style="color:#a3aed6;">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" style="background: linear-gradient(90deg,#7886C7 60%,#2D336B 100%); border:none; border-radius: 8px; font-weight:600;">Daftar</button>
                    <p class="mt-3 mb-0 text-center" style="color:#a3aed6; font-size:0.95rem;">Sudah punya akun? <a href="{{ route('login') }}" style="color:#7886C7;">Login</a></p>
                </form>
            </div>
        </div>
    </div>
@endsection
