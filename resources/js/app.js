import './bootstrap';
import Chart from 'chart.js/auto';
import $ from 'jquery';

let chartBig1Instance = null;
let chartBig1YAxisInstance = null;
let originalCanvasParentHTML = null;
let cacheRealisasiData = null;
let lastTahunDipilih = null;
let lastBulanDipilih = null;
let pembelianPadiData = [];

// ========================
// Helper format angka
// ========================
function formatShortNumber(num) {
    if (num >= 1_000_000_000) return (num / 1_000_000_000).toFixed(1).replace('.', ',') + 'M';
    if (num >= 1_000_000) return (num / 1_000_000).toFixed(1).replace('.', ',') + 'JT';
    if (num >= 1_000) return (num / 1_000).toFixed(1).replace('.', ',') + 'K';
    return num.toString();
}

// ========================
// Simpan struktur asli sekali saja
// ========================
$(document).ready(function () {
    const $ctx = $('#chartBig1');
    if ($ctx.length && originalCanvasParentHTML === null) {
        originalCanvasParentHTML = $('#chartBig1Wrapper').html();
    }

    // Dropdown filters for pembelian padi
    $('#filterKebun').on('change', function () {
        const kebun = $(this).val();
        const tahun = $('#filterTahun').val();
        renderPembelianChart(kebun, tahun);
    });

    $('#filterTahun').on('change', function () {
        const tahun = $(this).val();
        const kebun = $('#filterKebun').val();
        renderPembelianChart(kebun, tahun);
    });

    $('#filterTahun2').on('change', function () {
        const tahun = $(this).val();
        const kebun = $('#filterKebun').val();
        renderPembelianChart(kebun, tahun);
    });
    // Init load
    loadChartUMKM();
    loadPembelianData();
});

// ========================
// Cleanup scrollable view
// ========================
function cleanupScrollableView() {
    $(window).off('resize.chartPerKebun');
    if (chartBig1Instance) { try { chartBig1Instance.destroy(); } catch (e) { console.warn(e); } chartBig1Instance = null; }
    if (chartBig1YAxisInstance) { try { chartBig1YAxisInstance.destroy(); } catch (e) { console.warn(e); } chartBig1YAxisInstance = null; }

    if (originalCanvasParentHTML !== null) {
        $('#chartBig1Wrapper').html(originalCanvasParentHTML);
    }

    // 🔑 Reset height agar tidak nyangkut
    $('#chartBig1').css({ 'height': '', 'max-height': '' });
    $('#chartBig1').parent().css({ 'height': '', 'max-height': '' });
}


// ========================
// Chart Tahunan (default)
// ========================
function renderChartTahunan() {
    cleanupScrollableView();

    // Cek apakah ada data
    if (!cacheRealisasiData || cacheRealisasiData.length === 0) {
        // Sisipkan CSS animasi ke <head> kalau belum ada
        if (!$('#shineAnimationStyle').length) {
            $('head').append(`
            <style id="shineAnimationStyle">
                @keyframes shine {
                    0% {
                        background-position: -200px;
                    }
                    100% {
                        background-position: 200px;
                    }
                }

                .shine-text {
                    background: linear-gradient(90deg, #aaa, #fff, #aaa);
                    background-size: 200px 100%;
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    animation: shine 2.5s linear infinite;
                }

                .empty-data-container {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    height: 320px;
                    text-align: center;
                    transform: translateY(-30px); /* geser ke atas sedikit */
                }

                .empty-data-icon {
                    font-size: 4rem;
                    color: #888;
                    margin-bottom: 10px;
                    opacity: 0.8;
                }

                .empty-data-text {
                    font-size: 1.2rem;
                    font-weight: bold;
                    color: #cfcfcf;
                }

                .empty-data-text1 {
                    font-size: 1rem;
                    font-weight: light;
                    color: #cfcfcf;
                }
            </style>
        `);
        }

        // Masukkan elemen teks + ikon
        $('#chartBig1Wrapper').html(`
        <div class="empty-data-container">
            <div class="empty-data-icon">
                <i class="fas fa-database"></i>
            </div>
            <div class="empty-data-text1 shine-text">
                Data Realisasi Padi Akan Muncul disini.
            </div>
            <div class="empty-data-text shine-text">
                Tidak ada data yang ditampilkan karena data masih kosong.
            </div>
        </div>
    `);

        // $('#cardTitle').hide();
        // $('#atas_title').hide();
        // $('#btnKembaliChart').hide();
        return;
    }




    lastTahunDipilih = null;
    lastBulanDipilih = null;
    const grouped = {};
    cacheRealisasiData.forEach(item => {
        const th = Number(item.tahun);
        const bln = Number(item.bulan);

        if (!grouped[th]) {
            grouped[th] = { targetArr: [], lastMonth: 0, realisasi: 0 };
        }

        const targetVal = Number(item.target_tahun);
        if (!isNaN(targetVal)) grouped[th].targetArr.push(targetVal);

        const realisasiVal = Number(item.realisasi_sd_bulan);
        // Simpan realisasi yang memiliki bulan terakhir (mis. sd bulan terbesar)
        if (!isNaN(realisasiVal) && bln >= grouped[th].lastMonth) {
            grouped[th].lastMonth = bln;
            grouped[th].realisasi = realisasiVal;
        }
    });

    const tahunList = Object.keys(grouped).map(Number).sort((a, b) => a - b);
    const labels = tahunList.map(th => th.toString());

    const targetData = tahunList.map(th => {
        const arr = grouped[th].targetArr;
        if (!arr || arr.length === 0) return 0;
        return arr.reduce((a, b) => a + b, 0) / arr.length;
    });

    const realisasiData = tahunList.map(th => grouped[th].realisasi || 0);

    const ctx = document.getElementById('chartBig1');
    if (!ctx) return;
    if (chartBig1Instance) chartBig1Instance.destroy();

    chartBig1Instance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Target Tahunan',
                    data: targetData,
                    backgroundColor: '#0D5EA6'
                },
                {
                    label: 'Realisasi Tahunan',
                    data: realisasiData,
                    backgroundColor: '#ff7f0e'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true },
                datalabels: {
                    formatter: function (value) {
                        return formatShortNumber(Number(value));
                    },
                    font: {
                        weight: 'bold'
                    },
                    color: 'white',
                    anchor: 'center',   // titik acuan di tengah bar
                    align: 'center',    // teks rata tengah vertikal/horizontal
                    clamp: true,        // cegah teks keluar
                    clip: false
                }
            },
            scales: {
                x: { title: { display: true, text: 'Tahun' } },
                y: {
                    title: { display: true, text: 'Nilai (Rp)' },
                    beginAtZero: true,
                    ticks: { callback: val => formatShortNumber(Number(val)) }
                }
            },
            onClick: (evt, elements) => {
                if (elements.length > 0) {
                    const tahun = Number(chartBig1Instance.data.labels[elements[0].index]);
                    renderChartBulanan(tahun);
                }
            }
        },
        plugins: [ChartDataLabels] // aktifkan plugin datalabels
    });

    $('#cardTitle').text('Realisasi Padi UMKM per Tahun');
    $('#btnKembaliChart')
        .hide() // tombol disembunyikan di chart tahunan
        .text('');
    $('#tipeGrafikWrapper').hide();
    $('#tahunWrapper').hide();
}


