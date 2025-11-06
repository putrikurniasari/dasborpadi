@extends('layouts.app', ['pageSlug' => 'dashboard'])
<x-slot:title>{{$title}}</x-slot:title>



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

        <!-- 🔹 Card atas -->
        <div class="row">
            <div class="col-12">
                <div class="card card-chart animate__animated animate__fadeInUp">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center filter-header flex-column">
                            <div class="d-flex w-100 align-items-center justify-content-between">
                                <div>
                                    <h5 class="card-category mb-0" id="atas_title">Total Target & Realisasi Anggaran</h5>
                                    <h2 class="card-title mb-0" id="cardTitle">Realisasi Padi UMKM</h2>
                                </div>

                                <a class="btn btn-outline-info btn-lg" id="btnlihatgrafik" style="border-radius:12px;">
                                    Lihat Grafik
                                </a>
                            </div>


                            <!-- Tombol kembali -->
                            <div class="mt-2 w-100">
                                <button id="btnKembaliChart" class="btn btn-sm btn-outline-secondary"
                                    style="border-radius:12px;">
                                    <i class="fa fa-arrow-left"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center mt-4" id="searchtabel">
                            <div class="col-sm-6">
                                <input type="text" id="search" class="form-control mb-4" placeholder="search . . "
                                    value="{{ request('search') }}">
                            </div>

                            <div class="col-sm-3">
                                <select id="filterTahunAtas" class="form-select mb-4">
                                    <option value="">-- Semua Tahun --</option>
                                    @foreach($tahunList as $th)
                                        <option value="{{ $th->tahun }}">{{ $th->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>


                        <div class="card-body table-responsive" id="chartBig1WrapperDefault">
                            <table class="table table-striped table-hover align-middle group-separator-bt">
                                <thead class="text-center align-middle">
                                    <tr class="group-separator-bt">
                                        <th class="group-separator group-separator-lf " rowspan="2">No</th>
                                        <th class="group-separator" rowspan="2">Tahun</th>
                                        <th rowspan="2" class="group-separator">Bulan</th>
                                        <th colspan="3" class="group-separator">Bulan ini</th>
                                        <th colspan="3" class="group-separator">S.D Bulan ini</th>
                                    </tr>
                                    <tr class="group-separator-bt">
                                        <th class="group-separator">Target</th>
                                        <th class="group-separator">Realisasi</th>
                                        <th class="group-separator">Selisih</th>
                                        <th class="group-separator">Target</th>
                                        <th class="group-separator">Realisasi</th>
                                        <th class="group-separator">Selisih</th>
                                    </tr>
                                </thead>

                                <tbody class="text-center">
                                    @foreach($dataRealisasiAJAX ?? $dataRealisasi as $row)
                                        <tr>
                                            <td class="group-separator-lf group-separator-isi">{{ $loop->iteration }}</td>
                                            <td class="group-separator-isi">{{ $row->tahun }}</td>

                                            <td class="group-separator">
                                                {{ \Carbon\Carbon::create()->locale('id')->month($row->bulan)->translatedFormat('F') }}
                                            </td>

                                            <!-- Bulan ini -->
                                            <td class="group-separator-isi">{{ number_format($row->target_bulan, 0, ',', '.') }}
                                            </td>
                                            <td class="group-separator-isi">
                                                {{ number_format($row->realisasi_bulan, 0, ',', '.') }}
                                            </td>
                                            <td class="group-separator"
                                                style="color:{{ $row->selisih_bulan < 0 ? 'red' : 'green' }}">
                                                {{ number_format($row->selisih_bulan, 0, ',', '.') }}
                                            </td>

                                            <!-- S.D Bulan ini -->
                                            <td class="group-separator-isi">
                                                {{ number_format($row->target_sd_bulan, 0, ',', '.') }}
                                            </td>
                                            <td class="group-separator-isi">
                                                {{ number_format($row->realisasi_sd_bulan, 0, ',', '.') }}
                                            </td>
                                            <td class="group-separator"
                                                style="color:{{ $row->selisih_sd_bulan < 0 ? 'red' : 'green' }}">
                                                {{ number_format($row->selisih_sd_bulan, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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


                        <div class="card-body" id="chartBig2WrapperDefault">
                            <div class="col-sm-12 d-flex justify-content-center mb-3">
                                <input type="text" id="searchPembelian" class="form-control" placeholder="search..." value="{{ request('searchPembelian') }}">
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle group-separator-bt">
                                <thead class="text-center align-middle">
                                    <tr class="group-separator-bt">
                                        <th class="group-separator group-separator-lf" rowspan="2">No</th>

                                        <th class="group-separator" rowspan="2">Tahun</th>
                                        <th rowspan="2" class="group-separator">

                                            <select id="filterBulan" class="form-select" style="min-width:120px;">
                                                @foreach(range(1, 12) as $b)
                                                    <option value="{{ $b }}" {{ (request('filter_bulan') ?? 1) == $b ? 'selected' : '' }}>
                                                        {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                                                    </option>
                                                @endforeach
                                            </select>


                                        </th>

                                        <th rowspan="2" class="group-separator">Nama Kebun</th>
                                        <th colspan="2" class="group-separator">Transaksi</th>
                                        <th rowspan="2" class="group-separator">Plafond OPL</th>
                                        <th rowspan="2" class="group-separator">Persentase Terhadap Plafond</th>
                                    </tr>

                                    <tr class="group-separator-bt">
                                        <th class="group-separator">Padi Bulan Ini</th>
                                        <th class="group-separator">Padi S.D Bulan Ini</th>
                                    </tr>
                                </thead>

                                <tbody class="text-center" id="tbodyPembelian">
                                    @foreach($dataPembelian as $row)
                                        <tr>
                                            <td class="group-separator-lf group-separator-isi">
                                                {{ $loop->iteration }}
                                            </td>

                                            <td class="group-separator-isi">
                                                {{ $row->tahun }}
                                            </td>

                                            <td class="group-separator-isi">
                                                {{ \Carbon\Carbon::create()->locale('id')->month($row->bulan)->translatedFormat('F') }}
                                            </td>

                                            <td class="group-separator">
                                                {{ $row->deskripsi }}
                                            </td>

                                            <!-- Transaksi -->
                                            <td class="group-separator-isi">
                                                {{ number_format($row->transaksi_padi, 0, ',', '.') }}
                                            </td>

                                            <td class="group-separator group-separator-isi">
                                                {{ number_format($row->transaksi_padi_sd, 0, ',', '.') }}
                                            </td>

                                            <!-- Plafond -->
                                            <td class="group-separator-isi">
                                                {{ number_format($row->plafond_opl, 0, ',', '.') }}
                                            </td>

                                            <!-- Persentase -->
                                            <td class="group-separator"
                                            style="color: {{
                                                $row->persen_terhadap_plafond * 100 < 50 ? 'red' :
                                                ($row->persen_terhadap_plafond * 100 < 75 ? 'orange' :
                                                ($row->persen_terhadap_plafond * 100 < 100 ? 'green' : 'green'))
                                            }};">
                                            {{ number_format($row->persen_terhadap_plafond * 100, 0) }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <!-- Tombol kembali -->
                        <div class="mt-2 w-100">
                            <button id="btnKembaliChart2" class="btn btn-sm btn-outline-secondary"
                                style="border-radius:12px;">
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
    /* Garis tebal pembatas antara 2 grup kolom */

    th.group-separator-isi,
    td.group-separator-isi {
        border-right: 1px solid #000000ff !important;
    }

    th.group-separator,
    td.group-separator {
        border-right: 2px solid #000 !important;
    }

    th.group-separator-lf,
    td.group-separator-lf {
        border-left: 2px solid #000 !important;
    }

    table.group-separator-bt,
    tr.group-separator-bt,
    th.group-separator-bt,
    td.group-separator-bt {
        border-bottom: 2px solid #000 !important;
        border-top: 2px solid #000 !important;
    }

    /* Rapikan border tabel */
    table {
        border-collapse: collapse;
    }
</style>

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

<script>
document.addEventListener("DOMContentLoaded", () => {

    /* ===============================
       SEARCH REALISASI (TABEL ATAS)
    ================================ */

    const searchInput = document.getElementById("search");
    const tbodyAtas = document.querySelector("#chartBig1WrapperDefault tbody");

    if (searchInput && tbodyAtas) {
        searchInput.addEventListener("keyup", () => {
            const keyword = searchInput.value.trim();

            fetch(`/ajax/search-realisasi?search=${keyword}`)
                .then(res => res.json())
                .then(data => {

                    tbodyAtas.innerHTML = "";
                    let no = 1;

                    data.forEach(row => {
                        tbodyAtas.innerHTML += `
                            <tr>
                                <td class="group-separator-lf group-separator-isi">${no++}</td>
                                <td class="group-separator-isi">${row.tahun}</td>
                                <td class="group-separator">
                                    ${new Date(2000, row.bulan - 1).toLocaleString('id', { month: 'long' })}
                                </td>
                                <td class="group-separator-isi">${Intl.NumberFormat('id-ID').format(row.target_bulan)}</td>
                                <td class="group-separator-isi">${Intl.NumberFormat('id-ID').format(row.realisasi_bulan)}</td>
                                <td class="group-separator" style="color:${row.selisih_bulan < 0 ? 'red' : 'green'}">
                                    ${Intl.NumberFormat('id-ID').format(row.selisih_bulan)}
                                </td>
                                <td class="group-separator-isi">${Intl.NumberFormat('id-ID').format(row.target_sd_bulan)}</td>
                                <td class="group-separator-isi">${Intl.NumberFormat('id-ID').format(row.realisasi_sd_bulan)}</td>
                                <td class="group-separator" style="color:${row.selisih_sd_bulan < 0 ? 'red' : 'green'}">
                                    ${Intl.NumberFormat('id-ID').format(row.selisih_sd_bulan)}
                                </td>
                            </tr>
                        `;
                    });

                });
        });
    }

    /* ===============================
       FILTER TAHUN (TABEL ATAS)
    ================================ */

    const filterTahunAtas = document.getElementById("filterTahunAtas");

    if (filterTahunAtas && tbodyAtas) {
        filterTahunAtas.addEventListener("change", () => {

            const tahun = filterTahunAtas.value;

            const url = tahun === "" ? `/ajax/realisasi` : `/ajax/realisasi?tahun=${tahun}`;

            if (tahun === "") {
                document.location.href = document.location.pathname;
                return;
            }

            fetch(url)
                .then(res => res.json())
                .then(data => {

                    tbodyAtas.innerHTML = "";
                    let no = 1;

                    data.forEach(row => {
                        tbodyAtas.innerHTML += `
                            <tr>
                                <td class="group-separator-lf group-separator-isi">${no++}</td>
                                <td class="group-separator-isi">${row.tahun}</td>
                                <td class="group-separator">
                                    ${new Date(2000, row.bulan - 1).toLocaleString('id', { month: 'long' })}
                                </td>
                                <td class="group-separator-isi">${Intl.NumberFormat('id-ID').format(row.target_bulan)}</td>
                                <td class="group-separator-isi">${Intl.NumberFormat('id-ID').format(row.realisasi_bulan)}</td>
                                <td class="group-separator" style="color:${row.selisih_bulan < 0 ? 'red' : 'green'}">
                                    ${Intl.NumberFormat('id-ID').format(row.selisih_bulan)}
                                </td>
                                <td class="group-separator-isi">${Intl.NumberFormat('id-ID').format(row.target_sd_bulan)}</td>
                                <td class="group-separator-isi">${Intl.NumberFormat('id-ID').format(row.realisasi_sd_bulan)}</td>
                                <td class="group-separator" style="color:${row.selisih_sd_bulan < 0 ? 'red' : 'green'}">
                                    ${Intl.NumberFormat('id-ID').format(row.selisih_sd_bulan)}
                                </td>
                            </tr>
                        `;
                    });

                });
        });
    }

    /* ===============================
       SEARCH PEMBELIAN (TABEL BAWAH)
    ================================ */
    const searchPembelian = document.getElementById("searchPembelian");
    const tbodyPembelian = document.getElementById("tbodyPembelian");

    if (searchPembelian && tbodyPembelian) {
        searchPembelian.addEventListener("keyup", () => {
            const keyword = searchPembelian.value.trim();

            fetch(`/ajax/search-pembelian?search=${keyword}`)
                .then(res => res.json())
                .then(data => {

                    tbodyPembelian.innerHTML = "";
                    let no = 1;

                    data.forEach(row => {
                        tbodyPembelian.innerHTML += `
                            <tr>
                                <td class="group-separator-lf group-separator-isi">${no++}</td>
                                <td class="group-separator-isi">${row.tahun}</td>
                                <td class="group-separator-isi">
                                    ${new Date(2000, row.bulan - 1).toLocaleString('id', { month: 'long' })}
                                </td>
                                <td class="group-separator-isi">${row.deskripsi}</td>
                                <td class="group-separator-isi">${Intl.NumberFormat('id-ID').format(row.transaksi_padi)}</td>
                                <td class="group-separator-isi">${Intl.NumberFormat('id-ID').format(row.transaksi_padi_sd)}</td>
                                <td class="group-separator-isi">${Intl.NumberFormat('id-ID').format(row.plafond_opl)}</td>
                                <td class="group-separator"
                                    style="color:${
                                        row.persen_terhadap_plafond * 100 < 50 ? 'red' :
                                        row.persen_terhadap_plafond * 100 < 75 ? 'orange' :
                                        'green'
                                    }">
                                    ${Math.round(row.persen_terhadap_plafond * 100)}%
                                </td>
                            </tr>
                        `;
                    });

                });
        });
    }

    /* ===============================
       FILTER BULAN (TABEL BAWAH)
    ================================ */

    const filterBulan = document.getElementById("filterBulan");

    if (filterBulan && tbodyPembelian) {
        filterBulan.addEventListener("change", () => {

            const bulan = filterBulan.value;

            fetch(`/ajax/pembelian?filter_bulan=${bulan}`)
                .then(res => res.json())
                .then(data => {

                    tbodyPembelian.innerHTML = "";
                    let no = 1;

                    data.forEach(row => {

                        tbodyPembelian.innerHTML += `
                            <tr>
                                <td class="group-separator-lf group-separator-isi">${no++}</td>
                                <td class="group-separator-isi">${row.tahun}</td>
                                <td class="group-separator-isi">
                                    ${new Date(2000, row.bulan - 1).toLocaleString('id', { month: 'long' })}
                                </td>
                                <td class="group-separator-isi">${row.deskripsi}</td>
                                <td class="group-separator-isi">${Intl.NumberFormat('id-ID').format(row.transaksi_padi)}</td>
                                <td class="group-separator-isi">${Intl.NumberFormat('id-ID').format(row.transaksi_padi_sd)}</td>
                                <td class="group-separator-isi">${Intl.NumberFormat('id-ID').format(row.plafond_opl)}</td>
                                <td class="group-separator"
                                    style="color:${
                                        row.persen_terhadap_plafond * 100 < 50 ? 'red' :
                                        row.persen_terhadap_plafond * 100 < 75 ? 'orange' :
                                        'green'
                                    }">
                                    ${Math.round(row.persen_terhadap_plafond * 100)}%
                                </td>
                            </tr>
                        `;
                    });

                });
        });
    }

});
</script>
