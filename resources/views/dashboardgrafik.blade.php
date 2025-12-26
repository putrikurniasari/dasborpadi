@extends('layouts.app', ['pageSlug' => 'dashboardgrafik'])
@section('page', 'dashboardgrafik')
@section('content')
    <!-- ===== Main Content Start ===== -->
    <main>
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
            <div class="grid grid-cols-12 gap-4 md:gap-6">
                <div class="col-span-12 space-y-6 xl:col-span-7">
                    <!-- TOTAL TARGET & REALISASI -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6">
                        <!-- TARGET DALAM TAHUN -->
                        <div 
                            x-data="{
                                selected: '2025',
                                totalRealisasi: 0,
                                totalTarget: 0,
                                persenRealisasi: 0,
                                chartData: [],
                                loadData() {
                                    fetch(`/ajax/realisasi/${this.selected}`)
                                        .then(res => res.json())
                                        .then(data => {
                                            this.totalRealisasi = data.total ?? 0;        // total realisasi tahun ini
                                            this.totalTarget = data.target ?? 0;         // target tahun ini
                                            this.persenRealisasi = this.totalTarget > 0
                                                ? (this.totalRealisasi / this.totalTarget * 100).toFixed(2)
                                                : 0;
                                            this.chartData = data.chart ?? [];           // data chart
                                            this.updateChart();
                                        });
                                },
                                updateChart() {
                                    if (window.chartOne) {
                                        window.chartOne.updateSeries([{
                                            name: 'Realisasi',
                                            data: this.chartData
                                        }])
                                    }
                                }
                                }"
                            x-init="loadData()"
                            class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">

                            <div class="flex items-center justify-between">
                                <!-- Selector Tahun -->
                                <div class="flex items-center gap-0.5 w-fit rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">
                                    <template x-for="tahun in ['2023','2024','2025']">
                                        <button @click="selected = tahun; loadData();"
                                            :class="selected === tahun
                                                ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800'
                                                : 'text-gray-500 dark:text-gray-400'"
                                            class="px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white"
                                            x-text="tahun">
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="mt-5 flex items-end justify-between">
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="`Target Tahun ${selected}`">
                                    </span>
                                    <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90"
                                        x-text="`Rp. ${Number(totalTarget).toLocaleString('id-ID')}`">
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- TARGET DALAM TAHUN END -->

                        <!-- REALISASI DALAM TAHUN -->
                        <div 
                            x-data="{
                                selected: '2025',
                                totalRealisasi: 0,
                                totalTarget: 0,
                                persenRealisasi: 0,
                                chartData: [],
                                loadData() {
                                    fetch(`/ajax/realisasi/${this.selected}`)
                                        .then(res => res.json())
                                        .then(data => {
                                            this.totalRealisasi = data.total ?? 0;        // total realisasi tahun ini
                                            this.totalTarget = data.target ?? 0;         // target tahun ini
                                            this.persenRealisasi = this.totalTarget > 0
                                                ? (this.totalRealisasi / this.totalTarget * 100).toFixed(2)
                                                : 0;
                                            this.chartData = data.chart ?? [];           // data chart
                                            this.updateChart();
                                        });
                                },
                                updateChart() {
                                    if (window.chartOne) {
                                        window.chartOne.updateSeries([{
                                            name: 'Realisasi',
                                            data: this.chartData
                                        }])
                                    }
                                }
                            }"
                            x-init="loadData()"
                            class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">

                            <div class="flex items-center justify-between">
                                <!-- Selector Tahun -->
                                <div class="flex items-center gap-0.5 w-fit rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">
                                    <template x-for="tahun in ['2023','2024','2025']">
                                        <button @click="selected = tahun; loadData();"
                                            :class="selected === tahun
                                                ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800'
                                                : 'text-gray-500 dark:text-gray-400'"
                                            class="px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white"
                                            x-text="tahun">
                                        </button>
                                    </template>
                                </div>

                                <span
                                    class="flex items-center gap-1 rounded-full bg-success-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500"
                                    x-text="`${persenRealisasi}%`">
                                    <svg class="fill-current" width="12" height="12" viewBox="0 0 12 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M5.31462 10.3761C5.45194 10.5293 5.65136 10.6257 5.87329 10.6257C5.8736 10.6257 5.8739 10.6257 5.87421 10.6257C6.0663 10.6259 6.25845 10.5527 6.40505 10.4062L9.40514 7.4082C9.69814 7.11541 9.69831 6.64054 9.40552 6.34754C9.11273 6.05454 8.63785 6.05438 8.34486 6.34717L6.62329 8.06753L6.62329 1.875C6.62329 1.46079 6.28751 1.125 5.87329 1.125C5.45908 1.125 5.12329 1.46079 5.12329 1.875L5.12329 8.06422L3.40516 6.34719C3.11218 6.05439 2.6373 6.05454 2.3445 6.34752C2.0517 6.64051 2.05185 7.11538 2.34484 7.40818L5.31462 10.3761Z"
                                            fill="" />
                                    </svg>
                                </span>
                            </div>

                            <!-- Content -->
                            <div class="mt-5 flex items-end justify-between">
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="`Realisasi Padi UMKM (${selected})`">
                                    </span>
                                    <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90"
                                        x-text="`Rp. ${Number(totalRealisasi).toLocaleString('id-ID')}`">
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <!-- REALISASI DALAM TAHUN END -->
                    </div>
                    <!-- TOTAL TARGET & REALISASI END-->

                    <!-- ====== REALISASI PERTAHUN (BAR CHART) -->
                    <div
                        class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                Realisasi PerTahun
                            </h3>
                        </div>
                        <button id="btnBackYear" 
                                class="hidden mt-3 px-4 py-2 bg-gray-200 rounded-lg">
                            Kembali ke Grafik Tahunan
                        </button>
                        <div id="chartbaryear" style="height: 360px;"></div>
                    </div>
                    <!-- ====== REALISASI PERTAHUN (BAR CHART) END -->
                </div>

                <div class="col-span-12 xl:col-span-5">
                    <!-- ====== REALISASI PERBULAN (PIE CHART) -->
                    <div class="rounded-2xl border border-gray-200 bg-gray-100 dark:border-gray-800 dark:bg-white/[0.03]">
                        <div class="shadow-default rounded-2xl bg-white px-5 pb-11 pt-5 dark:bg-gray-900 sm:px-6 sm:pt-6">
                            <div class="flex justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                        Realisasi Padi UMKM
                                    </h3>
                                    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                                        Realisasi Padi UMKM perbulannya dan total dalam satu tahun
                                    </p>
                                </div>

                                <!-- Dropdown Tahun -->
                                <div class="relative" id="dropdownDonutWrapper">
                                    <button onclick="toggleDonutDropdown()"
                                        class="text-gray-400 hover:text-gray-700 dark:hover:text-white">⋮</button>

                                    <div id="dropdownDonut"
                                        class="hidden absolute right-0 top-full z-40 w-40 space-y-1 rounded-2xl border border-gray-200 bg-white p-2 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark">

                                        <button onclick="loadDonut('2023'); toggleDonutDropdown()"
                                            class="block w-full px-3 py-2 rounded-lg hover:bg-gray-100">2023</button>

                                        <button onclick="loadDonut('2024'); toggleDonutDropdown()"
                                            class="block w-full px-3 py-2 rounded-lg hover:bg-gray-100">2024</button>

                                        <button onclick="loadDonut('2025'); toggleDonutDropdown()"
                                            class="block w-full px-3 py-2 rounded-lg hover:bg-gray-100">2025</button>

                                    </div>
                                </div>
                            </div>

                            <!-- Chart -->
                            <div class="relative h-[240px] bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                                <div id="chartDonutRealisasi" class="h-[240px]"></div>
                            </div>

                            <p class="mx-auto mt-1.5 w-full max-w-[380px] text-center text-sm text-gray-500 sm:text-base">
                                Total realisasi tahun <span id="donutYear">2025</span> sebesar:
                                <br>
                                <strong id="donutTotal">Rp 0</strong>
                            </p>

                        </div>
                    </div>
                    <!-- ====== REALISASI PERBULAN (PIE CHART) END -->
                </div>

                <div class="col-span-12">
                    <!-- ====== REALISASI VS TARGET KUMULATIF (LINE CHART) -->
                    <div 
                        class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6"
                        x-data="chartLine()"
                        x-init="loadChart()"
                    >

                        <!-- Tombol Tahun -->
                        <div class="flex justify-end mb-4">
                            <div class="inline-flex w-fit items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">
                                <template x-for="year in years" :key="year">
                                    <button 
                                        @click="selectedYear = year; loadChart();" 
                                        :class="selectedYear === year 
                                            ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' 
                                            : 'text-gray-500 dark:text-gray-400'"
                                        class="px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white"
                                        x-text="year">
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Chart -->
                        <div id="chartLine" class="h-[350px] w-full"></div>
                        <!-- ====== REALISASI VS TARGET KUMULATIF (LINE CHART) END -->
                    </div>
                </div>

                <div class="col-span-12">
                    <!-- ====== TRANSAKSI PEMBELIAN PADI (CYLINDER CHART) -->
                    <div 
                        class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03]"
                        x-data="chartCylinder()"
                        x-init="loadChart()"
                    >
                        <!-- Pilih Tahun -->
                        <div class="flex justify-end mb-4">
                            <select 
                                class="border rounded-lg px-3 py-2"
                                x-model="selectedYear"
                                @change="loadChart()"
                            >
                                <template x-for="y in years">
                                    <option :value="y" x-text="y"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Chart -->
                        <div id="chartCylinder" class="h-[350px] w-full"></div>

                        <!-- Modal Detail -->
                        <div 
                            x-show="showDetail"
                            class="fixed inset-0 bg-black/50 flex items-center justify-center"
                        >
                            <div class="bg-white rounded-xl p-5 w-[400px]">
                                <h2 class="text-lg font-bold mb-3">
                                    Detail Transaksi Bulan <span x-text="namaBulan[selectedMonth]"></span>
                                </h2>

                                <template x-for="item in detailData" :key="item.deskripsi">
                                    <div class="flex justify-between border-b py-1">
                                        <span x-text="item.deskripsi"></span>
                                        <span x-text="item.transaksi_padi"></span>
                                    </div>
                                </template>

                                <button 
                                    class="mt-4 px-4 py-2 bg-gray-200 rounded-lg"
                                    @click="showDetail = false"
                                >
                                    Tutup
                                </button>
                            </div>
                        </div>
                    <!-- ====== TRANSAKSI PEMBELIAN PADI (CYLINDER CHART) END -->
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- ===== Main Content End ===== -->

