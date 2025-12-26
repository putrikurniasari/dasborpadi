@extends('layouts.app', ['pageSlug' => 'dashboard'])
@section('page', 'dashboardmain')
@section('content')
    <!-- ===== Main Content Start ===== -->
    <main>
        <div class="space-y-6 p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">

            <!-- Judul Halaman -->
            <div x-data="{ pageName: `Tabel Realisasi Dan Transaksi Padi`}">
                <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName"></h2>

                    <nav>
                        <ol class="flex items-center gap-1.5">
                            <li>
                                <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                                    href=" ">
                                    Home
                                    <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </li>
                            <li class="text-sm text-gray-800 dark:text-white/90" x-text="pageName"></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- Judul Halaman End -->

            <!-- Realisasi -->
            <div class="space-y-5 sm:space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                            Tabel Realisasi Padi UMKM
                        </h3>
                    </div>
                    <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between 
                            gap-4 p-4 border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

                            <!-- Pilih Tahun -->
                            <div class="flex items-center gap-3">
                                <span class="text-gray-500 dark:text-gray-400">Pilih Tahun</span>

                                <div class="relative inline-block">
                                    <select
                                        id="selectTahunRealisasi"
                                        class="h-9 w-full appearance-none rounded-lg border border-gray-300 bg-transparent py-2 pl-3 pr-10
                                        text-sm text-gray-800 shadow-theme-xs
                                        dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                        <option value="">Semua Tahun</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                    </select>

                                    <!-- Panah dalam box -->
                                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 
                                         text-gray-500 dark:text-gray-400">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M3.8335 5.9165L8.00016 10.0832L12.1668 5.9165" stroke="currentColor"
                                                stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- ====== Table Six Start -->
                        <div
                            class="overflow-hidden  border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                            <div class="max-w-full overflow-x-auto">
                                <table class="min-w-full">
                                    <!-- table header start -->
                                    <thead>
                                        <tr class="border-b border-gray-100 dark:border-gray-800">
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        No
                                                    </p>
                                                </div>
                                            </th>
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        Tahun
                                                    </p>
                                                </div>
                                            </th>
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        Bulan
                                                    </p>
                                                </div>
                                            </th>
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        Target
                                                    </p>
                                                </div>
                                            </th>
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        Realisasi
                                                    </p>
                                                </div>
                                            </th>
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        Selisih
                                                    </p>
                                                </div>
                                            </th>
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        Target
                                                    </p>
                                                </div>
                                            </th>
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        Realisasi
                                                    </p>
                                                </div>
                                            </th>
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        Selisih
                                                    </p>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <!-- table header end -->

                                    <!-- table body start -->
                                    <tbody id="tbodyRealisasi" class="divide-y divide-gray-100 dark:divide-gray-800">
                                        {{-- Data akan dimuat di sini melalui AJAX --}}
                                    </tbody>
                                    <!-- tabel body end -->
                                </table>
                            </div>
                        </div>
                        <!-- ====== Table Six End -->
                    </div>
                </div>
            </div>
            <!-- Realisasi end-->

            <!-- Tabel Transaksi -->
            <div class="space-y-5 sm:space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                            Tabel Transaksi Padi UMKM
                        </h3>
                    </div>
                    <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between 
                        gap-4 p-4 border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                            <div class="flex items-center gap-6"> 
                            <!-- Show Tahun -->
                            <div class="flex items-center gap-3">
                                <span class="text-gray-500 dark:text-gray-400">Jumlah Data</span>

                                <div class="relative inline-block">
                                    <select id="perPage"
                                        class="h-9 w-full appearance-none rounded-lg border border-gray-300 bg-transparent py-2 pl-3 pr-10
                                        text-sm text-gray-800 shadow-theme-xs
                                        dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>

                                    <!-- Panah DI DALAM Select -->
                                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 
                                         text-gray-500 dark:text-gray-400">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M3.8335 5.9165L8.00016 10.0832L12.1668 5.9165" stroke="currentColor"
                                                stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-gray-500 dark:text-gray-400">Pilih Tahun</span>

                                <div class="relative inline-block">
                                    <select
                                        id="selectTahun"
                                        class="h-9 w-full appearance-none rounded-lg border border-gray-300 bg-transparent py-2 pl-3 pr-10
                                    text-sm text-gray-800 shadow-theme-xs
                                    dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                        <option value="" selected>Semua Tahun</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                    </select>

                                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 
                                        text-gray-500 dark:text-gray-400">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M3.8335 5.9165L8.00016 10.0832L12.1668 5.9165" stroke="currentColor"
                                                stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>

                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-gray-500 dark:text-gray-400">Pilih Bulan</span>

                                <div class="relative inline-block">
                                    <select id="selectBulan"
                                        class="h-9 w-full appearance-none rounded-lg border border-gray-300 bg-transparent py-2 pl-3 pr-10
                                       text-sm text-gray-800 shadow-theme-xs
                                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                        <option value="" selected>Semua Bulan</option> 
                                        <option value="1">Januari</option>
                                        <option value="2">February</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>

                                    <!-- Panah DI DALAM Select -->
                                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 
                                         text-gray-500 dark:text-gray-400">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M3.8335 5.9165L8.00016 10.0832L12.1668 5.9165" stroke="currentColor"
                                                stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            </div>
                        </div>

                        <!-- ====== Table Six Start -->
                        <div
                            class="overflow-hidden  border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                            <div class="max-w-full overflow-x-auto">
                                <table class="min-w-full">
                                    <!-- table header start -->
                                    <thead>
                                        <tr class="border-b border-gray-100 dark:border-gray-800">
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        No
                                                    </p>
                                                </div>
                                            </th>
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        Bulan
                                                    </p>
                                                </div>
                                            </th>
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        Nama Kebun
                                                    </p>
                                                </div>
                                            </th>
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        Plafond OPL
                                                    </p>
                                                </div>
                                            </th>
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        Transaksi Padi
                                                    </p>
                                                </div>
                                            </th>
                                            <th class="px-5 py-3 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                        Persen Terhadap Plafond
                                                    </p>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <!-- table header end -->
                                    <!-- table body start -->
                                    <tbody id="tbodyTransaksi" class="divide-y divide-gray-100 dark:divide-gray-800">
                                        {{-- Data akan dimuat di sini melalui AJAX --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div
                            class="border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] py-4 pl-[18px] pr-4">
                            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between">
                                <div class="pb-3 xl:pb-0">
                                </div>
                                <div class="flex items-center justify-center gap-4 xl:justify-end">

    <!-- PREVIOUS BUTTON -->
    <button id="btnPrev" disabled
        class="flex h-10 items-center gap-2 rounded-lg border border-gray-300 bg-white p-2 sm:p-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z"
                fill="currentColor"></path>
        </svg>
    </button>

    <!-- PAGE NUMBER LIST -->
    <ul id="pageNumbers" class="flex items-center gap-1">
        <!-- nomor halaman akan diisi lewat JS -->
    </ul>

    <!-- NEXT BUTTON -->
    <button id="btnNext" disabled
        class="flex h-10 items-center gap-2 rounded-lg border border-gray-300 bg-white p-2 sm:p-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z"
                fill="currentColor"></path>
        </svg>
    </button>

</div>

                            </div>
                        </div>
                        <!-- ====== Table Six End -->
                    </div>
                </div>
            </div>
            <!-- Tabel Transaksi end-->
        </div>
    </main>
    <!-- ===== Main Content End ===== -->

<!-- Realisasi -->
<script>
    document.addEventListener("DOMContentLoaded", function () {

        // Mapping bulan angka → teks
        const namaBulan = [
            "", // index 0, agar 1 = Januari
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        function formatRibuan(angka) {
            if (angka === null || angka === undefined) return "-";
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        fetch("/ajax/realisasi")
            .then((res) => res.json())
            .then((data) => {
                let tbody = document.getElementById("tbodyRealisasi");
                tbody.innerHTML = "";

                let no = 1;

                data.forEach((item) => {
                    let bulanString = namaBulan[item.bulan] ?? item.bulan; 
                    // fallback jika null/undefined

                    let selisihBulanColor = item.selisih_bulan < 0 ? "text-error-500" : "text-success-500";
                    let selisihSdBulanColor = item.selisih_sd_bulan < 0 ? "text-error-500" : "text-success-500";


                    tbody.innerHTML += `
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${no++}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${item.tahun}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${bulanString}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${formatRibuan(item.target_bulan)}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${formatRibuan(item.realisasi_bulan)}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="${selisihBulanColor} text-theme-sm">${formatRibuan(item.selisih_bulan)}</p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${formatRibuan(item.target_sd_bulan)}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${formatRibuan(item.realisasi_sd_bulan)}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="${selisihSdBulanColor} text-theme-sm">${formatRibuan(item.selisih_sd_bulan)}</p>
                            </div>
                        </td>
                    </tr>
                `;
                });
            })
            .catch((err) => {
                console.error("Error:", err);
            });
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const tahunSelect = document.getElementById("selectTahunRealisasi");

    loadData(tahunSelect.value); // load default tahun 2023/2024/2025

    tahunSelect.addEventListener("change", function () {
        loadData(this.value); // load sesuai tahun dipilih
    });

    function loadData(tahun) {

        const namaBulan = [
            "", "Januari","Februari","Maret","April","Mei","Juni",
            "Juli","Agustus","September","Oktober","November","Desember"
        ];

        function formatRibuan(angka) {
            if (angka === null || angka === undefined) return "-";
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        fetch("/ajax/realisasi?tahun=" + tahun)
            .then((res) => res.json())
            .then((data) => {
                let tbody = document.getElementById("tbodyRealisasi");
                tbody.innerHTML = "";

                let no = 1;

                data.forEach((item) => {
                    const bulanString = namaBulan[item.bulan] ?? item.bulan;

                    const selisihBulanColor = item.selisih_bulan < 0 ? "text-error-500" : "text-success-500";
                    const selisihSdBulanColor = item.selisih_sd_bulan < 0 ? "text-error-500" : "text-success-500";

                    tbody.innerHTML += `
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${no++}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${item.tahun}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${bulanString}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${formatRibuan(item.target_bulan)}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${formatRibuan(item.realisasi_bulan)}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="${selisihBulanColor} text-theme-sm">${formatRibuan(item.selisih_bulan)}</p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${formatRibuan(item.target_sd_bulan)}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${formatRibuan(item.realisasi_sd_bulan)}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="${selisihSdBulanColor} text-theme-sm">${formatRibuan(item.selisih_sd_bulan)}</p>
                            </div>
                        </td>
                    </tr>
                    `;
                });
            });
    }

});
</script>


<!-- Pembelian -->
 <script>
    document.addEventListener("DOMContentLoaded", function () {

        const namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        const perPageSelect = document.getElementById("perPage");
        const selectTahun = document.getElementById("selectTahun");
        const selectBulan = document.getElementById("selectBulan");

        if (selectTahun) {
            selectTahun.addEventListener("change", function () {
                loadData(1, this.value, document.getElementById("selectBulan").value);
            });
        }

        if (selectBulan) {
            selectBulan.addEventListener("change", function () {
                loadData(1, document.getElementById("selectTahun").value, this.value);
            });
        }


        function formatRibuan(angka) {
            if (angka === null || angka === undefined) return "-";
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function formatPersen(nilai) {
            if (nilai === null || nilai === undefined) return "-";
            return (nilai * 1) + "%";
        }

        function getPersenColor(nilai) {
            let persen = nilai * 100;
            if (persen < 50) return "text-error-500";
            if (persen < 75) return "text-warning-500";
            return "text-success-500";
        }

        function loadData(page = 1, tahun = null, bulan = null) {
            let perPage = perPageSelect.value;

            if (!tahun) {
                tahun = document.getElementById("selectTahun")?.value;
            }
            if (!bulan) {
                bulan = document.getElementById("selectBulan")?.value;
            }

            fetch(`/ajax/pembelian?page=${page}&per_page=${perPage}&filter_tahun=${tahun}&filter_bulan=${bulan}`)
                .then(res => res.json())
                .then(response => {

                    let data = response.data;
                    let tbody = document.getElementById("tbodyTransaksi");
                    tbody.innerHTML = "";

                    let no = (response.current_page - 1) * response.per_page + 1;

                    data.forEach(item => {
                        let bulanString = namaBulan[item.bulan] ?? item.bulan;

                        let selisihBulanColor = item.selisih_bulan < 0 ? "text-error-500" : "text-success-500";
                        let selisihSdBulanColor = item.selisih_sd_bulan < 0 ? "text-error-500" : "text-success-500";


                        tbody.innerHTML += `
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        ${no++}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        ${bulanString}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        ${formatRibuan(item.deskripsi)}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        ${formatRibuan(item.plafond_opl)}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        ${formatRibuan(item.transaksi_padi)}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center">
                                    <p class="${getPersenColor(item.persen_terhadap_plafond)} text-theme-sm">
                                        ${formatPersen(item.persen_terhadap_plafond)}
                                    </p>
                                </div>
                            </td>
                        </tr>
                        `;
                    });
                    // render pagination button
                    renderPagination(response);
        });
    }

    function renderPagination(meta) {
    let btnPrev = document.getElementById("btnPrev");
    let btnNext = document.getElementById("btnNext");
    let pageNumbers = document.getElementById("pageNumbers");

    if (!btnPrev || !btnNext || !pageNumbers) return;

    // === PREVIOUS BUTTON ===
    btnPrev.disabled = !meta.prev_page_url;
    btnPrev.onclick = () => loadData(meta.current_page - 1, document.getElementById("selectTahun").value, document.getElementById("selectBulan").value);

    // === NEXT BUTTON ===
    btnNext.disabled = !meta.next_page_url;
    btnNext.onclick = () => loadData(meta.current_page + 1, document.getElementById("selectTahun").value, document.getElementById("selectBulan").value);

    // === PAGE NUMBER LIST ===
    pageNumbers.innerHTML = "";

    function createPageButton(i, active = false) {
        return `
            <li>
                <button 
                    onclick="loadData(${i}, document.getElementById('selectTahun').value, document.getElementById('selectBulan').value)"
                    class="
                        px-4 py-2 rounded flex w-10 items-center justify-center h-10 
                        rounded-lg text-sm font-medium
                        ${active 
                            ? 'bg-brand-500 text-white' 
                            : 'bg-white text-gray-700 hover:bg-blue-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500'}
                    "
                >
                    ${i}
                </button>
            </li>
        `;
    }

    // === LOGIC PAGINATION SHORT ===
    const current = meta.current_page;
    const last = meta.last_page;

    let pages = [];

    // Selalu tampilkan halaman pertama
    pages.push(1);

    // Tampilkan "..." setelah halaman 1
    if (current > 3) pages.push("...");

    // Tampilkan halaman di sekitar current (misal 5 6 7)
    for (let i = current - 1; i <= current + 1; i++) {
        if (i > 1 && i < last) pages.push(i);
    }

    // Tampilkan "..." sebelum halaman terakhir
    if (current < last - 2) pages.push("...");

    // Selalu tampilkan halaman terakhir
    if (last > 1) pages.push(last);

    // Render semua item
    pages.forEach(p => {
        if (p === "...") {
            pageNumbers.innerHTML += `
                <li>
                    <span class="px-3 py-2 text-gray-500">...</span>
                </li>
            `;
        } else {
            pageNumbers.innerHTML += createPageButton(p, p === current);
        }
    });
}


    // Event ketika jumlah per halaman diganti
    perPageSelect.addEventListener("change", () => loadData(1));

    // load awal
    loadData();

    // global biar pagination bisa dipanggil
    window.loadData = loadData;
});


</script>

@endsection