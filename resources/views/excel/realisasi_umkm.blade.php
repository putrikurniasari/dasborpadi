@extends('layouts.app', ['pageSlug' => 'realisasi-umkm'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-chart">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center filter-header">
                    <div>
                        <h5 class="card-category mb-0">Halaman Input Excel</h5>
                        <h2 class="card-title mb-0">Realisasi Padi UMKM</h2>
                    </div>
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
                <form action="{{ route('upload.realisasi') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file_excel">Upload File Excel</label>
                        <input type="file" class="form-control" name="file_excel" required>
                        @error('file_excel')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Upload</button>
                </form>

                <hr>

                {{-- Tabel daftar file --}}
                <h5 class="mt-4">Daftar File Realisasi UMKM</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Input</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($files as $index => $file)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $file->tanggal_input }}</td>
                                <td>
                                    <a href="{{ asset('storage/' . $file->file_excel) }}" target="_blank">Download</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Belum ada file diupload</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection
