@extends('layouts.admin')
@section('main-content')
<h1 class="h3 mb-4 text-gray-800">Table Sampah</h1>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Sampah</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Jenis Sampah</th>
                        <th>Harga/Kg (Rp)</th>
                        <th>Jumlah/Kg</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($listsampah as $sampah)
                        <tr>
                            <td>{{ str_pad($sampah->id, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $sampah->jenis_sampah }}</td>
                            <td>Rp {{ number_format($sampah->harga_kg, 0, ',', '.') }}</td>
                            <td>{{ $sampah->jumlah }}</td>
                            <td>
                                {{-- Edit Button --}}
                                <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editModal{{ $sampah->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                {{-- Edit Modal --}}
                                <div class="modal fade" id="editModal{{ $sampah->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $sampah->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('sampah.update', $sampah->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Sampah</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Jenis Sampah</label>
                                                        <input type="text" name="jenis_sampah" class="form-control" value="{{ old('jenis_sampah', $sampah->jenis_sampah) }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Harga/Kg (Rp)</label>
                                                        <input type="number" name="harga_kg" class="form-control" value="{{ old('harga_kg', $sampah->harga_kg) }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Jumlah/Kg</label>
                                                        <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', $sampah->jumlah) }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data sampah</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