// ========================
// Chart Bulanan
// ========================
function renderChartBulanan(tahun) {
    if (!cacheRealisasiData) return;
    cleanupScrollableView();
    lastTahunDipilih = tahun;
    lastBulanDipilih = null;

    // Filter data sesuai tahun
    const filteredData = cacheRealisasiData.filter(item => Number(item.tahun) === tahun);

    // Ambil bulan unik dari data
    const bulanUnik = [...new Set(filteredData.map(item => Number(item.bulan)))].sort((a, b) => a - b);

    const bulanNama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    const labels = [];
    const targetSdBulan = [];
    const realisasiSdBulan = [];

    bulanUnik.forEach(bulan => {
        labels.push(bulanNama[bulan] || bulan);
        const found = filteredData.find(item => Number(item.bulan) === bulan);
        targetSdBulan.push(found ? Number(found.target_sd_bulan) : 0);
        realisasiSdBulan.push(found ? Number(found.realisasi_sd_bulan) : 0);
    });

    const ctx = document.getElementById('chartBig1');
    if (!ctx) return;
    if (chartBig1Instance) chartBig1Instance.destroy();

    chartBig1Instance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: 'Target Bulanan', data: targetSdBulan, backgroundColor: '#0D5EA6' },
                { label: 'Realisasi Bulanan', data: realisasiSdBulan, backgroundColor: '#ff7f0e' }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true },
                datalabels: {
                    formatter: value => formatShortNumber(Number(value)),
                    font: { weight: 'bold' },
                    anchor: 'center',
                    align: 'center',
                    color: 'white',
                    clip: true
                }
            },
            scales: {
                x: { title: { display: true, text: 'Bulan' } },
                y: {
                    title: { display: true, text: 'Nilai (Rp)' },
                    beginAtZero: true,
                    ticks: { callback: val => formatShortNumber(Number(val)) }
                }
            },
            onClick: (evt, elements) => {
                if (elements.length > 0) {
                    const index = elements[0].index;
                    const bulan = bulanUnik[index]; // gunakan bulanUnik
                    renderChartPerKebunInSameCard(bulan, tahun);
                }
            }
        }
    });

    $('#cardTitle').text(`Realisasi Padi UMKM Tahun ${tahun} per Bulan`);
    $('#btnKembaliChart')
        .show()
        .text('← Kembali ke Tahunan')
        .off('click')
        .on('click', () => renderChartTahunan());
    $('#tipeGrafikWrapper').hide();
}


// ========================
// Chart per kebun (scrollable)
// ========================
function renderChartPerKebunInSameCard(bulan, tahun) {
    if (!pembelianPadiData.length) return;
    lastTahunDipilih = tahun;
    lastBulanDipilih = bulan;

    const bulanNama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const filtered = pembelianPadiData.filter(item =>
        Number(item.bulan) === bulan && Number(item.tahun) === tahun
    );

    const labels = filtered.map(item => item.deskripsi);
    const plafond = filtered.map(item => Number(item.plafond_opl));
    const transaksi = filtered.map(item => Number(item.transaksi_padi));

    const ctx = document.getElementById('chartBig1');
    if (!ctx) return;
    if (chartBig1Instance) chartBig1Instance.destroy();

    // biarkan parent container yang atur ukuran
    $('#chartBig1').css({ width: '100%', height: '100%' });
    $('#chartBig1').parent().css({ width: '100%', height: '400px' });
    // tinggi parent bisa diatur sesuai kebutuhan (misal 300–500px)

    chartBig1Instance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Plafond OPL',
                    data: plafond,
                    backgroundColor: '#0D5EA6',
                    borderColor: '#0D5EA6',
                    borderWidth: 1
                },
                {
                    label: 'Transaksi Padi',
                    data: transaksi,
                    backgroundColor: '#ff7f0e',
                    borderColor: '#ff7f0e',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // biar fleksibel sesuai parent
            scales: {
                x: {
                    ticks: {
                        font: { size: 12 },
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: { size: 12 },
                        callback: val => formatShortNumber(Number(val))
                    },
                    title: { display: true, text: 'Nilai (Rp)' },
                    grid: { drawBorder: true, color: '#ddd' }
                }
            },
            plugins: {
                legend: { display: true },
                tooltip: { mode: 'index', intersect: false }
            }
        }
    });

    const namaBulan = bulanNama[bulan] || bulan;
    $('#cardTitle').text(`Transaksi per Kebun Bulan ${namaBulan} ${tahun}`);

    $('#btnKembaliChart')
        .show()
        .text('← Kembali ke Bulanan')
        .off('click')
        .on('click', () => renderChartBulanan(tahun));

    $('#tipeGrafikWrapper').hide();
}