<!-- BAR Chart Script -->
<script>
let chartInstance;
let dataTahunan = null;
let dataBulananCache = {}; // cache biar tdk fetch ulang

// formatter angka custom
function formatAngka(v) {
    if (v >= 1_000_000_000_000) return (v / 1_000_000_000_000).toFixed(1).replace('.0','') + " T";   // triliun
    if (v >= 1_000_000_000)     return (v / 1_000_000_000).toFixed(1).replace('.0','') + " M";       // miliar
    if (v >= 1_000_000)         return (v / 1_000_000).toFixed(1).replace('.0','') + " Jt";          // juta
    if (v >= 1_000)             return (v / 1_000).toFixed(1).replace('.0','') + " Rb";              // ribu
    return v;
}

function loadChartYear() {
    fetch("/ajax/realisasi-all-year")
        .then(res => res.json())
        .then(data => {
            dataTahunan = data;

            chartInstance = Highcharts.chart("chartbaryear", {
                
                chart: { type: "column" },
                title: { text: "Realisasi vs Target per Tahun" },

                xAxis: {
                    categories: data.kategori,
                    title: { text: "Tahun" }
                },

                yAxis: {
                    title: { text: "Jumlah" },
                    labels: {
                        formatter: function () {
                            return formatAngka(this.value);
                        }
                    }
                },

                plotOptions: {
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    const tahun = this.category;
                                    loadChartMonth(tahun);  
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            inside: false,      // ⬅️ LABEL DI LUAR / ATAS BAR
                            verticalAlign: "bottom",
                            style: {
                                fontWeight: "bold",
                                color: "#000000",   // ⬅️ WARNA HITAM
                                textOutline: "0px"
                            },
                            formatter: function () {
                                return formatAngka(this.y);
                            }
                        }
                    }
                },

                series: [
                    { name: "Target", data: data.target.map(Number), color: "#FEB05D" },
                    { name: "Realisasi", data: data.realisasi.map(Number), color: "#5A7ACD" }
                ]
            });
        });
}

