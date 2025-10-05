@extends('layouts.app', ['pageSlug' => 'pembelian-padi'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center filter-header">
                        <div>
                            <h5 class="card-category mb-0">Halaman Input Excel</h5>
                            <h2 class="card-title mb-0">Pembelian Padi</h2>
                        </div>
                        {{-- Tombol tampilkan form upload --}}
                        <button class="btn btn-success" type="button" data-bs-toggle="collapse"
                            data-bs-target="#formUploadExcel" aria-expanded="false" aria-controls="formUploadExcel">
                            <i class="fa fa-plus"></i> Upload Excel
                        </button>
                    </div>
                </div>

                <div class="card-body">

                    {{-- Notifikasi sukses --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
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
                                <th style="width: 200px;">Tanggal Upload</th>
                                <th style="width: 180px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($files as $index => $file)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ basename($file->file_excel) }}</td>
                                    <td>{{ $file->tanggal_input }}</td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column gap-2">
                                            <a href="{{ asset('storage/' . $file->file_excel) }}" target="_blank"
                                                class="btn btn-sm btn-info w-100">
                                                <i class="fa fa-download"></i> Download
                                            </a>
                                            <form action="{{ route('delete.realisasi', $file->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus file ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger w-100">
                                                    <i class="fa fa-trash"></i> Hapus
                                                </button>
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