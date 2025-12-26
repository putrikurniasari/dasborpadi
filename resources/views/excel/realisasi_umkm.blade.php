@extends('layouts.app', ['pageSlug' => 'realisasi-umkm'])
<x-slot:title>{{$title}}</x-slot:title>
@php
    $namaBulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];
@endphp
@section('page', 'uploadrealisasi')
@section('content')
    <main>
        <div class="space-y-6 p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
            <!-- Judul Halaman -->
            <div x-data="{ pageName: `Excel Realisasi Padi UMKM` }" class="mb-4">
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
            <div class="pc-content">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-chart animate__animated animate__fadeInUp">

                            <div class="card-body p-3">

                                {{-- Notifikasi sukses & error --}}
                                @if(session('success'))
                                    <div class="flex items-start gap-4 rounded-xl border border-success-300 bg-success-50 p-4 mt-2 dark:border-success-900/40 dark:bg-success-900/20"
                                        id="alertMessage">

                                        <!-- Icon Success -->
                                        <div class="-mt-0.5 text-success-500">
                                            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C1.90186 17.5772 6.423 22.0984 12.0001 22.0984C17.5772 22.0984 22.0984 17.5772 22.0984 12.0001C22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM15.6197 10.7395C15.9712 10.388 15.9712 9.81819 15.6197 9.46672C15.2683 9.11525 14.6984 9.11525 14.347 9.46672L11.1894 12.6243L9.6533 11.0883C9.30183 10.7368 8.73198 10.7368 8.38051 11.0883C8.02904 11.4397 8.02904 12.0096 8.38051 12.3611L10.553 14.5335C10.7217 14.7023 10.9507 14.7971 11.1894 14.7971C11.428 14.7971 11.657 14.7023 11.8257 14.5335L15.6197 10.7395Z" />
                                            </svg>
                                        </div>

                                        <!-- Text -->
                                        <div>
                                            <h4 class="mb-1 text-sm font-semibold text-success-600 dark:text-success-400">
                                                Berhasil
                                            </h4>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ session('success') }}
                                            </p>
                                        </div>

                                    </div>
                                @endif


                                @if(session('error'))
                                    <div class="flex items-start gap-4 rounded-xl border border-warning-500 bg-warning-50 p-4 mt-2 dark:border-warning-500/30 dark:bg-warning-500/15"
                                        id="alertMessage">
                                        <!-- Icon Error -->
                                        <div class="-mt-0.5 text-warning-500 dark:text-orange-400">
                                            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M3.6501 12.0001C3.6501 7.38852 7.38852 3.6501 12.0001 3.6501C16.6117 3.6501 20.3501 7.38852 20.3501 12.0001C20.3501 16.6117 16.6117 20.3501 12.0001 20.3501C7.38852 20.3501 3.6501 16.6117 3.6501 12.0001ZM12.0001 1.8501C6.39441 1.8501 1.8501 6.39441 1.8501 12.0001C1.8501 17.6058 6.39441 22.1501 12.0001 22.1501C17.6058 22.1501 22.1501 17.6058 22.1501 12.0001C22.1501 6.39441 17.6058 1.8501 12.0001 1.8501ZM10.9992 7.52517C10.9992 8.07746 11.4469 8.52517 11.9992 8.52517H12.0002C12.5525 8.52517 13.0002 8.07746 13.0002 7.52517C13.0002 6.97289 12.5525 6.52517 12.0002 6.52517H11.9992C11.4469 6.52517 10.9992 6.97289 10.9992 7.52517ZM12.0002 17.3715C11.586 17.3715 11.2502 17.0357 11.2502 16.6215V10.945C11.2502 10.5308 11.586 10.195 12.0002 10.195C12.4144 10.195 12.7502 10.5308 12.7502 10.945V16.6215C12.7502 17.0357 12.4144 17.3715 12.0002 17.3715Z"
                                                    fill="" />
                                            </svg>
                                        </div>

                                        <!-- Text -->
                                        <div>
                                            <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                                                Gagal
                                            </h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ session('error') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <hr>

                                <div class="space-y-5 sm:space-y-6">
                                    <div
                                        class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                                        <div class="px-5 py-4 sm:px-6 sm:py-5 flex items-center justify-between">
                                            <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                                                Daftar File Realisasi UMKM
                                            </h3>
                                            <div class="flex items-center gap-3">
                                                <button
                                                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-bold text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 border-gray-200 dark:border-gray-800"
                                                    onclick="confirmDownloadTemplate()" style="border-radius:12px;">

                                                    Download Template

                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        fill="currentColor" viewBox="0 0 24 24" class="fill-current">
                                                        <path
                                                            d="M12 3a1.25 1.25 0 0 1 1.25 1.25v8.19l2.72-2.72a1.25 1.25 0 1 1 1.77 1.77l-5 5a1.25 1.25 0 0 1-1.77 0l-5-5a1.25 1.25 0 1 1 1.77-1.77l2.72 2.72V4.25A1.25 1.25 0 0 1 12 3Z" />
                                                        <path
                                                            d="M4 17.75A1.25 1.25 0 0 1 5.25 16.5h13.5a1.25 1.25 0 1 1 0 2.5H5.25A1.25 1.25 0 0 1 4 17.75Z" />
                                                    </svg>


                                                </button>
                                                <button
                                                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-bold text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 border-gray-200 dark:border-gray-800"
                                                    id="openUploadModal">

                                                    Upload Excel +

                                                </button>
                                            </div>
                                        </div>
                                        <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6 ">
                                            <!-- ====== Table Six Start -->
                                            <div
                                                class="rounded-2xl overflow-hidden  border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                                                <div class="max-w-full overflow-x-auto">
                                                    <table class="min-w-full">
                                                        <!-- table header start -->
                                                        <thead>
                                                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                                                <th class="px-5 py-3 sm:px-6">
                                                                    <div class="flex items-center">
                                                                        <p
                                                                            class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                                            No
                                                                        </p>
                                                                    </div>
                                                                </th>
                                                                <th class="px-5 py-3 sm:px-6">
                                                                    <div class="flex items-center">
                                                                        <p
                                                                            class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                                            Nama File
                                                                        </p>
                                                                    </div>
                                                                </th>
                                                                <th class="px-5 py-3 sm:px-6">
                                                                    <div class="flex items-center">
                                                                        <p
                                                                            class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                                            Bulan
                                                                        </p>
                                                                    </div>
                                                                </th>
                                                                <th class="px-5 py-3 sm:px-6">
                                                                    <div class="flex items-center">
                                                                        <p
                                                                            class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                                            Tahun
                                                                        </p>
                                                                    </div>
                                                                </th>
                                                                <th class="px-5 py-3 sm:px-6">
                                                                    <div class="flex items-center">
                                                                        <p
                                                                            class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                                            Aksi
                                                                        </p>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <!-- table header end -->

                                                        <!-- table body start -->
                                                        <tbody id="tbodyRealisasi"
                                                            class="divide-y divide-gray-100 dark:divide-gray-800">
                                                            @forelse($files as $index => $file)
                                                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                                                    <td class="px-5 py-4 sm:px-6">
                                                                        <div class="flex items-center">
                                                                            <p
                                                                                class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                                                {{ $index + 1 }}
                                                                            </p>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 py-4 sm:px-6">
                                                                        <div class="flex items-center">
                                                                            <p
                                                                                class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                                                {{ basename($file->file_excel) }}
                                                                            </p>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 py-4 sm:px-6">
                                                                        <div class="flex items-center">
                                                                            <p
                                                                                class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                                                {{ $namaBulan[$file->bulan] ?? '-' }}
                                                                            </p>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 py-4 sm:px-6">
                                                                        <div class="flex items-center">
                                                                            <p
                                                                                class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                                                {{ $file->tahun }}
                                                                            </p>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 py-4 sm:px-6">
                                                                        <div class="d-flex flex-column gap-2">
                                                                            <button
                                                                                class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 border-gray-200 dark:border-gray-800"
                                                                                onclick="confirmDownloadFile('{{ asset('storage/' . $file->file_excel) }}', '{{ basename($file->file_excel) }}'); return false;">

                                                                                Download

                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="20" height="20" fill="currentColor"
                                                                                    viewBox="0 0 24 24" class="fill-current">
                                                                                    <path
                                                                                        d="M12 3a1.25 1.25 0 0 1 1.25 1.25v8.19l2.72-2.72a1.25 1.25 0 1 1 1.77 1.77l-5 5a1.25 1.25 0 0 1-1.77 0l-5-5a1.25 1.25 0 1 1 1.77-1.77l2.72 2.72V4.25A1.25 1.25 0 0 1 12 3Z" />
                                                                                    <path
                                                                                        d="M4 17.75A1.25 1.25 0 0 1 5.25 16.5h13.5a1.25 1.25 0 1 1 0 2.5H5.25A1.25 1.25 0 0 1 4 17.75Z" />
                                                                                </svg>

                                                                            </button>

                                                                            <button
                                                                                class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-error-500 shadow-theme-xs hover:bg-gray-200"
                                                                                onclick="confirmDelete({{ $file->id }})"
                                                                                style="border-radius:12px;">

                                                                                Hapus

                                                                                <svg class="fill-current" width="20" height="20"
                                                                                    viewBox="0 0 20 20"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <path fill-rule="evenodd"
                                                                                        clip-rule="evenodd"
                                                                                        d="M7.5 2C7.22386 2 7 2.22386 7 2.5V3H4.5C4.22386 3 4 3.22386 4 3.5C4 3.77614 4.22386 4 4.5 4H15.5C15.7761 4 16 3.77614 16 3.5C16 3.22386 15.7761 3 15.5 3H13V2.5C13 2.22386 12.7761 2 12.5 2H7.5ZM5 6C4.72386 6 4.5 6.22386 4.5 6.5V15.5C4.5 16.8807 5.61929 18 7 18H13C14.3807 18 15.5 16.8807 15.5 15.5V6.5C15.5 6.22386 15.2761 6 15 6H5ZM8.5 9C8.77614 9 9 9.22386 9 9.5V14.5C9 14.7761 8.77614 15 8.5 15C8.22386 15 8 14.7761 8 14.5V9.5C8 9.22386 8.22386 9 8.5 9ZM11.5 9C11.7761 9 12 9.22386 12 9.5V14.5C12 14.7761 11.7761 15 11.5 15C11.2239 15 11 14.7761 11 14.5V9.5C11 9.22386 11.2239 9 11.5 9Z" />
                                                                                </svg>


                                                                            </button>

                                                                            <form id="delete-form-{{ $file->id }}"
                                                                                action="{{ route('delete.realisasi', $file->id) }}"
                                                                                method="POST" class="d-none">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                            </form>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="text-center text-muted">Belum ada
                                                                        file diupload</td>
                                                                </tr>
                                                            @endforelse
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal Upload Excel --}}
        <!-- BEGIN MODAL -->
        <div class="fixed inset-0 items-center justify-center hidden p-5 overflow-y-auto modal z-99999" id="eventModal">
            <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
            <div
                class="modal-dialog modal-dialog-scrollable modal-lg no-scrollbar relative flex w-full max-w-[700px] flex-col overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-11">
                <!-- close btn -->
                <button
                    class="modal-close-btn transition-color absolute right-5 top-5 z-999 flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-gray-700 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300 sm:h-11 sm:w-11">
                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z"
                            fill="" />
                    </svg>
                </button>

                <div class="flex flex-col px-2 overflow-y-auto modal-content custom-scrollbar">
                    <div class="modal-header">
                        <h5 class="mb-2 font-semibold text-gray-800 modal-title text-theme-xl dark:text-white/90 lg:text-2xl"
                            id="eventModalLabel">
                            Upload File Excel
                        </h5>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Lengkapi semua data, pastikan bulan dan tahun belum pernah di upload sebelumnya.
                        </p>
                    </div>
                    <form action="{{ route('upload.realisasi') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mt-8 modal-body">
                            <div>
                                <div>
                                    <label for="bulanSelect"
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Bulan
                                    </label>
                                    <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                        <select name="bulan" id="bulanSelect" required
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                            :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                            @change="isOptionSelected = true">
                                            <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                Pilih Bulan
                                            </option>
                                            <option value="1" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                Januari
                                            </option>
                                            <option value="2" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                Februari
                                            </option>
                                            <option value="3" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                Maret
                                            </option>
                                            <option value="4" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                April
                                            </option>
                                            <option value="5" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                Mei
                                            </option>
                                            <option value="6" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                Juni
                                            </option>
                                            <option value="7" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                Juli
                                            </option>
                                            <option value="8" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                Agustus
                                            </option>
                                            <option value="9" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                September
                                            </option>
                                            <option value="10" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                Oktober
                                            </option>
                                            <option value="11" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                November
                                            </option>
                                            <option value="12" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                Desember
                                            </option>
                                    </div>
                                    </select>
                                    <span
                                        class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <br>
                            <div>
                                <label for="tahunInput"
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Tahun
                                </label>
                                <input id="event-title" type="number" min="2000" max="2100" name="tahun" id="tahunInput"
                                    placeholder="Masukkan Tahun"
                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                            </div>
                        </div>
                    </form>

                    <br>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Upload file
                        </label>
                        <input type="file" name="file_excel" id="fileExcelInput" accept=".xlsx,.xls" required
                        class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400" />
                        @error('file_excel')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 mt-6 modal-footer sm:justify-end">
                        <button type="button"
                            class="btn modal-close-btn bg-danger-subtle text-danger flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto"
                            data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit"
                            class="btn btn-primary btn-add-event flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                            Upload Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Script untuk menampilkan nama file --}}
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const fileInput = document.getElementById('fileExcelInput');
                const fileName = document.getElementById('fileName');

                fileInput.addEventListener('change', function () {
                    if (fileInput.files.length > 0) {
                        fileName.textContent = `File dipilih: ${fileInput.files[0].name}`;
                    } else {
                        fileName.textContent = '';
                    }
                });
            });
        </script>

        {{-- Script untuk tampilkan nama file & tombol hapus --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const fileInput = document.getElementById('fileExcelInput');
                const fileInfo = document.getElementById('fileInfo');

                fileInput.addEventListener('change', function () {
                    fileInfo.innerHTML = ''; // reset isi

                    if (fileInput.files.length > 0) {
                        const fileName = fileInput.files[0].name;
                        const fileDisplay = document.createElement('div');
                        fileDisplay.classList.add('d-flex', 'align-items-center', 'justify-content-between');

                        fileDisplay.innerHTML = `
                                                                                                                                    <small class="text-success"><i class="fa fa-file-excel me-2"></i>${fileName}</small>
                                                                                                                                    <button type="button" id="clearFileBtn" class="btn btn-sm btn-outline-danger ms-2">
                                                                                                                                        <i class="fa fa-times"></i>
                                                                                                                                    </button>
                                                                                                                                `;

                        fileInfo.appendChild(fileDisplay);

                        // tombol hapus
                        const clearBtn = document.getElementById('clearFileBtn');
                        clearBtn.addEventListener('click', function () {
                            fileInput.value = ''; // kosongkan input file
                            fileInfo.innerHTML = '<small class="text-muted">Inputkan Excel terlebih dahulu</small>';
                        });
                    } else {
                        fileInfo.innerHTML = '<small class="text-muted">Inputkan Excel terlebih dahulu</small>';
                    }
                });
            });
        </script>
    </main>
    @push('js')
        <script>
            // === Loading SweetAlert saat form upload ===
            document.addEventListener("DOMContentLoaded", function () {
                const formUpload = document.querySelector('form[action="{{ route('upload.realisasi') }}"]');
                if (formUpload) {
                    formUpload.addEventListener('submit', function (e) {
                        Swal.fire({
                            title: 'Sedang Mengupload data realisasi padi...',
                            html: 'Mohon tunggu beberapa saat',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    });
                }
            });

            // === Konfirmasi Hapus ===
            window.confirmDelete = function (id) {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Data realisasi Padi yang terkait dengan file ini juga akan dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${id}`).submit();
                        Swal.fire({
                            title: 'Sedang menghapus data realisasi padi...',
                            html: 'Mohon tunggu beberapa saat',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    }
                });
            }

            // === Konfirmasi sebelum download template ===
            window.confirmDownloadTemplate = function () {
                Swal.fire({
                    title: 'Download Template Excel?',
                    html: `
                                                                                                                                                                                                                                <div class="text-start" style="color: black;">
                                                                                                                                                                                                                                    <p style="color: black;"><b>Pastikan Anda:</b></p>
                                                                                                                                                                                                                                    <ul style="text-align: left; padding-left: 18px;">
                                                                                                                                                                                                                                        <li style="color: black;">Mengisi data <b>hanya pada sheet <u>KERTAS KERJA</u></b>.</li>
                                                                                                                                                                                                                                        <li style="color: black;">Mengikuti petunjuk pengisian sesuai <b>catatan (note)</b> di dalam file template.</li>
                                                                                                                                                                                                                                        <li style="color: black;">Tidak mengubah struktur kolom atau format bawaan.</li>
                                                                                                                                                                                                                                    </ul>
                                                                                                                                                                                                                                </div>

                                                                                                                                                                                                                        `,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Download Template',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const fileUrl = "{{ asset('storage/template/Template Realisasi.xlsx') }}";
                        const link = document.createElement('a');
                        link.href = fileUrl;
                        link.download = 'Template Realisasi.xlsx';
                        link.click();

                        Swal.fire({
                            title: 'Sedang mendownload...',
                            html: '<span style="color:black;">Template akan segera diunduh.</span>',
                            timer: 1800,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    }
                });
            }

            // === Konfirmasi Download File dari Tabel ===
            // === Konfirmasi Download File dari Tabel ===
            window.confirmDownloadFile = function (fileUrl, fileName) {
                Swal.fire({
                    title: 'Download File?',
                    text: `Apakah Anda ingin mendownload file "${fileName}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Download',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Sedang mendownload...',
                            html: '<span style="color:black;">File akan segera diunduh.</span>',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Buat link download
                        const link = document.createElement('a');
                        link.href = fileUrl;
                        link.download = fileName;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                });
            }

        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const alertBox = document.getElementById("alertMessage");
                if (alertBox) {
                    setTimeout(() => {
                        alertBox.style.opacity = "0";
                        alertBox.style.transition = "opacity 0.5s ease";

                        setTimeout(() => {
                            alertBox.remove();
                        }, 500); // tunggu transisi selesai
                    }, 3000); // 3 detik
                }
            });
        </script>
        <script>
            const openUploadModal = document.getElementById("openUploadModal");
            const eventModal = document.getElementById("eventModal");
            const closeButtons = document.querySelectorAll(".modal-close-btn");

            // Klik tombol → tampilkan modal
            openUploadModal.addEventListener("click", () => {
                eventModal.classList.remove("hidden");
                eventModal.classList.add("flex");
            });

            // Klik close (X dan area abu-abu)
            closeButtons.forEach(btn => {
                btn.addEventListener("click", () => {
                    eventModal.classList.add("hidden");
                    eventModal.classList.remove("flex");
                });
            });
        </script>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alertBox = document.getElementById("alertMessage");
            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.opacity = "0";
                    alertBox.style.transition = "opacity 0.5s ease";

                    setTimeout(() => {
                        alertBox.remove();
                    }, 500); // tunggu transisi selesai
                }, 3000); // 3 detik
            }
        });
        </script>
    @endpush
@endsection