function loadChartMonth(tahun) {
    document.getElementById("btnBackYear").classList.remove("hidden");

    const namaBulan = [
            "", "Januari", "Februari", "Maret", "April", "Mei",
            "Juni", "Juli", "Agustus", "September", "Oktober",
            "November", "Desember"
    ];

    
    // Cek cache dulu, biar tidak fetch ulang
    if (dataBulananCache[tahun]) {
        return renderChartMonth(tahun, dataBulananCache[tahun]);
    }

    fetch(`/ajax/realisasi-tahun/${tahun}`)
        .then(res => res.json())
        .then(data => {
            // convert angka bulan ke nama bulan
            data.kategori = data.kategori.map(b => namaBulan[b]);

            dataBulananCache[tahun] = data;
            renderChartMonth(tahun, data);
        });
}

function renderChartMonth(tahun, data) {
    chartInstance.update({
        chart: { type: "column" },
        title: { text: `Target vs Realisasi per Bulan (${tahun})` },

        xAxis: {
            categories: data.kategori,
            title: { text: "Bulan" }
        },

        series: [
            { name: "Target", data: data.target.map(Number), color: "#FEB05D" },
            { name: "Realisasi", data: data.realisasi.map(Number), color: "#5A7ACD" }
        ]
    }, true, true);
}

