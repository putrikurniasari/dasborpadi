import './bootstrap';
import Chart from 'chart.js/auto';
import $ from 'jquery';

let chartBig1Instance = null;
let cacheRealisasiData = null;
let pembelianPadiData = [];
let selectedTahun = null;

// ========================
// Render Dropdown Tahun
// ========================
function renderTahunDropdown() {
    const $dropdown = $('#filterTahun');
    $dropdown.empty().append('<option value="" hidden selected>Pilih Tahun</option>');

    if (!cacheRealisasiData || cacheRealisasiData.length === 0) return;

    const tahunList = [...new Set(cacheRealisasiData.map(item => Number(item.tahun)))]
        .sort((a, b) => b - a);

    tahunList.forEach(th => {
        $dropdown.append(`<option value="${th}">${th}</option>`);
    });

    // set default: tahun terbaru
    selectedTahun = tahunList[0];
    $dropdown.val(selectedTahun);
}

// ========================
// Chart Big 1 - Realisasi UMKM
// ========================
function renderChartBig1(tipe) {
    if (!cacheRealisasiData || !selectedTahun) return;

    const data = cacheRealisasiData;
    const bulanNama = [
        '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    const labels = [];
    const targetSdBulan = [];
    const realisasiSdBulan = [];

    for (let i = 1; i <= 8; i++) {
        labels.push(bulanNama[i] + ' ' + selectedTahun);
        const found = data.find(item => Number(item.bulan) === i && Number(item.tahun) === selectedTahun);
        targetSdBulan.push(found ? Number(found.target_sd_bulan) : 0);
        realisasiSdBulan.push(found ? Number(found.realisasi_sd_bulan) : 0);
    }

    let datasets = [];
    if (tipe === 'perbandingan') {
        datasets = [
            { label: 'Target SD Bulan', data: targetSdBulan, backgroundColor: 'rgba(54, 162, 235, 0.5)', borderColor: 'rgba(54, 162, 235, 1)', borderWidth: 2 },
            { label: 'Realisasi SD Bulan', data: realisasiSdBulan, backgroundColor: 'rgba(255, 99, 132, 0.5)', borderColor: 'rgba(255, 99, 132, 1)', borderWidth: 2 }
        ];
    } else if (tipe === 'realisasi') {
        datasets = [
            { label: 'Realisasi SD Bulan', data: realisasiSdBulan, backgroundColor: 'rgba(255, 99, 132, 0.5)', borderColor: 'rgba(255, 99, 132, 1)', borderWidth: 2 }
        ];
    } else if (tipe === 'target') {
        datasets = [
            { label: 'Target SD Bulan', data: targetSdBulan, backgroundColor: 'rgba(54, 162, 235, 0.5)', borderColor: 'rgba(54, 162, 235, 1)', borderWidth: 2 }
        ];
    }

    const ctx = document.getElementById('chartBig1');
    if (!ctx) return;
    if (chartBig1Instance) chartBig1Instance.destroy();

    chartBig1Instance = new Chart(ctx, {
        type: 'bar',
        data: { labels: labels, datasets: datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true }
            },
            scales: {
                x: {
                    title: { display: true, text: 'Bulan' },
                    ticks: { autoSkip: false, maxRotation: 45, minRotation: 0 }
                },
                y: {
                    title: { display: true, text: 'Nilai (Rp)' },
                    suggestedMax: Math.max(...targetSdBulan, ...realisasiSdBulan) * 1.2,
                    beginAtZero: true
                }
            },
            onClick: (evt, elements) => {
                if (elements.length > 0) {
                    const index = elements[0].index;
                    const bulan = index + 1;
                    renderChartPerKebunInSameCard(bulan, selectedTahun);
                }
            }
        },
        plugins: [{
            id: 'customLabels',
            afterDatasetsDraw(chart) {
                const { ctx } = chart;
                ctx.save();
                ctx.font = 'bold 12px sans-serif';
                ctx.fillStyle = '#ffffffff'; // angka selalu hitam
                ctx.textAlign = 'center';

                chart.data.datasets.forEach((dataset, i) => {
                    if (!chart.isDatasetVisible(i)) return;

                    const meta = chart.getDatasetMeta(i);
                    meta.data.forEach((bar, index) => {
                        const value = dataset.data[index];
                        if (value > 0) {
                            const formatted = value.toLocaleString('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            });
                            ctx.fillText(formatted, bar.x, bar.y - 5);
                        }
                    });
                });

                ctx.restore();
            }
        }]
    });

    $('#tipeGrafikWrapper').show();
    $('#btnKembaliChart').hide();
    $('#cardTitle').text('Realisasi Padi UMKM');
    $('#chartBig1').parent().css('height', '400px');
}

