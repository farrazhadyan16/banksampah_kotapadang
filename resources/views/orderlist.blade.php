@extends('layouts.admin')

@section('main-content')

<h1 class="h3 mb-4 text-gray-800">Order List</h1>

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
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Order</h6>
        <form method="GET" class="form-inline" action="{{ route('orderlist') }}">
            <input type="date" name="tanggal" class="form-control mr-2" value="{{ request('tanggal') }}">

            <select name="status" class="form-control mr-1">
                <option value="">-Status-</option>
                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <select name="sort_by" class="form-control mr-1">
                <option value="">-Berdasarkan-</option>
                <option value="id_riwayat" {{ request('sort_by') == 'id_riwayat' ? 'selected' : '' }}>No Referensi</option>
                <option value="id_nasabah" {{ request('sort_by') == 'id_nasabah' ? 'selected' : '' }}>No Rek</option>
                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal</option>
            </select>

            <select name="sort_direction" class="form-control mr-1">
                <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Naik</option>
                <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Turun</option>
            </select>

            <button class="btn btn-primary mr-2">Terapkan</button>
            <a href="{{ route('orderlist') }}" class="btn btn-danger">Reset</a>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Referensi</th>
                        <th>No Rek</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $index => $order)
                        <tr>
                            <td>{{ $orders->firstItem() + $index }}</td>
                            <td>{{ str_pad($order->id_riwayat, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $order->user->no_rek ?? '-' }}</td>
                            <td>{{ trim(($order->user->name ?? '') . ' ' . ($order->user->last_name ?? '')) }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->tanggal)->format('d M Y H:i:s') }}</td>
                            <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <form method="POST" action="{{ route('orderlist.updateStatus', $order->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="form-control
                                        {{ $order->status == 'Completed' ? 'bg-success text-white' :
                                           ($order->status == 'Processing' ? 'bg-primary text-white' :
                                           ($order->status == 'Rejected' ? 'bg-danger text-white' : 'bg-warning text-dark')) }}">
                                        <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="Rejected" {{ $order->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $orders->links() }}
                <p class="text-sm text-muted mt-2">Menampilkan {{ $orders->count() }} dari total {{ $orders->total() }} data</p>
            </div>
        </div>
    </div>
</div>

@endsection