document.getElementById("btnBackYear").addEventListener("click", function () {
    this.classList.add("hidden");
    loadChartYear();
});


// load pertama
loadChartYear();
</script>
<!-- BAR Chart Script END -->

<!-- Donut Chart Script -->
<script>
        let donutSelected = "2025";

        function toggleDonutDropdown() {
            document.getElementById("dropdownDonut").classList.toggle("hidden");
        }

        // klik di luar → tutup dropdown
        document.addEventListener("click", function (e) {
            if (!document.getElementById("dropdownDonutWrapper").contains(e.target)) {
                document.getElementById("dropdownDonut").classList.add("hidden");
            }
        });

        // Format Rupiah
        function rupiah(n) {
            return "Rp " + Number(n).toLocaleString("id-ID");
        }

        function loadDonut(tahun) {
            donutSelected = tahun;

            fetch(`/ajax/realisasi-bulanan/${tahun}`)
                .then(res => res.json())
                .then(data => {

                    // update teks total
                    document.getElementById("donutYear").innerText = tahun;
                    document.getElementById("donutTotal").innerText = rupiah(data.total);

                    // Data harus format Highcharts:
                    // [{name: "Januari", y: 12345}, ...]
                    const chartData = data.chart.map((item) => ({
                        name: item.name,
                        y: Number(item.value)
                    }));

                    Highcharts.chart('chartDonutRealisasi', {
                        chart: {
                            type: 'pie'
                        },
                        title: { text: '' },
                        plotOptions: {
                            pie: {
                                innerSize: '75%',
                                dataLabels: {
                                    enabled: true,
                                    format: '{point.name}<br>{point.percentage:.0f}%',
                                    style: { fontSize: '10px' }
                                }
                            }
                        },
                        series: [{
                            name: 'Realisasi',
                            data: chartData
                        }]
                    });

                });
        }

        // load awal
        loadDonut("2025");
</script>
<!-- Donut Chart Script END -->

