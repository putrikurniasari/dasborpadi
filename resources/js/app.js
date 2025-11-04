//import library, plugins, dll
import './bootstrap';
import Chart from 'chart.js/auto';
import ChartDataLabels from 'chartjs-plugin-datalabels';
import zoomPlugin from 'chartjs-plugin-zoom';
import $ from 'jquery';
Chart.register(zoomPlugin);
Chart.register(ChartDataLabels);

//inisialisasi variabel
let chartBig1Instance = null;
// let chartBig1YAxisInstance = null;
let originalCanvasParentHTML = null;
let cacheRealisasiData = null;
// let lastTahunDipilih = null;
// let lastBulanDipilih = null;
let pembelianPadiData = [];
let chartPembelianPadiInstance = null;
let kebunList = [];
let tahunListPembelian = [];
let lastTahunPembelianDipilih = null;


// ========================
// Function Helper
// ========================

// Load Data
function loadChartUMKM() {
    $.getJSON('/api/realisasi-padi-umkm', function (data) {
        cacheRealisasiData = data || [];
        renderTabelRealisasiCard1();
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

        renderChartPembelianCard2();
    }).fail(function (err) {
        console.error('Gagal load /api/pembelian-padi', err);
    });
}

// Helper angka (JT, M)
function formatShortNumber(num) {
    if (num >= 1_000_000_000) return (num / 1_000_000_000).toFixed(1).replace('.', ',') + 'M';
    if (num >= 1_000_000) return (num / 1_000_000).toFixed(1).replace('.', ',') + 'JT';
    return num.toString();
}

// Transparansi Bar
function transparentize(hexColor, opacity = 0.5) {
    hexColor = hexColor.replace('#', '');
    if (hexColor.length === 3) {
        hexColor = hexColor.split('').map(c => c + c).join('');
    }
    const r = parseInt(hexColor.substring(0, 2), 16);
    const g = parseInt(hexColor.substring(2, 4), 16);
    const b = parseInt(hexColor.substring(4, 6), 16);

    // Konversi ke RGBA dengan tingkat transparansi
    return `rgba(${r}, ${g}, ${b}, ${opacity})`;
}


// Simpan struktur asli
$(document).ready(function () {
    const $ctx = $('#chartBig1');
    if ($ctx.length && originalCanvasParentHTML === null) {
        originalCanvasParentHTML = $('#chartBig1Wrapper').html();
    }


    // Dropdown filters for pembelian padi
    $('#filterKebun').on('change', function () {
        const kebun = $(this).val();
        const tahun = $('#filterTahun').val();
        renderChartPembelianCard2(kebun, tahun);
    });

    $('#filterTahun').on('change', function () {
        const tahun = $(this).val();
        const kebun = $('#filterKebun').val();
        renderChartPembelianCard2(kebun, tahun);
    });

    $('#filterTahun2').on('change', function () {
        const tahun = $(this).val();
        const kebun = $('#filterKebun').val();
        renderChartPembelianCard2(kebun, tahun);
    });
    loadChartUMKM();
    loadPembelianData();
});

// Dropdown Kebun
function renderKebunDropdown() {
    const $dropdown = $('#filterKebun');
    $dropdown.empty().append('<option value="default" selected>Semua Kebun</option>');
    kebunList.forEach(kebun => $dropdown.append(`<option value="${kebun}">${kebun}</option>`));
}

// Dropdown Tahun
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
// Bar Chart
// ========================

// Card Atas (Tabel) 
function renderTabelRealisasiCard1() {
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
        return;
    }

    $('#cardTitle').text('Tabel Realisasi Padi UMKM');
    $('#chartBig1Wrapper').hide()
    $('#chartBig1WrapperDefault').show()
    $('#searchtabel').show();
    $('#btnKembaliChart')
        .hide()
        .text('');
    $('#btnlihatgrafik')
        .show()
        .off('click')
        .on('click', () => renderChartTahunanCard1());
}

// Card Atas (Chart Tahunan) 
function renderChartTahunanCard1() {
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
        // Simpan realisasi yang memiliki bulan terakhir
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
                    borderColor: '#0D5EA6',
                    backgroundColor: transparentize('#0D5EA6', 0.5),
                    borderWidth: 2

                },
                {
                    label: 'Realisasi Tahunan',
                    data: realisasiData,
                    borderColor: '#ff7f0e',
                    backgroundColor: transparentize('#ff7f0e', 0.5),
                    borderWidth: 2
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
                    anchor: 'center',
                    align: 'center',
                    clamp: true,
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
                    renderChartBulananCard1(tahun);
                }
            }
        },
    });

    $('#cardTitle').text('Realisasi Padi UMKM per Tahun');
    $('#chartBig1Wrapper').show();
    $('#chartBig1WrapperDefault').hide();
    $('#btnKembaliChart')
        .show()
        .text('← Kembali ke Tabel')
        .off('click')
        .on('click', () => renderTabelRealisasiCard1());
    $('#btnlihatgrafik').hide();
    $('#searchtabel').hide();
}

