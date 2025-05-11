@extends('layouts.admin')

@section('main-content')
<div class="container mt-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                <h4 class="mt-3">Transaksi Berhasil</h4>
                <p class="text-muted">{{ $riwayat->created_at->format('d M Y | H:i:s') }} WIB</p>
            </div>
            <hr>
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
                        // Asumsi relasi 'tarikSaldo' ada di model Riwayat
                        $nominal = $riwayat->tarikSaldo->jumlah ?? 0;
                    } elseif ($riwayat->jenis_transaksi === 'setoran') {
                        // Jika satu transaksi bisa memiliki banyak detail setor_sampah
                        $nominal = $riwayat->setorSampah->total_harga ?? 0;
                    }
                @endphp

                <p><strong>Nominal:</strong> Rp {{ number_format($nominal, 0, ',', '.') }}</p>
            </div>

            <div class="mt-4">
                <a href="#" onclick="window.print()" class="btn btn-outline-primary me-2">Bagikan</a>
                <a href="{{ route('tarik.index') }}" class="btn btn-primary">OK</a>
            </div>
        </div>
    </div>
</div>
@endsection
