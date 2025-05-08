@extends('layouts.admin')

@section('main-content')

<h1 class="h3 mb-4 text-gray-800">Table User Nasabah</h1>

{{-- Pesan sukses --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

{{-- Pesan error validasi --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Data tidak diperbarui:</strong>
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
        <h6 class="m-0 font-weight-bold text-primary">Data Nasabah</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($listnasabah as $nasabah)
                        <tr>
                            <td>{{ str_pad($nasabah->id,  '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $nasabah->name }}</td>
                            <td>{{ $nasabah->email }}</td>
                            <td>{{ $nasabah->no_hp }}</td>
                            <td>{{ $nasabah->alamat }}</td>
                            <td>
                                {{-- Tombol Edit --}}
                                <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editModal{{ $nasabah->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('user.destroy', $nasabah->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                                {{-- Modal Edit --}}
                                <div class="modal fade" id="editModal{{ $nasabah->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $nasabah->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('user.update', $nasabah->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $nasabah->id }}">Edit Nasabah</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Nama</label>
                                                        <input type="text" name="name" class="form-control" value="{{ old('name', $nasabah->name) }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ old('email', $nasabah->email) }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>No HP</label>
                                                        <input type="number" name="no_hp" class="form-control" value="{{ old('no_hp', $nasabah->no_hp) }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Alamat</label>
                                                        <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $nasabah->alamat) }}" required>
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
                            <td colspan="6" class="text-center">Tidak ada data nasabah</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