// Card Atas (Chart Bulanan)
function renderChartBulananCard1(tahun) {

    if (!cacheRealisasiData) return;
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
                {
                    label: 'Target Bulanan',
                    data: targetSdBulan,
                    borderColor: '#0D5EA6',
                    backgroundColor: transparentize('#0D5EA6', 0.5),
                    borderWidth: 2
                },
                {
                    label: 'Realisasi Bulanan',
                    data: realisasiSdBulan,
                    borderColor: '#ff7f0e',
                    backgroundColor: transparentize('#ff7f0e', 0.5),
                    borderWidth: 2
                }
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
                    renderChartPerKebunCard1(bulan, tahun);
                }
            }
        }
    });

    $('#cardTitle').text(`Realisasi Padi UMKM Tahun ${tahun} per Bulan`);
    $('#btnKembaliChart')
        .show()
        .text('← Kembali ke Tahunan')
        .off('click')
        .on('click', () => renderChartTahunanCard1());
    $('#tipeGrafikWrapper').hide();
}

// Card Atas (Chart Per Kebun)
function renderChartPerKebunCard1(bulan, tahun) {

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

    $('#chartBig1').css({ width: '100%', height: '100%' });
    $('#chartBig1').parent().css({ width: '100%', height: '400px' });

    chartBig1Instance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Plafond OPL',
                    data: plafond,
                    borderColor: '#0D5EA6',
                    backgroundColor: transparentize('#0D5EA6', 0.5),
                    borderWidth: 1
                },
                {
                    label: 'Transaksi Padi',
                    data: transaksi,
                    borderColor: '#ff7f0e',
                    backgroundColor: transparentize('#ff7f0e', 0.5),
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
                tooltip: { mode: 'index', intersect: false },
                datalabels: {
                    display: false,
                    color: 'white',
                    anchor: 'center',
                    align: 'center',
                    font: { weight: 'bold', size: 11 },
                    formatter: (value) => formatShortNumber(value)
                },
                zoom: {
                    zoom: {
                        wheel: { enabled: true },
                        pinch: { enabled: true },
                        mode: 'x',
                        onZoom: ({ chart }) => {
                            const zoomScale = chart.getZoomLevel();
                            if (zoomScale > 2) chart.options.plugins.datalabels.display = true;
                            chart.update();
                        },
                        onZoomComplete: ({ chart }) => {
                            const zoomScale = chart.getZoomLevel();
                            if (zoomScale <= 1.01) chart.options.plugins.datalabels.display = false;
                            chart.update();
                        }
                    },
                    pan: {
                        enabled: true,
                        mode: 'x'
                    },
                    limits: {
                        x: { min: 'original', max: 'original' }
                    }
                }
            },
            animation: {
                duration: 800,
                easing: 'easeOutCubic'
            }
        },
    });

    // 🔹 Reset zoom dengan double click
    ctx.addEventListener('dblclick', () => {
        chartBig1Instance.resetZoom();
        chartBig1Instance.options.plugins.datalabels.display = false;
        chartBig1Instance.update('none');
    });

    const namaBulan = bulanNama[bulan] || bulan;
    $('#cardTitle').text(`Transaksi per Kebun Bulan ${namaBulan} ${tahun}`);

    $('#btnKembaliChart')
        .show()
        .text('← Kembali ke Bulanan')
        .off('click')
        .on('click', () => renderChartBulananCard1(tahun));

    $('#tipeGrafikWrapper').hide();
}

// Card Bawah (Chart Transaksi Padi)