// ========================
// Chart pembelian padi (bawah)
// ========================
let chartPembelianPadiInstance = null;
let kebunList = [];
let tahunListPembelian = [];
let lastTahunPembelianDipilih = null; // 🔹 simpan tahun terakhir dipilih

function renderKebunDropdown() {
    const $dropdown = $('#filterKebun');
    $dropdown.empty().append('<option value="" selected>Semua Kebun</option>');
    kebunList.forEach(kebun => $dropdown.append(`<option value="${kebun}">${kebun}</option>`));
}

function renderTahunDropdown() {
    const $dropdown = $('#filterTahun2');
    $dropdown.empty();
    tahunListPembelian.forEach(th => $dropdown.append(`<option value="${th}">${th}</option>`));

    // default: tahun terbaru
    if (tahunListPembelian.length) {
        const terbaru = Math.max(...tahunListPembelian);
        $dropdown.val(terbaru);
        lastTahunPembelianDipilih = terbaru; // 🔹 set default
    }
}


// ========================
// Bar pembelian padi (bawah)
// ========================
function renderPembelianChart(kebunFilter, tahunFilter) {

    if (!pembelianPadiData || pembelianPadiData.length === 0) {
        // Sisipkan CSS animasi ke <head> kalau belum ada
        if (!$('#shineAnimationStyle2').length) {
            $('head').append(`
                <style id="shineAnimationStyle2">
                    @keyframes shine {
                        0% { background-position: -200px; }
                        100% { background-position: 200px; }
                    }

                    .shine-text {
                        background: linear-gradient(90deg, #aaa, #fff, #aaa);
                        background-size: 200px 100%;
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        animation: shine 2.5s linear infinite;
                    }

                    .empty-data-container {
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        height: 300px;
                        text-align: center;
                        transform: translateY(-25px); /* sedikit ke atas */
                    }

                    .empty-data-icon {
                        font-size: 4rem;
                        color: #888;
                        margin-bottom: 10px;
                        opacity: 0.85;
                    }

                    .empty-data-text {
                        font-size: 1.2rem;
                        font-weight: bold;
                        color: #cfcfcf;
                    }

                    .empty-data-subtext {
                        font-size: 1rem;
                        font-weight: 300;
                        color: #cfcfcf;
                    }
                </style>
            `);
        }

        // Masukkan elemen teks + ikon
        $('#chartPembelianWrapper').html(`
            <div class="empty-data-container">
                <div class="empty-data-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div class="empty-data-subtext shine-text">
                    Data Pembelian Padi Akan Ditampilkan di Sini.
                </div>
                <div class="empty-data-text shine-text">
                    Tidak ada data yang ditampilkan karena data masih kosong.
                </div>
            </div>
        `);

        // $('#btnKembaliChart2').hide();
        // $('#cardTitle2').hide();
        // $('#atasjudul').hide();
        // $('#tahunWrapper2').hide();
        // $('.filter-kebun-group').hide();
        return;
    }

    // gunakan tahun terakhir dipilih kalau ada
    const tahunTerbaru = Math.max(...pembelianPadiData.map(item => Number(item.tahun)));
    const tahunDipakai = tahunFilter || lastTahunPembelianDipilih || tahunTerbaru;
    lastTahunPembelianDipilih = tahunDipakai;

    // Filter data sesuai tahun dan kebun (jika ada)
    const filteredData = pembelianPadiData.filter(item =>
        Number(item.tahun) === Number(tahunDipakai) &&
        (!kebunFilter || item.deskripsi === kebunFilter)
    );

    // Ambil bulan unik dari data
    const bulanUnik = [...new Set(filteredData.map(item => Number(item.bulan)))].sort((a, b) => a - b);

    const bulanNama = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const labels = [];
    const transaksi = [];

    bulanUnik.forEach(bulan => {
        labels.push(bulanNama[bulan] || bulan);
        const bulanItems = filteredData.filter(item => Number(item.bulan) === bulan);
        const totalTransaksi = bulanItems.reduce((sum, d) => sum + Number(d.transaksi_padi), 0);
        transaksi.push(totalTransaksi);
    });

    const ctx = document.getElementById('chartPembelianPadi');
    if (!ctx) return;
    if (chartPembelianPadiInstance) chartPembelianPadiInstance.destroy();

    chartPembelianPadiInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: `Transaksi ${kebunFilter ? kebunFilter : 'Semua Kebun'} (${tahunDipakai})`,
                data: transaksi,
                backgroundColor: 'rgba(75, 192, 192, 0.7)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true } },
            scales: {
                x: { title: { display: true, text: 'Bulan' } },
                y: {
                    title: { display: true, text: 'Jumlah Transaksi (Rp)' },
                    beginAtZero: true,
                    ticks: { callback: val => formatShortNumber(Number(val)) }
                }
            },
            onClick: (evt, elements) => {
                if (elements.length > 0) {
                    const index = elements[0].index;
                    const bulan = bulanUnik[index]; // gunakan index bulanUnik
                    const tahunDipakai = lastTahunPembelianDipilih;
                    const kebunDipilih = $('#filterKebun').val();
                    renderPembelianPerKebun(bulan, tahunDipakai, kebunDipilih);
                }
            }
        }
    });

    $('#btnKembaliChart2').hide();

}


