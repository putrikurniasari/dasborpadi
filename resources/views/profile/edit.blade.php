@extends('layouts.app', ['page' => __('User Profile'), 'pageSlug' => 'profile'])
<x-slot:title>{{$title}}</x-slot:title>
@section('page', 'profile')
@section('content')

    <!-- ===== Main Content Start ===== -->
    <main>
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
            <!-- Breadcrumb Start -->
            <div x-data="{ pageName: `Profile`}">
                <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName"></h2>

                    <nav>
                        <ol class="flex items-center gap-1.5">
                            <li>
                                <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                                    href="index.html">
                                    Home
                                    <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </li>
                            <li class="text-sm text-gray-800 dark:text-white/90" x-text="pageName"></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- Breadcrumb End -->

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">
                    Profile
                </h3>

                <!-- profil info -->
                <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                        <div class="flex flex-col items-center w-full gap-6 xl:flex-row">
                            <div class="w-20 h-20 overflow-hidden border border-gray-200 rounded-full dark:border-gray-800">
                                <img src="{{ asset('images/user/pprofil.jpg') }}" alt="user" />
                            </div>
                            <div class="order-3 xl:order-2">
                                <h4
                                    class="mb-2 text-lg font-semibold text-center text-gray-800 dark:text-white/90 xl:text-left">
                                    {{ old('username', $user->username ?? '') }}
                                </h4>

                                <div class="flex flex-col items-center gap-1 text-center xl:flex-row xl:gap-3 xl:text-left">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        @if($user->role === 'admin')
                                            Admin Monitoring Padi
                                        @else
                                            User Monitoring Padi
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- profil edit -->
                <form method="post" action="{{ route('profile.update') }}" autocomplete="off">
                    <div class="rounded-2xl border border-gray-200 bg-black dark:border-gray-800 dark:bg-white/[0.03]">
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

                        <div class="px-5 py-4 sm:px-6 sm:py-5">
                            <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                                {{ __('Edit Profil') }}
                            </h3>
                        </div>
                        <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                            <!-- Elements -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Username
                                </label>
                                <input type="text" name="username" placeholder="Username"
                                    class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }} dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    value="{{ old('username', $user->username ?? '') }}" required />
                            </div>

                            <!-- Elements -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Password Lama
                                </label>
                                <input type="password" name="old_password" placeholder="Password Lama"
                                    class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }} dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                @include('alerts.feedback', ['field' => 'old_password'])
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Password Baru
                                </label>
                                <input type="password" placeholder="Pasword Baru" name="password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                @include('alerts.feedback', ['field' => 'password'])
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Konfirmasi Password
                                </label>
                                <input type="password" placeholder="Ulangi Password Baru"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            </div>

                            <div class="flex justify-end">
                                <button id="btn-submit"
                                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                    {{ __('Update Profil') }}
                                </button>
                            </div>
                        </div>
                    </div>
            </div>
            </form>
            <!-- ====== Form Elements Section End -->
        </div>
        </div>
    </main>
    <!-- ===== Main Content End ===== -->

    <!-- <script defer src={{ asset('js/bundle.js') }}></script> -->
    </body>
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