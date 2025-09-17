@extends('layouts.app', ['class' => 'login-page', 'page' => __('Reset password'), 'contentClass' => 'login-page'])

@section('content')
    <div class="col-lg-5 col-md-7 ml-auto mr-auto">
        <form class="form" method="post" action="{{ route('password.email') }}">
            @csrf

            <div class="card card-login card-white">
                <div class="card-header">
                    <img src="{{ asset('black') }}/img/card-primary.png" alt="">
                    <h1 class="card-title">{{ __('Reset password') }}</h1>
                </div>
                <div class="card-body">
                    @include('alerts.success')

                    <div class="input-group{{ $errors->has('username') ? ' has-danger' : '' }}">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="tim-icons icon-single-02"></i>
                            </div>
                        </div>
                        <input type="text" name="username"
                            class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                            placeholder="{{ __('Username') }}">
                        @include('alerts.feedback', ['field' => 'username'])
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit"
                        class="btn btn-primary btn-lg btn-block mb-3">{{ __('Send Password Reset Link') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection