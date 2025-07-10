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
        <form method="GET" class="form-inline" action="{{ route('orderlist.show') }}">
            <input type="date" name="tanggal" class="form-control mr-2" value="{{ request('tanggal') }}">

            <select name="status" class="form-control mr-1">
                <option value="">-Status-</option>
                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>

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
            <a href="{{ route('orderlist.show') }}" class="btn btn-danger">Reset</a>
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
                        <th>Tanggal Dibuat</th>
                        <th>Tanggal Diupdate</th>
                        <th>Berat/Kg</th>
                        <th>Harga</th>
                        <th>Verifikator</th>
                        <th>Status & Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $index => $order)
                        <tr>
                            <td>{{ $orders->firstItem() + $index }}</td>
                            <td>{{ str_pad($order->id_riwayat, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $order->user->no_rek ?? '-' }}</td>
                            <td>{{ trim(($order->user->name ?? '') . ' ' . ($order->user->last_name ?? '')) }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i:s') }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->updated_at)->format('d M Y H:i:s') }}</td>
                            <td>
                                {{ number_format($order->details->sum('berat_sampah'), 3, ',', '.') }} kg
                            </td>
                            <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td>
                                {{ $order->details->first()->verifikator ?? '-' }}
                            </td>                            
                            <td>
                                {{-- Status Dropdown --}}
                                <form method="POST" action="{{ route('orderlist.updateStatus', $order->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="form-control
    {{ $order->status == 'Completed' ? 'bg-success text-white' :
    ($order->status == 'Processing' ? 'bg-primary text-white' :
    ($order->status == 'Rejected' ? 'bg-danger text-white' :
    ($order->status == 'Cancelled' ? 'bg-secondary text-white' : 'bg-warning text-dark'))) }}">

                                        <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="Rejected" {{ $order->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>

                                    </select>
                                </form>

                                {{-- Tombol Input Berat --}}
                                @if ($order->status === 'Processing')
                                    <button class="btn btn-sm btn-warning mt-2" data-toggle="modal" data-target="#modalBerat{{ $order->id }}">
                                        Input Berat
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-secondary mt-2" disabled title="Status harus 'Processing' untuk input berat">
                                        Input Berat
                                    </button>
                                @endif

                                {{-- Modal Input Berat (Hanya untuk Processing) --}}
                                @if ($order->status === 'Processing')
                                <div class="modal fade" id="modalBerat{{ $order->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <form method="POST" action="{{ route('orderlist.updateBerat', $order->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Input Berat Sampah - Ref #{{ $order->id_riwayat }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @foreach($order->details as $detail)
                                                        <div class="mb-3">
                                                            <label>{{ $detail->sampah->jenis_sampah ?? '-' }}</label>
                                                            <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">
                                                            <input type="number" step="0.00001" min="0" class="form-control" name="berat_sampah[]" value="{{ $detail->berat_sampah ?? '' }}" required>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Simpan Berat</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data ditemukan.</td>
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