<!-- Line Chart Script -->
<script>
function chartLine() {
    return {
        selectedYear: "2025",
        years: ["2023", "2024", "2025"],

        namaBulan: [
            "", "Januari", "Februari", "Maret", "April", "Mei",
            "Juni", "Juli", "Agustus", "September", "Oktober",
            "November", "Desember"
        ],

        loadChart() {
            fetch(`/ajax/realisasi-tahun/${this.selectedYear}`)
                .then(res => res.json())
                .then(data => {
                    Highcharts.chart('chartLine', {
                        chart: { type: "line" },
                        title: {
                            text: `Realisasi & Target Kumulatif (${this.selectedYear})`,
                            align: "left"
                        },
                        xAxis: {
                            categories: data.kategori.map(b => this.namaBulan[b])
                        },
                        yAxis: {
                            title: { text: "Jumlah (Kumulatif)" },
                            labels: {
                                formatter: function () {
                                    const v = this.value;
                                    if (v >= 1_000_000_000_000) return (v / 1_000_000_000_000).toFixed(1).replace('.0','') + ' T';
                                    if (v >= 1_000_000_000)     return (v / 1_000_000_000).toFixed(1).replace('.0','') + ' M';
                                    if (v >= 1_000_000)         return (v / 1_000_000).toFixed(1).replace('.0','') + ' Jt';
                                    if (v >= 1_000)             return (v / 1_000).toFixed(1).replace('.0','') + ' Rb';
                                    return v;
                                }
                            }
                        },
                        series: [
                            { name: "Realisasi (Kumulatif)", data: data.realisasi.map(Number) },
                            { name: "Target (Kumulatif)", data: data.target.map(Number) }
                        ]
                    });
                });
        }
    };
}
</script>
<!-- Line Chart Script END -->

<!-- Cylinder Chart Script -->
<script>
function chartCylinder() {
    return {
        years: ["2023", "2024", "2025"],
        selectedYear: "2025",

        namaBulan: [
            "", "Januari", "Februari", "Maret", "April", "Mei",
            "Juni", "Juli", "Agustus", "September", "Oktober",
            "November", "Desember"
        ],

        showDetail: false,
        selectedMonth: null,
        detailData: [],

        // Load Chart Utama
        loadChart() {
            fetch(`/ajax/pembelianchart?tahun=${this.selectedYear}`)
                .then(res => res.json())
                .then(data => {
                    Highcharts.chart('chartCylinder', {
                        chart: {
                            type: 'cylinder',
                            options3d: {
                                enabled: true,
                                alpha: 15,
                                beta: 15,
                                depth: 50,
                                viewDistance: 25
                            }
                        },

                        title: {
                            text: `Transaksi Pembelian Padi (${this.selectedYear})`
                        },

                        plotOptions: {
                            series: {
                                cursor: "pointer",
                                dataLabels: {
            enabled: true,
            inside: false,              // ⬅️ di luar / atas bar
            verticalAlign: 'bottom',
            y: -5,                      // ⬅️ naikkan sedikit ke atas
            style: {
                color: '#000000',       // ⬅️ HITAM
                fontWeight: 'bold',
                textOutline: '0px'
            },
            formatter: function () {
                const v = this.y;
                if (v >= 1_000_000_000_000) return (v / 1_000_000_000_000).toFixed(2) + ' T';
                if (v >= 1_000_000_000)     return (v / 1_000_000_000).toFixed(2) + ' M';
                if (v >= 1_000_000)         return (v / 1_000_000).toFixed(2) + ' Jt';
                if (v >= 1_000)             return (v / 1_000).toFixed(0) + ' Rb';
                return v;
            }
        },
                                point: {
                                    events: {
                                        click: (e) => {
                                            this.openDetail(e.point.bulan);
                                        }
                                    }
                                }
                            }
                        },

                        xAxis: {
                            categories: data.map(x => this.namaBulan[x.bulan])
                        },

                        series: [{
                            name: 'Transaksi',
                            colorByPoint: true,
                            data: data.map(x => ({
                                y: Number(x.total),
                                bulan: x.bulan
                            }))
                        }]
                    });
                });
        },
    };
}
</script>
<!-- Cylinder Chart Script END -->

@endsection