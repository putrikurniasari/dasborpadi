@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h5 class="card-category">Kinerja Tahun 2025</h5>
                            <h2 class="card-title">Realisasi Padi UMKM Perbulan</h2>
                        </div>
                        <div class="col-sm-6 d-flex align-items-center justify-content-end">
                            <div class="filter-tipegrafik-group">
                                <label for="filterTipeGrafik" class="filter-tipegrafik-label">Tipe Grafik:</label>
                                <select id="filterTipeGrafik" class="filter-tipegrafik-select">
                                    <option value="perbandingan">Perbandingan Target & Realisasi</option>
                                    <option value="realisasi">Realisasi</option>
                                    <option value="target">Target</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <input type="text" class="form-control form-control-sm" placeholder="Input" style="width: 120px; display: inline-block; margin-left: 10px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="width:100%; min-height:300px;">
                        <canvas id="chartBig1" style="width:100% !important; height:350px !important;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center" style="flex-wrap:wrap;">
                        <div>
                            <h5 class="card-category mb-0">Total Transaksi Padi</h5>
                            <h2 class="card-title mb-0">Transaksi Padi per Kebun</h2>
                        </div>
                        <div class="form-group mb-0 filter-kebun-group">
                            <label for="filterKebun" class="filter-kebun-label">Filter Kebun:</label>
                            <select id="filterKebun" class="filter-kebun-select">
                                <option value="">-- Pilih Kebun --</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="width:100%; min-height:300px;">
                        <canvas id="chartPembelianPadi" style="width:100% !important; height:350px !important;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .filter-tipegrafik-group, .filter-kebun-group {
    min-width: 150px;
    max-width: 220px;
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 6px;
    background: #23243a;
    border-radius: 10px;
    padding: 4px 10px;
    box-shadow: 0 2px 8px 0 rgba(94,114,228,0.10), 0 1px 2px 0 rgba(0,0,0,0.08);
    border: 1px solid #35376c;
    position: relative;
    }
    .filter-tipegrafik-label, .filter-kebun-label {
        font-weight: 600;
        color: #a3aed6;
        background: transparent;
        border-radius: 6px;
        padding: 0 0.5rem 0 0;
        margin-bottom: 0;
        font-size: 1rem;
        letter-spacing: 0.5px;
        border: none;
        box-shadow: none;
    }
    .filter-tipegrafik-select, .filter-kebun-select {
    width: 110px;
    font-size: 0.85rem;
    font-weight: 500;
    border-radius: 7px;
    border: 1px solid #7f83b7;
    background: #23243a;
    color: #fff;
    padding: 5px 10px;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-shadow: 0 1px 4px rgba(94,114,228,0.07);
    appearance: none;
    outline: none;
    cursor: pointer;
    position: relative;
    }
    .filter-tipegrafik-select:focus, .filter-kebun-select:focus {
        border-color: #e14eca;
        box-shadow: 0 0 0 2px #e14eca33;
        outline: none;
    }
    .filter-tipegrafik-select option, .filter-kebun-select option {
        color: #23243a;
        background: #fff;
        font-weight: 500;
    font-size: 0.85rem;
        border-radius: 0;
        padding: 8px 16px;
    }
    .filter-tipegrafik-select::-ms-expand, .filter-kebun-select::-ms-expand {
        display: none;
    }

    .filter-tipegrafik-group select, .filter-kebun-group select {
        background-image: url('data:image/svg+xml;utf8,<svg fill="%23a3aed6" height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7.293 7.293a1 1 0 011.414 0L10 8.586l1.293-1.293a1 1 0 111.414 1.414l-2 2a1 1 0 01-1.414 0l-2-2a1 1 0 010-1.414z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.2em;
    }
    @media (max-width: 600px) {
        .filter-kebun-group, .filter-tipegrafik-group {
            flex-direction: column;
            align-items: flex-end;
            width: 100%;
        }
        .filter-kebun-label, .filter-tipegrafik-label {
            margin-bottom: 4px;
            font-size: 0.98rem;
        }
        .filter-kebun-select {
            width: 100%;
            font-size: 0.98rem;
        }
    }
</style>
@push('js')
    <script src="{{ asset('js/plugins/chartjs.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            let cacheRealisasiData = null;
            function renderChartBig1(tipe) {
                if (!cacheRealisasiData) return;
                const data = cacheRealisasiData;
                const bulanNama = [
                    '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                const tahun = 2025;
                const labels = [];
                const targetSdBulan = [];
                const realisasiSdBulan = [];
                for (let i = 1; i <= 8; i++) {
                    labels.push(bulanNama[i] + ' ' + tahun);
                    const found = data.find(item => Number(item.bulan) === i && Number(item.tahun) === tahun);
                    targetSdBulan.push(found ? Number(found.target_sd_bulan) : 0);
                    realisasiSdBulan.push(found ? Number(found.realisasi_sd_bulan) : 0);
                }
                let datasets = [];
                if (tipe === 'perbandingan') {
                    datasets = [
                        {
                            label: 'Target SD Bulan',
                            data: targetSdBulan,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2
                        },
                        {
                            label: 'Realisasi SD Bulan',
                            data: realisasiSdBulan,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2
                        }
                    ];
                } else if (tipe === 'realisasi') {
                    datasets = [
                        {
                            label: 'Realisasi SD Bulan',
                            data: realisasiSdBulan,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2
                        }
                    ];
                } else if (tipe === 'target') {
                    datasets = [
                        {
                            label: 'Target SD Bulan',
                            data: targetSdBulan,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2
                        }
                    ];
                }
                const ctx = document.getElementById('chartBig1').getContext('2d');
                if(window.chartBig1Instance) window.chartBig1Instance.destroy();
                window.chartBig1Instance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        indexAxis: 'x',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: true } },
                        scales: {
                            x: { title: { display: true, text: 'Bulan' }, ticks: { autoSkip: false, maxRotation: 45, minRotation: 0 } },
                            y: {
                                title: { display: true, text: 'Nilai (Rp)' },
                                suggestedMax: Math.max(...targetSdBulan, ...realisasiSdBulan) * 1.2,
                                min: 0,
                                beginAtZero: true,
                                ticks: { callback: function(value) { return value.toLocaleString(); } }
                            }
                        }
                    }
                });
            }
            function loadChartAndTable() {
                $.getJSON('/api/realisasi-padi-umkm', function(data) {
                    cacheRealisasiData = data;
                    const tipe = $('#filterTipeGrafik').val() || 'perbandingan';
                    renderChartBig1(tipe);


                    let tbody = '';
                    data.forEach(function(item) {
                        let b = Number(item.bulan);
                        let namaBulan = (b >= 1 && b <= 12) ? bulanNama[b] : item.bulan;
                        tbody += `<tr>
                            <td>${namaBulan + ' ' + item.tahun}</td>
                            <td>${item.realisasi_bulanan}</td>
                        </tr>`;
                    });
                    $('#tabel-bulan-realisasi tbody').html(tbody);
                });
            }
            loadChartAndTable();
            $('#filterTipeGrafik').on('change', function() {
                const tipe = $(this).val();
                renderChartBig1(tipe);
            });
            let pembelianPadiData = [];
            let kebunList = [];
            function renderKebunDropdown() {
                const $dropdown = $('#filterKebun');
                $dropdown.empty();
                $dropdown.append('<option value="">-- Pilih Kebun --</option>');
                kebunList.forEach(kebun => {
                    $dropdown.append(`<option value="${kebun}">${kebun}</option>`);
                });
            }
            function renderPembelianChart(kebunFilter) {
                let filtered = pembelianPadiData;
                if (kebunFilter) {
                    filtered = pembelianPadiData.filter(item => item.deskripsi === kebunFilter);
                }
                const bulanNama = [ '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember' ];
                const tahun = 2025;
                const labels = [];
                const values = [];
                for (let i = 1; i <= 8; i++) {
                    labels.push(bulanNama[i] + ' ' + tahun);
                    const found = filtered.find(item => Number(item.bulan) === i && Number(item.tahun) === tahun);
                    values.push(found ? Number(found.transaksi_padi) : 0);
                }
                const ctx2 = document.getElementById('chartPembelianPadi').getContext('2d');
                if(window.chartPembelianPadiInstance) window.chartPembelianPadiInstance.destroy();
                window.chartPembelianPadiInstance = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: kebunFilter ? `Total Transaksi per Bulan (${kebunFilter})` : 'Total Transaksi per Bulan',
                            data: values,
                            backgroundColor: 'rgba(34,139,34,0.5)',
                            borderColor: 'rgba(34,139,34,1)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        indexAxis: 'x',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: true } },
                        scales: {
                            x: { title: { display: true, text: 'Bulan' }, ticks: { autoSkip: false, maxRotation: 45, minRotation: 0 } },
                            y: {
                                title: { display: true, text: 'Total Transaksi' },
                                suggestedMax: Math.max(...values) * 1.2,
                                min: 0,
                                beginAtZero: true,
                                ticks: { callback: function(value) { return value.toLocaleString(); } }
                            }
                        }
                    }
                });
            }
            $.getJSON('/api/pembelian-padi', function(data) {
                pembelianPadiData = data;
                kebunList = [...new Set(data.map(item => item.deskripsi))].sort();
                renderKebunDropdown();
                renderPembelianChart();
            });
            $(document).on('change', '#filterKebun', function() {
                const kebun = $(this).val();
                renderPembelianChart(kebun);
            });
        });
    </script>
@endpush