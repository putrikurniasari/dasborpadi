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
    /* Responsive & Modern Dropdown */
    .filter-kebun-group {
        min-width: 180px;
        max-width: 320px;
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .filter-kebun-label {
        font-weight: 700;
        color: #fff;
        background: #228B22;
        border-radius: 6px;
        padding: 4px 12px;
        margin-bottom: 0;
        font-size: 1rem;
        letter-spacing: 0.5px;
        box-shadow: 0 1px 4px rgba(34,139,34,0.08);
    }
    .filter-kebun-select {
        width: 170px;
        font-size: 1rem;
        font-weight: 600;
        color: #228B22;
        background: #f8f9fa;
        border-radius: 8px;
        border: 2px solid #228B22;
        box-shadow: 0 2px 8px rgba(34,139,34,0.07);
        padding: 6px 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }
    .filter-kebun-select:focus {
        border-color: #145214;
        box-shadow: 0 0 0 2px #b6e7c9;
    }
    @media (max-width: 600px) {
        .filter-kebun-group {
            flex-direction: column;
            align-items: flex-end;
            width: 100%;
        }
        .filter-kebun-label {
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
            function loadChartAndTable() {
                $.getJSON('/api/realisasi-padi-umkm', function(data) {
                    const bulanNama = [
                        '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];
                    const tahun = 2025;
                    const labels = [];
                    const values = [];
                    for (let i = 1; i <= 8; i++) {
                        labels.push(bulanNama[i] + ' ' + tahun);
                        const found = data.find(item => Number(item.bulan) === i && Number(item.tahun) === tahun);
                        values.push(found ? Number(found.realisasi_sd_bulan) : 0);
                    }
                    const ctx = document.getElementById('chartBig1').getContext('2d');
                    if(window.chartBig1Instance) window.chartBig1Instance.destroy();
                    window.chartBig1Instance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Realisasi SD Bulan',
                                data: values,
                                backgroundColor: 'rgba(72,72,176,0.5)',
                                borderColor: 'rgba(72,72,176,1)',
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
                                    title: { display: true, text: 'Realisasi SD Bulan' },
                                    suggestedMax: Math.max(...values) * 1.2,
                                    min: 0,
                                    beginAtZero: true,
                                    ticks: { callback: function(value) { return value.toLocaleString(); } }
                                }
                            }
                        }
                    });


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