@extends('layouts.admin')

@section('main-content')

<h1 class="h3 mb-4 text-gray-800">Riwayat Transaksi</h1>

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
        <h6 class="m-0 font-weight-bold text-primary">Data Riwayat</h6>
    </div>
    <div class="card-body">

        <form method="GET" class="form-inline mb-4">
            <label class="mr-2">Dari</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control mr-3">

            <label class="mr-2">Sampai</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control mr-3">

            <label class="mr-2">Jenis Transaksi</label>
            <select name="jenis_transaksi" class="form-control mr-3">
                <option value="">-- Semua --</option>
                <option value="setor_sampah" {{ request('jenis_transaksi') == 'setor_sampah' ? 'selected' : '' }}>Setor Sampah</option>
                <option value="tarik_saldo" {{ request('jenis_transaksi') == 'tarik_saldo' ? 'selected' : '' }}>Tarik Saldo</option>
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Referensi</th>
                        @if (Auth::user()->role !== 'nasabah')
                            <th>Nama Nasabah</th>
                                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin')
                                <th>No Rekening</th>
                            @endif
                        @endif
                        <th>Jenis Transaksi</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayat as $data)
                        <tr>
                            <td>{{ $loop->iteration + ($riwayat->currentPage() - 1) * $riwayat->perPage() }}</td>
                            <td>{{ str_pad($data->id, '0', STR_PAD_LEFT) }}</td>
                            @if (Auth::user()->role !== 'nasabah')
                                <td>{{ $data->nasabah->name ?? '-' }}</td>
                                    @if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin')
                                        <td>{{ $data->nasabah->no_rek ?? '-' }}</td>
                                    @endif
                            @endif
                            <td>{{ ucfirst(str_replace('_', ' ', $data->jenis_transaksi)) }}</td>
                            <td>{{ $data->created_at->format('d-m-Y H:i:s') }}</td>
                            <td class="text-center">
                                <a href="{{ route('nota.show', $data->id) }}" class="btn btn-sm btn-info" title="Lihat Nota">
                                    <i class="fas fa-search fa-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role === 'nasabah' ? 3 : 4 }}" class="text-center">Tidak ada data riwayat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $riwayat->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
