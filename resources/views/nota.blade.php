@extends('layouts.admin')
@section('main-content')
<div class="container mt-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
            {{-- Status Transaksi --}}
            <div class="mb-4">
                @php $status = $riwayat->setoran->status ?? null; @endphp

                @if($status === 'Processing')
                    <i class="bi bi-hourglass-split text-warning" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-warning">Transaksi Diproses</h4>
                @elseif($status === 'Rejected')
                    <i class="bi bi-x-circle-fill text-danger" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-danger">Transaksi Ditolak</h4>
                @else
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-success">Transaksi Berhasil</h4>
                @endif
                <p class="text-muted">{{ $riwayat->created_at->format('d M Y | H:i:s') }} WIB</p>
            </div>

            <hr>

            {{-- Informasi Umum --}}
            <div class="text-start">
                <p><strong>Nomor Referensi:</strong> {{ $riwayat->id }}</p>
                <p><strong>Sumber Dana:</strong> {{ strtoupper($riwayat->nasabah->name ?? '-') }}<br>
                    {{ $riwayat->nasabah->no_rek ?? '**** **** **** ****' }}</p>
                <p><strong>Jenis Transaksi:</strong>
                    @if($riwayat->jenis_transaksi == 'tarik_saldo')
                        Penarikan Saldo
                    @elseif($riwayat->jenis_transaksi == 'setoran')
                        Penyetoran Sampah
                    @else
                        {{ ucfirst(str_replace('_', ' ', $riwayat->jenis_transaksi)) }}
                    @endif
                </p>

                <hr>

                @php
                    $nominal = 0;
                    if ($riwayat->jenis_transaksi === 'tarik_saldo') {
                        $nominal = $riwayat->tarikSaldo->jumlah ?? 0;
                    } elseif ($riwayat->jenis_transaksi === 'setoran') {
                        $nominal = $riwayat->setoran->total_harga ?? 0;
                    }
                @endphp

                {{-- Rincian Setoran --}}
                @if($riwayat->jenis_transaksi === 'setoran' && $riwayat->setoran)
                    <h5>Rincian Setoran Sampah</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Jenis Sampah</th>
                                <th>{{ $status === 'Completed' ? 'Jumlah/Kg' : 'Jumlah Satuan' }}</th>
                                <th>Harga/Kg</th>
                                @if($status === 'Completed')
                                    <th>Total</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalSetoran = 0; @endphp
                            @foreach ($riwayat->setoran->setoranDetail as $detail)
                                @php
                                    $jenis = $detail->sampah->jenis_sampah ?? 'Tidak diketahui';
                                    $jumlah = $status === 'Completed' ? $detail->berat_sampah : $detail->jumlah_sampah;
                                    $harga = $detail->harga_kg;
                                    $subtotal = $detail->total_harga;
                                    $totalSetoran += $subtotal;
                                @endphp
                                <tr>
                                    <td>{{ $jenis }}</td>
                                    <td>{{ $jumlah }} {{ $status === 'Completed' ? 'kg' : 'pcs' }}</td>
                                    <td>Rp {{ number_format($harga, 0, ',', '.') }}</td>
                                    @if($status === 'Completed')
                                        <td>Rp {{ number_format($subtotal, 2, ',', '.') }}</td>
                                    @endif
                                </tr>
                            @endforeach

                            @if($status === 'Completed')
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td><strong>Rp {{ number_format($totalSetoran, 2, ',', '.') }}</strong></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                @endif

                {{-- Rincian Tarik Saldo --}}
                @if($riwayat->jenis_transaksi === 'tarik_saldo' && $riwayat->tarikSaldo)
                    <h5 class="mt-4">Informasi Bank</h5>
                    <p><strong>Bank:</strong> {{ $riwayat->tarikSaldo->nama_bank }}</p>
                    <p><strong>Nomor Rekening:</strong> {{ $riwayat->tarikSaldo->rek_bank }}</p>
                    <p><strong>Atas Nama:</strong> {{ $riwayat->tarikSaldo->tujuan_bank }}</p>
                    <h5>Rincian Penarikan Saldo</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Jumlah Penarikan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Rp {{ number_format($riwayat->tarikSaldo->jumlah, 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($riwayat->tarikSaldo->created_at)->format('d M Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Nominal --}}
                    <p><strong>Nominal:</strong> Rp {{ number_format($riwayat->tarikSaldo->jumlah, 0, ',', '.') }}</p>
                @endif

                {{-- Nominal Hanya untuk Completed --}}
                @if($status === 'Completed')
                    <p><strong>Nominal:</strong> Rp {{ number_format($nominal, 2, ',', '.') }}</p>
                @endif
            </div>

            {{-- Verifikator di pojok kanan bawah --}}
            @if(in_array($status, ['Completed', 'Rejected']))
                @php
                    $verifikators = $riwayat->setoran->setoranDetail->pluck('verifikator')->unique()->filter()->implode(', ');
                @endphp
                <div class="d-flex justify-content-end">
                    <p class="text-muted"><strong>Verifikator:</strong> {{ $verifikators ?: '-' }}</p>
                </div>
            @endif

            {{-- Tombol --}}
            <div class="mt-4">
                <a href="#" onclick="window.print()" class="btn btn-outline-primary me-2">Bagikan</a>

                @php
                    switch($riwayat->jenis_transaksi) {
                        case 'setoran': $redirectUrl = route('setoran'); break;
                        case 'tarik_saldo': $redirectUrl = route('tarik.show'); break;
                        default: $redirectUrl = route('riwayat.show'); break;
                    }
                @endphp

                <a href="{{ $redirectUrl }}" class="btn btn-primary">OK</a>
            </div>
        </div>
    </div>
</div>
@endsection