//bar
function renderChartPembelianCard2(kebunFilter, tahunFilter) {
    // Jika belum pilih kebun atau pilih semua kebun
    if (!kebunFilter || kebunFilter === 'default' || kebunFilter === 'Semua Kebun') {
        $('#chartBig2WrapperDefault').show();
        $('#chartPembelianWrapper').hide();
        return; // keluar fungsi, tidak perlu render chart pembelian
    } else {
        $('#chartBig2WrapperDefault').hide();
        $('#chartPembelianWrapper').show();
    }
    
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
        $('#chartPembelianWrapper').html(` <div class="empty-data-container">
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
                borderColor: '#4bc0c0b3',
                backgroundColor: transparentize('#4bc0c0b3', 0.5),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true },
                datalabels: {
                    color: '#fff', // warna label putih
                    anchor: 'end',
                    align: 'start',
                    font: {
                        weight: 'bold',
                        size: 11
                    },
                    formatter: function (value) {
                        return formatShortNumber(value); // panggil fungsi format
                    }
                }
            },
            scales: {
                x: {
                    title: { display: true, text: 'Bulan' },
                    ticks: { color: '#fff' } // opsional: warna label sumbu X
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#fff', // opsional: warna label sumbu Y
                        font: { size: 12 },
                        callback: val => formatShortNumber(Number(val))
                    },
                }
            },
            onClick: (evt, elements) => {
                if (elements.length > 0) {
                    const index = elements[0].index;
                    const bulan = bulanUnik[index];
                    const tahunDipakai = lastTahunPembelianDipilih;
                    const kebunDipilih = $('#filterKebun').val();
                    renderPembelianPerKebunCard2(bulan, tahunDipakai, kebunDipilih);
                }
            }
        },
    });

    $('#chartBigWrapperDefault').hide()
    $('#btnKembaliChart2').hide();

}

// Card Bawah (Chart Pembelian Per Kebun)
function renderPembelianPerKebunCard2(bulan, tahun, kebunFilter) {

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
                    borderColor: '#36a2ebb3',
                    backgroundColor: transparentize('#36a2ebb3', 0.5),
                    borderWidth: 2
                },
                {
                    label: 'Transaksi Padi',
                    data: transaksi,
                    borderColor: '#ff6384b3',
                    backgroundColor: transparentize('#ff6384b3', 0.5),
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true } },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Kebun'
                    },
                    ticks: {
                        font: { size: 12 },
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: { display: false }
                },
                y: {
                    type: 'linear',
                    position: 'left',
                    title: { display: true, text: 'Nilai (Rp)' },
                    grid: { drawBorder: true, color: '#ddd' },
                    beginAtZero: true,
                    ticks: {
                        font: { size: 12 },
                        callback: val => formatShortNumber(Number(val))
                    }
                }
            },
            plugins: {
                legend: { display: true },
                tooltip: { mode: 'index', intersect: false },
                datalabels: {
                    display: false, // ⬅️ Awalnya tidak tampil
                    color: 'white',
                    anchor: 'center',
                    align: 'center',
                    font: { weight: 'bold', size: 11 },
                    formatter: (value) => formatShortNumber(value)
                },
                zoom: {
                    zoom: {
                        wheel: { enabled: true },
                        pinch: { enabled: true },
                        mode: 'x',
                        onZoom: ({ chart }) => {
                            const zoomScale = chart.getZoomLevel();

                            // Saat pertama kali zoom, langsung tampilkan label
                            if (zoomScale > 2) {
                                chart.options.plugins.datalabels.display = true;
                            }

                            chart.update();
                        },
                        onZoomComplete: ({ chart }) => {
                            const zoomScale = chart.getZoomLevel();

                            // Jika zoom out kembali ke posisi awal, sembunyikan label lagi
                            if (zoomScale <= 1.01) {
                                chart.options.plugins.datalabels.display = false;
                            }

                            chart.update();
                        }
                    },
                    pan: {
                        enabled: true,
                        mode: 'x'
                    },
                    limits: {
                        x: { min: 'original', max: 'original' }
                    }
                }

            },
            // tambahkan animasi bar agar halus saat zoom
            animation: {
                duration: 800,
                easing: 'easeOutCubic'
            }
        },
        // plugins: [ChartDataLabels]
    });

    // 🔹 Reset zoom dengan double click
    ctx.addEventListener('dblclick', () => {
        chartPembelianPadiInstance.resetZoom();
        chartPembelianPadiInstance.options.plugins.datalabels.display = false;
        chartPembelianPadiInstance


            .update('none');
    });


    const namaBulan = bulanNama[bulan] || bulan;

    // === Ubah judul ===
    $('#chartPembelianPadi').closest('.card').find('.card-title').text(
        `Rekap Pembelian Padi ${kebunFilter ? kebunFilter + ' - ' : ''}Bulan ${namaBulan} ${tahun}`
    );

    // === Sembunyikan dropdown kebun & tahun ===
    $('.filter-kebun-group, #tahunWrapper2').hide();

    // === Tombol kembali ===
    $('#btnKembaliChart2')
        .show()
        .text('← Kembali ke Bulanan')
        .off('click')
        .on('click', () => {
            $('.filter-kebun-group, #tahunWrapper2').show();

            // 🔹 Reset judul ke tampilan awal
            $('#chartPembelianPadi')
                .closest('.card')
                .find('.card-title')
                .text('Realisasi Pembelian Padi per Bulan');

            renderChartPembelianCard2(kebunFilter, tahun);
        });

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
        border-radius: 12px;
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
        border-radius: 12px;
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
}
