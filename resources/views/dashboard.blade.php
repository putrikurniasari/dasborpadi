@extends('layouts.app', ['pageSlug' => 'dashboard'])
<x-slot:title>{{$title}}</x-slot:title>
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-chart animate__animated animate__fadeInUp">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center filter-header flex-column">
                        <div class="w-100">
                            <h5 class="card-category mb-0" id="atas_title">Total Target & Realisasi Anggaran</h5>
                            <h2 class="card-title mb-0" id="cardTitle">Realisasi Padi UMKM</h2>
                        </div>

                        <!-- Tombol kembali -->
                        <div class="mt-2 w-100">
                            <button id="btnKembaliChart" class="btn btn-sm btn-outline-secondary">
                                <i class="fa fa-arrow-left"></i>
                            </button>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" id="chartBig1Wrapper">
                        <canvas id="chartBig1"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card bawah -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-chart animate__animated animate__fadeInUp">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center filter-header w-100">

                        <div>
                            <h5 class="card-category mb-0" id="atasjudul">Total Transaksi Padi</h5>
                            <h2 class="card-title mb-0" id="cardTitle2">Transaksi Padi per Kebun</h2>
                        </div>
                        <div class="d-flex gap-3 align-items-center">
                            <!-- Filter Tahun (kanan atas) -->
                            <div class="filter-tahun-group mb-0" id="tahunWrapper2">
                                <label for="filterTahun2" class="filter-tahun-label">Tahun:</label>
                                <select id="filterTahun2" class="filter-tahun-select">
                                    <option value="" hidden selected>Pilih Tahun</option>
                                </select>
                            </div>

                            <!-- Filter Kebun -->
                            <div class="filter-kebun-group mb-0">
                                <label for="filterKebun" class="filter-kebun-label">Kebun:</label>
                                <select id="filterKebun" class="filter-kebun-select">
                                    <option value="" hidden selected>Pilih Kebun</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Tombol kembali -->
                    <div class="mt-2 w-100">
                        <button id="btnKembaliChart2" class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-arrow-left"></i>
                        </button>

                    </div>
                </div>
                <div class="card-body" id="chartPembelianWrapper">
                    <div class="chart-area">
                        <canvas id="chartPembelianPadi"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 🔹 1. Cegah notifikasi demo dari Black Dashboard
        if (typeof demo !== 'undefined') {
            demo.showNotification = function () { }; // kosongkan fungsi
        }

        // 🔹 2. Cegah notifikasi dari bootstrap-notify (kadang muncul otomatis)
        if ($.notify) {
            $.notify = function () { }; // matikan semua notify
        }

        // 🔹 3. Hilangkan elemen notifikasi yang sempat muncul (jika sudah dirender)
        setTimeout(() => {
            document.querySelectorAll('.alert.alert-warning, .alert').forEach(el => {
                if (el.innerText.includes('Change your password') || el.innerText.includes('notifikasi')) {
                    el.remove();
                }
            });
        }, 500);

        // 🔹 4. Awasi DOM, hapus notifikasi jika muncul setelah delay
        const observer = new MutationObserver(() => {
            document.querySelectorAll('.alert.alert-warning, .alert').forEach(el => {
                if (el.innerText.includes('Change your password') || el.innerText.includes('notifikasi')) {
                    el.remove();
                }
            });
        });

        observer.observe(document.body, { childList: true, subtree: true });
    });
</script>

@if (session('login_success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: "Login Berhasil 🎉",
                text: "Selamat datang kembali, {{ Auth::user()->username }}!",
                icon: "success",
                confirmButtonText: "Lanjut",
                timer: 20000,
                timerProgressBar: true,
                position: "center", // ✅ muncul di tengah layar
                background: "#1e1e2f",
                color: "#fff",
            });
        });
    </script>
@endif