@extends('layouts.app', ['page' => __('User Profile'), 'pageSlug' => 'profile'])

@section('content')
    <div class="row">
        <div class="col-md-12 animate__animated animate__fadeInUp">
            <div class="card shadow-lg" style="background: #23243a; border: 1px solid #35376c;">
                <div class="card-header text-center">
                    <h4 class="title text-light mb-0">{{ __('Edit Profil Pengguna') }}</h4>
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
                            <label style="color:#a3aed6;">Username</label>
                            <input type="text" name="username"
                                class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                placeholder="Username" value="{{ old('username', $user->username ?? '') }}" required>
                            @include('alerts.feedback', ['field' => 'username'])
                        </div>

                        <hr style="border-color:#35376c;">
                        <h5 class="mb-3 text-center" style="color:#e14eca;">Ganti Password</h5>

                        <div class="form-group mb-3">
                            <label style="color:#a3aed6;">Password Lama</label>
                            <input type="password" name="old_password"
                                class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}"
                                placeholder="Masukkan password lama">
                            @include('alerts.feedback', ['field' => 'old_password'])
                        </div>

                        <div class="form-group mb-3">
                            <label style="color:#a3aed6;">Password Baru</label>
                            <input type="password" name="password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                placeholder="Password baru">
                            @include('alerts.feedback', ['field' => 'password'])
                        </div>

                        <div class="form-group mb-3">
                            <label style="color:#a3aed6;">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end">
                        <button type="button" id="btn-submit" class="btn btn-fill btn-primary" style="background: linear-gradient(90deg,#e14eca 60%,#23243a 100%);
                           border:none; border-radius:8px;">
                            {{ __('Simpan Perubahan') }}
                        </button>
                    </div>

                </form>
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