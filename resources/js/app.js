import './bootstrap';
import Chart from 'chart.js/auto';
import $ from 'jquery';

let chartBig1Instance = null;
let cacheRealisasiData = null;
let pembelianPadiData = [];
let selectedTahun = 2025;

// ========================
// Chart Big 1 - Realisasi UMKM
// ========================
function renderChartBig1(tipe) {
    if (!cacheRealisasiData) return;

    const data = cacheRealisasiData;
    const bulanNama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

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
            plugins: { legend: { display: true } },
            scales: {
                x: { title: { display: true, text: 'Bulan' }, ticks: { autoSkip: false, maxRotation: 45, minRotation: 0 } },
                y: { title: { display: true, text: 'Nilai (Rp)' }, suggestedMax: Math.max(...targetSdBulan, ...realisasiSdBulan) * 1.2, beginAtZero: true }
            },
            onClick: (evt, elements) => {
                if (elements.length > 0) {
                    const index = elements[0].index;
                    const bulan = index + 1;
                    renderChartPerKebunInSameCard(bulan, selectedTahun);
                }
            }
        }
    });

    // tampilkan filter & sembunyikan tombol kembali
    $('#tipeGrafikWrapper').show();
    $('#btnKembaliChart').hide();
    $('#cardTitle').text('Realisasi Padi UMKM');

    // atur tinggi card default
    $('#chartBig1').parent().css('height', '400px');
}

// ========================
// Chart detail per kebun (horizontal, bar lebih tebal, nama kebun keluar semua)
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

    // bikin tinggi card lebih panjang sesuai jumlah label
    const cardHeight = Math.max(500, labels.length * 50); // 100px per label biar lebih lega
    $('#chartBig1').css({
        'height': cardHeight + 'px',     // atur langsung ke canvas juga
        'max-height': 'none'
    });
    $('#chartBig1').parent().css({
        'height': cardHeight + 'px',
        'max-height': 'none'
    });

    chartBig1Instance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Plafond OPL',
                    data: plafond,
                    backgroundColor: 'rgba(54,162,235,0.7)',
                    borderColor: 'rgba(54,162,235,1)',
                    borderWidth: 1,
                    // barThickness: 30   <-- buang ini biar fleksibel
                },
                {
                    label: 'Transaksi Padi',
                    data: transaksi,
                    backgroundColor: 'rgba(255,99,132,0.7)',
                    borderColor: 'rgba(255,99,132,1)',
                    borderWidth: 1,
                    // barThickness: 30
                }
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
                    barPercentage: 0.4,       // bar lebih tipis
                    categoryPercentage: 0.6   // jarak antar bar lebih renggang
                },
                x: {
                    beginAtZero: true,
                    ticks: { font: { size: 14 }, padding: 10 },
                    grid: { drawBorder: true, drawTicks: true, color: '#ccc' }
                }
            },
            plugins: {
                legend: { display: true },
                tooltip: { mode: 'index', intersect: false }
            }
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
// Chart Pembelian Padi card bawah (dikembalikan seperti awal)
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

    const bulanNama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
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
        data: { labels: labels, datasets: [{ label: kebunFilter ? `Total Transaksi per Bulan (${kebunFilter})` : 'Total Transaksi per Bulan', data: values, backgroundColor: 'rgba(34,139,34,0.5)', borderColor: 'rgba(34,139,34,1)', borderWidth: 2 }] },
        options: { responsive: true, maintainAspectRatio: false }
    });
}

// ========================
// Load data
// ========================
function loadChartUMKM() {
    $.getJSON('/api/realisasi-padi-umkm', function (data) {
        cacheRealisasiData = data;
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

// ========================
// Inisialisasi
// ========================
loadChartUMKM();
loadPembelianData();
