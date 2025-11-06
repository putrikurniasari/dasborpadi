<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login & Register' }}</title>

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Font Awesome 5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet">
    <!-- CSS -->
    <link href="{{ asset('css/black-dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container {{ session('error') ? '' : 'animate__animated animate__fadeInUp' }}" id="container">

        {{-- REGISTER FORM --}}
        <div class="form-container sign-up-container">
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                <h1 class="mb-3" style="color: #1e1e2f;">Buat Akun</h1>

                <!-- USERNAME -->
                <input type="text" name="username" id="username" placeholder="Username" required />
                <small id="usernameError" class="text-danger d-block mt-1"></small>

                <!-- PASSWORD -->
                <div class="password-wrapper mt-2">
                    <input type="password" name="password" id="password" placeholder="Password" required
                        autocomplete="new-password" />

                    <i class="fas fa-eye toggle-password" data-target="password"></i>
                </div>
                <small id="passwordError" class="text-danger d-block mt-1"></small>

                <!-- KONFIRMASI PASSWORD -->
                <div class="password-wrapper mt-2">
                    <input type="password" name="confpassword" id="confpassword" placeholder="Konfirmasi Password"
                        required autocomplete="off" />
                    <i class="fas fa-eye toggle-password" data-target="confpassword"></i>
                </div>
                <small id="confpasswordError" class="text-danger d-block mt-1"></small>

                <button type="submit" class="mt-3 w-100">Daftar</button>
            </form>
        </div>

        {{-- LOGIN FORM --}}
        <div class="form-container sign-in-container">
            <form id="loginForm" method="POST">
                @csrf
                <h1 class="mb-3" style="color: #1e1e2f;">Login</h1>

                <div id="loginError" class="alert alert-danger text-center d-none animate__animated animate__headShake"
                    style="font-size: 14px; background-color: #ff4d4d; color: white; border: none;
               border-radius: 0px; padding: 8px 12px; width: 100%; margin-bottom: 8px;">
                </div>

                <input type="text" id="login-username" name="username" placeholder="Username" required />
                <div class="password-wrapper">
                    <input type="password" name="password" id="login-password" placeholder="Password" required />
                    <i class="fas fa-eye toggle-password" data-target="login-password"></i>
                </div>

                <button type="submit" class="mt-3 w-100">Masuk</button>
            </form>

        </div>



        {{-- OVERLAY --}}
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Selamat Datang Kembali!</h1>
                    <p>Sudah punya akun?</p>
                    <button class="ghost" id="signIn">Masuk</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Halo, Selamat Datang!</h1>
                    <p>Belum punya akun? Daftar sekarang!</p>
                    <button class="ghost" id="signUp">Daftar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('js/core/popper.min.js') }}"></script>
    <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-notify.js') }}"></script>
    <script src="{{ asset('js/black-dashboard.min.js') }}"></script>
    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.chartjs.org/samples/latest/utils.js"></script>
    <script>
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function () {
                const target = document.getElementById(this.dataset.target);
                const type = target.getAttribute('type') === 'password' ? 'text' : 'password';
                target.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const username = document.getElementById("username");
            const password = document.getElementById("password");
            const confpassword = document.getElementById("confpassword");

            const usernameError = document.getElementById("usernameError");
            const passwordError = document.getElementById("passwordError");
            const confpasswordError = document.getElementById("confpasswordError");

            // 🔹 Cek Username Real-time via AJAX
            username.addEventListener("input", function () {
                const value = this.value.trim();
                if (value === "") {
                    usernameError.textContent = "Username wajib diisi.";
                    return;
                }

                fetch("{{ route('check.username') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ username: value })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            usernameError.textContent = "Username sudah terdaftar.";
                        } else {
                            usernameError.textContent = "";
                        }
                    });
            });

            // 🔹 Validasi Password Minimal 8 Karakter
            password.addEventListener("input", function () {
                if (password.value.length < 8) {
                    passwordError.textContent = "Password minimal 8 karakter.";
                } else {
                    passwordError.textContent = "";
                }

                // Perbarui validasi konfirmasi juga
                if (confpassword.value && confpassword.value !== password.value) {
                    confpasswordError.textContent = "Konfirmasi password tidak cocok.";
                } else {
                    confpasswordError.textContent = "";
                }
            });

            // 🔹 Validasi Konfirmasi Password
            confpassword.addEventListener("input", function () {
                if (confpassword.value !== password.value) {
                    confpasswordError.textContent = "Konfirmasi password tidak cocok.";
                } else {
                    confpasswordError.textContent = "";
                }
            });

            // 🔹 Cegah Submit Jika Masih Ada Error
            document.getElementById("registerForm").addEventListener("submit", function (e) {
                if (usernameError.textContent || passwordError.textContent || confpasswordError.textContent) {
                    e.preventDefault();
                }
            });
        });
    </script>
    <script>
        document.getElementById("loginForm").addEventListener("submit", function (e) {
            e.preventDefault();

            const username = document.getElementById("login-username").value.trim();
            const password = document.getElementById("login-password").value.trim();
            const errorDiv = document.getElementById("loginError");

            // Hapus pesan sebelumnya
            errorDiv.classList.add("d-none");
            errorDiv.textContent = "";

            fetch("{{ route('check.login') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ username, password })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect; // redirect ke dashboard
                    } else {
                        errorDiv.textContent = data.message;
                        errorDiv.classList.remove("d-none");
                    }
                })
                .catch(() => {
                    errorDiv.textContent = "Terjadi kesalahan pada server.";
                    errorDiv.classList.remove("d-none");
                });
        });
    </script>
    @if(session('logout_success'))
        <script>
            Swal.fire({
                title: 'Berhasil Logout!',
                text: 'Anda telah keluar dari sistem.',
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK',
                timer: 2500,
                timerProgressBar: true,
            });
        </script>
    @endif



    @stack('js')
</body>

</html>