@extends('layouts.app', ['pageSlug' => 'dashboard'])
<x-slot:title>{{$title}}</x-slot:title>

@php
    function sortButtons($column)
    {
        $base = '?sort_by=' . $column . '&search=' . request('search');

        return "
                    <span class='sort-icons'>
                        <a href=\"{$base}&sort_order=asc\" class=\"sort-btn\">▲</a>
                        <a href=\"{$base}&sort_order=desc\" class=\"sort-btn\">▼</a>
                    </span>
                ";
    }
@endphp



@section('content')

    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Dashboard Padi</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-chart animate__animated animate__fadeInUp">
                    <div class="card-header">
                        <h5 class="card-category mb-0">Data Realisasi Padi UMKM</h5>
                        <h2 class="card-title mb-0">Tabel Realisasi Anggaran</h2>
                    </div>

                    <div class="row justify-content-center mt-4">
                        <div class="col-sm-8">
                            <input type="text" id="search" class="form-control mb-4" placeholder="search . . "
                                value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Tahun {!! sortButtons('tahun') !!}</th>
                                    <th>Bulan {!! sortButtons('bulan') !!}</th>
                                    <th>Target Tahun {!! sortButtons('target_tahun') !!}</th>
                                    <th>Target s/d Bulan {!! sortButtons('target_sd_bulan') !!}</th>
                                    <th>Realisasi s/d Bulan {!! sortButtons('realisasi_sd_bulan') !!}</th>
                                    <th>Sisa Target {!! sortButtons('sisa_target') !!}</th>
                                    <th>Selisih (Rp) {!! sortButtons('selisih_rp') !!}</th>
                                    <th>% Capaian {!! sortButtons('persentase_capaian') !!}</th>
                                </tr>


                            </thead>
                            <tbody class="text-center">
                                @foreach($dataRealisasi as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->tahun }}</td>
                                        <td>{{ \Carbon\Carbon::create()->month($row->bulan)->translatedFormat('F') }}</td>
                                        <td>{{ number_format($row->target_tahun, 0, ',', '.') }}</td>
                                        <td>{{ number_format($row->target_sd_bulan, 0, ',', '.') }}</td>
                                        <td>{{ number_format($row->realisasi_sd_bulan, 0, ',', '.') }}</td>
                                        <td>{{ number_format($row->sisa_target, 0, ',', '.') }}</td>
                                        <td>{{ number_format($row->selisih_rp, 0, ',', '.') }}</td>
                                        <td>{{ $row->persentase_capaian }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $dataRealisasi->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

        <!-- 🔹 Card bawah -->
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
                                <!-- Filter Tahun -->
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
    </div>
@endsection

<style>
    th {
        position: relative;
        white-space: nowrap; /* supaya tidak turun baris */
    }

    .sort-icons {
        font-size: 9px;
        margin-left: 4px;
        display: inline-flex;
        flex-direction: column;
        line-height: 9px;
        vertical-align: middle;
        opacity: 0.6;
    }

    .sort-btn {
        color: inherit !important;
        text-decoration: none;
        cursor: pointer;
    }

    .sort-btn:hover {
        opacity: 1;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 🔹 1. Cegah notifikasi demo dari Black Dashboard
        if (typeof demo !== 'undefined') {
            demo.showNotification = function () { };
        }

        // 🔹 2. Cegah notifikasi dari bootstrap-notify
        if ($.notify) {
            $.notify = function () { };
        }

        // 🔹 3. Hilangkan notifikasi yang muncul
        setTimeout(() => {
            document.querySelectorAll('.alert.alert-warning, .alert').forEach(el => {
                if (el.innerText.includes('Change your password') || el.innerText.includes('notifikasi')) {
                    el.remove();
                }
            });
        }, 500);

        // 🔹 4. Awasi DOM jika notifikasi muncul lagi
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
                position: "center",
                background: "#1e1e2f",
                color: "#fff",
            });
        });
    </script>
@endif