// ========================
// Bar Rekap 
// ========================
function renderPembelianPerKebun(bulan, tahun, kebunFilter) {
    if (!pembelianPadiData.length) return;

    const bulanNama = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const filtered = pembelianPadiData.filter(item =>
        Number(item.bulan) === bulan &&
        Number(item.tahun) === tahun &&
        (!kebunFilter || item.deskripsi === kebunFilter)
    );

    if (!filtered.length) return;

    const labels = filtered.map(item => item.deskripsi);
    const plafond = filtered.map(item => Number(item.plafond_opl));
    const transaksi = filtered.map(item => Number(item.transaksi_padi));
    const persen = filtered.map(item => {
        const p = Number(item.plafond_opl);
        return p > 0 ? (Number(item.transaksi_padi) / p) * 100 : 0;
    });

    const ctx = document.getElementById('chartPembelianPadi');
    if (!ctx) return;
    if (chartPembelianPadiInstance) chartPembelianPadiInstance.destroy();

    chartPembelianPadiInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Plafond OPL',
                    data: plafond,
                    backgroundColor: 'rgba(54,162,235,0.7)'
                },
                {
                    label: 'Transaksi Padi',
                    data: transaksi,
                    backgroundColor: 'rgba(255,99,132,0.7)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true } },
            scales: {
                x: { title: { display: true, text: 'Kebun' } },
                y: {
                    type: 'linear',
                    position: 'left',
                    title: { display: true, text: 'Nilai (Rp)' },
                    beginAtZero: true,
                    ticks: { callback: val => formatShortNumber(Number(val)) }
                }
            }
        }
    });

    const namaBulan = bulanNama[bulan] || bulan;

    // === Ubah judul ===
    $('#chartPembelianPadi').closest('.card').find('.card-title').text(
        `Rekap Pembelian Padi ${kebunFilter ? kebunFilter : ''} Bulan ${namaBulan} ${tahun}`
    );

    // === Sembunyikan dropdown kebun & tahun ===
    $('.filter-kebun-group, #tahunWrapper2').hide();

    // === Sembunyikan dropdown kebun & tahun ===
$('.filter-kebun-group, #tahunWrapper2').hide();

// === Sembunyikan dropdown kebun & tahun ===
$('.filter-kebun-group, #tahunWrapper2').hide();

// === Buat wrapper utama (judul + grid progress) ===
const $wrapper = $('<div id="persenButtonsWrapper" class="mt-3"></div>');
$wrapper.css({
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    gap: '12px',
    padding: '15px',
    background: 'rgba(255,255,255,0.05)',
    borderRadius: '12px',
    textAlign: 'center',
    backdropFilter: 'blur(4px)'
});

