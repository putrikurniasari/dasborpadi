@extends('layouts.app', ['pageSlug' => 'dashboard'])

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