// ========================
// Chart detail per kebun
// ========================
function renderChartPerKebunInSameCard(bulan, tahun) {
    if (!pembelianPadiData.length) return;

    const filtered = pembelianPadiData.filter(item => Number(item.bulan) === bulan && Number(item.tahun) === tahun);

    const labels = filtered.map(item => item.deskripsi);
    const plafond = filtered.map(item => Number(item.plafond_opl));
    const transaksi = filtered.map(item => Number(item.transaksi_padi));

    const ctx = document.getElementById('chartBig1');
    if (!ctx) return;
    if (chartBig1Instance) chartBig1Instance.destroy();

    const cardHeight = Math.max(500, labels.length * 50);
    $('#chartBig1').css({ 'height': cardHeight + 'px', 'max-height': 'none' });
    $('#chartBig1').parent().css({ 'height': cardHeight + 'px', 'max-height': 'none' });

    chartBig1Instance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label: 'Plafond OPL', data: plafond, backgroundColor: 'rgba(54,162,235,0.7)', borderColor: 'rgba(54,162,235,1)', borderWidth: 1 },
                { label: 'Transaksi Padi', data: transaksi, backgroundColor: 'rgba(255,99,132,0.7)', borderColor: 'rgba(255,99,132,1)', borderWidth: 1 }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    ticks: {
                        font: { size: 14 },
                        autoSkip: false,
                        callback: function (value) {
                            let label = this.getLabelForValue(value);
                            return label.match(/.{1,12}/g);
                        }
                    },
                    grid: { display: false },
                    barPercentage: 0.4,
                    categoryPercentage: 0.6
                },
                x: {
                    beginAtZero: true,
                    ticks: { font: { size: 14 }, padding: 10 },
                    grid: { drawBorder: true, drawTicks: true, color: '#ccc' }
                }
            },
            plugins: { legend: { display: true }, tooltip: { mode: 'index', intersect: false } }
        }
    });

    $('#cardTitle').text(`Transaksi per Kebun Bulan ${bulan} ${tahun}`);
    $('#btnKembaliChart').show();
    $('#tipeGrafikWrapper').hide();
}

// ========================
// Tombol kembali
// ========================
$('#btnKembaliChart').on('click', function () {
    const tipe = $('#filterTipeGrafik').val() || 'perbandingan';
    renderChartBig1(tipe);
});

// ========================
// Chart Pembelian Padi (card bawah)
// ========================
let chartPembelianPadiInstance = null;
let kebunList = [];

function renderKebunDropdown() {
    const $dropdown = $('#filterKebun');
    $dropdown.empty().append('<option value="" hidden selected>Pilih Kebun</option>');
    kebunList.forEach(kebun => $dropdown.append(`<option value="${kebun}">${kebun}</option>`));
}

function renderPembelianChart(kebunFilter) {
    let filtered = pembelianPadiData;
    if (kebunFilter) filtered = pembelianPadiData.filter(item => item.deskripsi === kebunFilter);

    const bulanNama = [
        '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    const labels = [];
    const values = [];

    for (let i = 1; i <= 8; i++) {
        labels.push(bulanNama[i] + ' ' + selectedTahun);
        const found = filtered.find(item => Number(item.bulan) === i && Number(item.tahun) === selectedTahun);
        values.push(found ? Number(found.transaksi_padi) : 0);
    }

    const ctx2 = document.getElementById('chartPembelianPadi');
    if (!ctx2) return;
    if (chartPembelianPadiInstance) chartPembelianPadiInstance.destroy();

    chartPembelianPadiInstance = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label: kebunFilter ? `Total Transaksi per Bulan (${kebunFilter})` : 'Total Transaksi per Bulan', data: values, backgroundColor: 'rgba(34,139,34,0.5)', borderColor: 'rgba(34,139,34,1)', borderWidth: 2 }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
}

// ========================
// Load Data
// ========================
function loadChartUMKM() {
    $.getJSON('/api/realisasi-padi-umkm', function (data) {
        cacheRealisasiData = data;
        renderTahunDropdown();
        const tipe = $('#filterTipeGrafik').val() || 'perbandingan';
        renderChartBig1(tipe);
    });
}

function loadPembelianData() {
    $.getJSON('/api/pembelian-padi', function (data) {
        pembelianPadiData = data;
        kebunList = [...new Set(data.map(item => item.deskripsi))].sort();
        renderKebunDropdown();
        renderPembelianChart();
    });
}

// ========================
// Event Listeners
// ========================
$('#filterTipeGrafik').on('change', function () {
    const tipe = $(this).val();
    renderChartBig1(tipe);
});

$(document).on('change', '#filterKebun', function () {
    renderPembelianChart($(this).val());
});

$(document).on('change', '#filterTahun', function () {
    selectedTahun = Number($(this).val());
    const tipe = $('#filterTipeGrafik').val() || 'perbandingan';
    renderChartBig1(tipe);
    renderPembelianChart($('#filterKebun').val());
});

// ========================
// Inisialisasi
// ========================
loadChartUMKM();
loadPembelianData();
