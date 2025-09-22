
@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center filter-header flex-column">
                        <div class="w-100">
                            <h5 class="card-category mb-0">Total Target & Realisasi Anggaran</h5>
                            <h2 class="card-title mb-0" id="cardTitle">Realisasi Padi UMKM</h2>
                        </div>
                        <!-- Tombol di bawah judul -->
                        <div class="mt-2 w-100">
                            <button id="btnKembaliChart" class="btn btn-sm btn-primary" style="display:none;">
                                Kembali ke Chart UMKM
                            </button>
                        </div>
                        <!-- Filter tetap di bawah tombol -->
                        <div class="filter-tipegrafik-group mt-2" id="tipeGrafikWrapper">
                            <label for="filterTipeGrafik" class="filter-tipegrafik-label">Tipe Grafik:</label>
                            <select id="filterTipeGrafik" class="filter-tipegrafik-select">
                                <option value="" hidden selected>Pilih Tipe Grafik</option>
                                <option value="perbandingan">Perbandingan Target & Realisasi</option>
                                <option value="realisasi">Realisasi</option>
                                <option value="target">Target</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartBig1"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card bawah tetap sama -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center filter-header">
                        <div>
                            <h5 class="card-category mb-0">Total Transaksi Padi</h5>
                            <h2 class="card-title mb-0">Transaksi Padi per Kebun</h2>
                        </div>
                        <div class="form-group mb-0 filter-kebun-group">
                            <label for="filterKebun" class="filter-kebun-label">Filter Kebun:</label>
                            <select id="filterKebun" class="filter-kebun-select">
                                <option value="" hidden selected>Pilih Kebun</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartPembelianPadi"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
