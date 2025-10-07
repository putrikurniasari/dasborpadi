@extends('layouts.app', ['pageSlug' => 'realisasi-umkm'])
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
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-chart animate__animated animate__fadeInUp">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center filter-header">
                        <div>
                            <h5 class="card-category mb-0">Halaman Input Excel</h5>
                            <h2 class="card-title mb-0">Realisasi Padi UMKM</h2>
                        </div>
                        {{-- Tombol tampilkan form upload --}}
                        <button class="btn btn-success" type="button" data-bs-toggle="collapse"
                            data-bs-target="#formUploadExcel" aria-expanded="false" aria-controls="formUploadExcel">
                            <i class="fa fa-plus"></i> Upload Excel
                        </button>
                    </div>
                </div>

                <div class="card-body">

                    {{-- Notifikasi sukses & error --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert" id="alertMessage">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert" id="alertMessage">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Form upload --}}
                    <div class="collapse mt-3" id="formUploadExcel">
                        <div class="card card-body border shadow-sm p-4">
                            <form action="{{ route('upload.realisasi') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    {{-- Input Bulan --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="bulanSelect">Bulan</label>
                                            <select class="form-control" name="bulan" id="bulanSelect">
                                                <option value="">-- Pilih Bulan --</option>
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
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
                                        </div>
                                    </div>

                                    {{-- Input Tahun --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tahunInput">Tahun</label>
                                            <input type="number" class="form-control" name="tahun" id="tahunInput"
                                                placeholder="Masukkan Tahun" min="2000" max="2100">
                                        </div>
                                    </div>

                                    {{-- Input File Excel --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fileExcelInput">Upload File Excel</label>
                                            <div class="input-group">
                                                <label for="fileExcelInput"
                                                    class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center"
                                                    style="cursor: pointer;">
                                                    <i class="fa fa-upload me-2"></i> Pilih File Excel
                                                </label>
                                                <input type="file" class="form-control d-none" name="file_excel"
                                                    id="fileExcelInput" accept=".xlsx,.xls" required>
                                            </div>
                                            <small id="fileName" class="text-muted mt-1 d-block"></small>

                                            @error('file_excel')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end mt-3">
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <hr>

                    {{-- Tabel daftar file --}}
                    <h5 class="mt-4">Daftar File Realisasi UMKM</h5>
                    <table class="table table-bordered table-dark table-striped align-middle">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>Nama File</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <!-- <th style="width: 200px;">Tanggal Upload</th> -->
                                <th style="width: 180px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($files as $index => $file)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ basename($file->file_excel) }}</td>
                                    <td>{{ $namaBulan[$file->bulan] ?? '-' }}</td>
                                    <td>{{ $file->tahun }}</td>
                                    <!-- <td>{{ $file->tanggal_input }}</td> -->
                                    <td class="text-center">
                                        <div class="d-flex flex-column gap-2">
                                            <a href="{{ asset('storage/' . $file->file_excel) }}" target="_blank"
                                                class="btn btn-sm btn-info w-100">
                                                <i class="fa fa-download"></i> Download
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger w-100"
                                                onclick="confirmDelete({{ $file->id }})">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>

                                            <form id="delete-form-{{ $file->id }}"
                                                action="{{ route('delete.realisasi', $file->id) }}" method="POST"
                                                class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada file diupload</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

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
        </script>
    @endpush


    {{-- === Styling dark mode untuk dropdown, input tahun, dan tabel === --}}
    <style>
        /* === Dropdown Bulan === */
        #bulanSelect {
            background-color: #1e1f2b;
            color: #ffffff;
            border: 1px solid #555b7a;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        #bulanSelect:hover {
            border-color: #7a5ce0;
        }

        #bulanSelect:focus {
            border-color: #9b5de5;
            box-shadow: 0 0 0 2px rgba(155, 93, 229, 0.3);
        }

        #bulanSelect option {
            background-color: #1e1f2b;
            color: #ffffff;
        }

        /* === Input Tahun === */
        #tahunInput {
            background-color: #1e1f2b;
            color: #ffffff;
            border: 1px solid #555b7a;
            border-radius: 6px;
        }

        #tahunInput:focus {
            border-color: #9b5de5;
            box-shadow: 0 0 0 2px rgba(155, 93, 229, 0.3);
        }

        /* Hilangkan background putih di panah number */
        #tahunInput::-webkit-inner-spin-button,
        #tahunInput::-webkit-outer-spin-button {
            background: transparent;
            border: none;
        }

        #tahunInput[type=number] {
            -moz-appearance: textfield;
        }

        /* === Tabel Dark === */
        .table-dark {
            background-color: #1b1c29;
            color: #dcdce6;
        }

        .table-dark thead {
            background-color: #23243a;
            color: #aab3d3;
        }

        .table-dark tbody tr:hover {
            background-color: #2a2c44;
        }

        .btn-info {
            background-color: #4e73df;
            border: none;
        }

        .btn-info:hover {
            background-color: #3752b1;
        }

        .btn-danger {
            background-color: #e14d4d;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c13d3d;
        }
    </style>
@endsection