@extends('layouts.admin')

@section('main-content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Table Sampah</h1>

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Sampah</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAME</th>
                            <th>Harga (satuan)</th>
                            <th>Jumlah</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sampahs as $sampah)
                            <tr>
                                <td>{{ str_pad($sampah->id, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $sampah->jenis_sampah }}</td>
                                <td>Rp. {{ number_format($sampah->harga_satuan, 0, ',', '.') }}</td>
                                <td>{{ $sampah->jumlah }}</td>
                                <td>
                                    <a href="{{ route('sampah.edit', $sampah->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ route('sampah.destroy', $sampah->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($sampahs->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data sampah</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