// === Tambahkan style animasi shimmer + layout sejajar ===
const shimmerStyle = `
<style id="persenStyleFix">
    @keyframes shimmerText {
        0% { background-position: -200px 0; }
        100% { background-position: 200px 0; }
    }
    .shimmer-title h6 {
        background: linear-gradient(90deg, #fff, #bfbfbf, #fff);
        background-size: 200px 100%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shimmerText 2.5s infinite linear;
    }
    /* --- Perataan progress bar agar sejajar --- */
    .persen-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 10px;
        width: 100%;
    }
    .progress-box {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        height: 100px; /* tinggi seragam */
        background: rgba(255,255,255,0.03);
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .progress-box:hover {
        transform: scale(1.05);
        background: rgba(255,255,255,0.07);
    }
    .progress-box .progress {
        width: 100%;
        height: 8px;
        background: #333;
        border-radius: 4px;
        overflow: hidden;
        margin-top: auto;
        margin-bottom: 4px;
    }
</style>
`;
if (!$('#persenStyleFix').length) $('head').append(shimmerStyle);

// === Tambahkan judul ke dalam wrapper ===
const $title = $(`
    <div class="text-center shimmer-title mb-2">
        <h6 style="
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 4px;
        ">
            Persentase Capaian Transaksi terhadap Plafond OPL
        </h6>
        <small style="color:#ccc; font-size:0.85rem;">
            Menunjukkan seberapa besar realisasi transaksi dibandingkan target plafon tiap kebun.
        </small>
    </div>
`);
$wrapper.append($title);

// === Buat grid untuk progress bar ===
const $grid = $('<div class="persen-grid"></div>');

filtered.forEach((item, idx) => {
    const p = persen[idx];
    const color = `hsl(${Math.min(p, 100)}, 70%, 45%)`;

    const $box = $(`
        <div class="progress-box text-white p-2 shadow-sm">
            <div style="font-weight:bold; font-size:0.9rem; line-height:1.2rem;">${item.deskripsi}</div>
            <div class="progress mt-1">
                <div class="progress-bar" role="progressbar"
                    style="width:${Math.min(p, 100)}%; background-color:${color}; transition: width 1.2s ease;">
                </div>
            </div>
            <small style="font-size:0.85rem; color:#ccc;">${p.toFixed(1)}%</small>
        </div>
    `);

    $grid.append($box);
});

// === Masukkan grid ke dalam wrapper ===
$wrapper.append($grid);

// === Sisipkan ke dalam card di bawah chart ===
const $chartCard = $('#chartPembelianPadi').closest('.card');
$chartCard.find('#persenButtonsWrapper').remove();
$chartCard.find('.card-body').append($wrapper);





    // === Tombol kembali ===
    $('#btnKembaliChart2')
        .show()
        .text('← Kembali ke Bulanan')
        .off('click')
        .on('click', () => {
            $wrapper.remove();
            $('.filter-kebun-group, #tahunWrapper2').show();

            // 🔹 Reset judul ke tampilan awal
            $('#chartPembelianPadi')
                .closest('.card')
                .find('.card-title')
                .text('Realisasi Pembelian Padi per Bulan');

            renderPembelianChart(kebunFilter, tahun);
        });


}




// ========================
// Load Data
// ========================
function loadChartUMKM() {
    $.getJSON('/api/realisasi-padi-umkm', function (data) {
        cacheRealisasiData = data || [];
        renderChartTahunan();
    }).fail(function (err) {
        console.error('Gagal load /api/realisasi-padi-umkm', err);
    });
}
function loadPembelianData() {
    $.getJSON('/api/pembelian-padi', function (data) {
        pembelianPadiData = data || [];

        kebunList = [...new Set(pembelianPadiData.map(item => item.deskripsi))].sort();
        tahunListPembelian = [...new Set(pembelianPadiData.map(item => Number(item.tahun)))].sort((a, b) => a - b);

        renderKebunDropdown();
        renderTahunDropdown();

        renderPembelianChart();
    }).fail(function (err) {
        console.error('Gagal load /api/pembelian-padi', err);
    });
}
