@extends('layouts.app', ['page' => __('User Profile'), 'pageSlug' => 'profile'])
<x-slot:title>{{$title}}</x-slot:title>
@section('content')
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Profil</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Profil</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 animate__animated animate__fadeInUp">
                <div class="card shadow-lg">
                    <div class="card-header text-center">
                        <h4 class="title mb-0">{{ __('Edit Profil') }}</h4>
                    </div>

                    <form method="post" action="{{ route('profile.update') }}" autocomplete="off">
                        <div class="card-body">
                            @csrf
                            @method('put')

                            {{-- Alert Success/Error --}}
                            @if(session('success'))
                                <div class="alert alert-success text-center" id="alert-box">
                                    {{ session('success') }}
                                </div>
                            @elseif(session('error'))
                                <div class="alert alert-danger text-center" id="alert-box">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="form-group mb-3">
                                <label>Username</label>
                                <input type="text" name="username"
                                    class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                    placeholder="Username" value="{{ old('username', $user->username ?? '') }}" required>
                                @include('alerts.feedback', ['field' => 'username'])
                            </div>

                            <hr style="border-color:#35376c;">
                            <h5 class="mb-3 text-center" >Ganti Password</h5>

                            <div class="form-group mb-3">
                                <label>Password Lama</label>
                                <input type="password" name="old_password"
                                    class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}"
                                    placeholder="Masukkan password lama">
                                @include('alerts.feedback', ['field' => 'old_password'])
                            </div>

                            <div class="form-group mb-3">
                                <label>Password Baru</label>
                                <input type="password" name="password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    placeholder="Password baru">
                                @include('alerts.feedback', ['field' => 'password'])
                            </div>

                            <div class="form-group mb-3">
                                <label>Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-end">
                            <button type="button" id="btn-submit" class="btn btn-fill btn-primary" style="border-radius:8px;">
                                {{ __('Simpan Perubahan') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Auto hide alert setelah 5 detik --}}
    <script>
        setTimeout(() => {
            const alertBox = document.getElementById('alert-box');
            if (alertBox) {
                alertBox.style.transition = 'opacity 0.5s ease';
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 5000);
    </script>

    <script>
        // Auto hide alert setelah 5 detik
        setTimeout(() => {
            const alertBox = document.getElementById('alert-box');
            if (alertBox) {
                alertBox.style.transition = 'opacity 0.5s ease';
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 5000);

        // SweetAlert konfirmasi sebelum submit
        document.getElementById('btn-submit').addEventListener('click', function () {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Perubahan profil akan disimpan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('form').submit();
                    Swal.fire({
                        title: 'Sedang Mengubah Profil...',
                        html: 'Mohon tunggu beberapa saat',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
            });
        });
    </script>

@endsection