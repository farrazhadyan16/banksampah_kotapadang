@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">Batalkan Setoran</h1>

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
        <h6 class="m-0 font-weight-bold text-primary">Data Setoran (Status: Processing)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if ($setorans->isEmpty())
                <div class="alert alert-info">Tidak ada setoran yang masih dalam status <strong>Processing</strong>.</div>
            @else
                <table class="table table-bordered" id="dataTable" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Referensi</th>
                            @if (Auth::user()->role !== 'nasabah')
                                <th>Nama Nasabah</th>
                                <th>No Rekening</th>
                            @endif
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($setorans as $index => $setoran)
                            <tr>
                                <td>{{ $setorans->firstItem() + $index }}</td>
                                <td>{{ str_pad($setoran->id_riwayat, 5, '0', STR_PAD_LEFT) }}</td>

                                @if (Auth::user()->role !== 'nasabah')
                                    <td>{{ $setoran->user->name ?? '-' }}</td>
                                    <td>{{ $setoran->user->no_rek ?? '-' }}</td>
                                @endif

                                <td>{{ \Carbon\Carbon::parse($setoran->created_at)->format('d-m-Y H:i') }}</td>
                                <td><span class="badge bg-primary text-white">{{ $setoran->status }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('setoran.batal.proses', $setoran->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan setoran ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Batalkan</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $setorans->withQueryString()->links() }}
                    <p class="text-muted text-sm">Menampilkan {{ $setorans->count() }} dari total {{ $setorans->total() }} data</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
