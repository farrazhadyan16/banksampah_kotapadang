@extends('layouts.admin')

@section('main-content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    {{-- Rangkuman Ringkas --}}
    <div class="row">
        @php
            $cards = [
                ['title' => 'Total Nasabah', 'value' => $totalNasabah, 'color' => 'primary'],
                ['title' => 'Total Admin', 'value' => $totalAdmin, 'color' => 'success'],
                ['title' => 'Total Setoran', 'value' => $totalSetoran, 'color' => 'warning'],
                ['title' => 'Total Tarik Saldo', 'value' => $totalTarik, 'color' => 'danger'],
            ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-{{ $card['color'] }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-{{ $card['color'] }} text-uppercase mb-1">
                        {{ $card['title'] }}
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $card['value'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Stok & Transaksi Terbaru --}}
    <div class="row">
        {{-- Stok Sampah --}}
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Stok Sampah (Kg)</h6>
                </div>
                <div class="card-body">
                    @forelse ($sampahList as $sampah)
                        <p class="mb-1">{{ $sampah->jenis_sampah }} <span class="float-right">{{ $sampah->jumlah }} Kg</span></p>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ min($sampah->jumlah, 100) }}%" aria-valuenow="{{ $sampah->jumlah }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada data sampah.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Transaksi Terbaru --}}
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Transaksi Terbaru</h6>
                </div>
                <div class="card-body">
                    @forelse ($recentRiwayat as $item)
                        <div class="mb-2">
                            <strong>{{ strtoupper($item->jenis_transaksi) }}</strong> oleh 
                            <span class="text-info">{{ $item->nasabah->name ?? 'Tidak diketahui' }}</span>
                            <br>
                            <small class="text-muted">{{ $item->created_at->format('d M Y H:i') }}</small>
                            <hr class="my-2">
                        </div>
                    @empty
                        <p class="text-muted">Belum ada transaksi terbaru.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="row">
        {{-- Pie Chart: Distribusi Sampah --}}
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Sampah</h6>
                </div>
                <div class="card-body">
                    <canvas id="sampahPie"></canvas>
                </div>
            </div>
        </div>

        {{-- Bar Chart: Stok Sampah --}}
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Stok Sampah per Jenis</h6>
                </div>
                <div class="card-body">
                    <canvas id="sampahBar"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Line Chart: Transaksi Bulanan --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Transaksi Bulanan</h6>
                </div>
                <div class="card-body">
                    <canvas id="transaksiChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Line Chart: Transaksi Bulanan per Jenis Sampah --}}
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Bulanan per Jenis Sampah</h6>
            </div>
            <div class="card-body">
                <canvas id="chartJenis"></canvas>
            </div>
        </div>
    </div>
</div>


</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

    // Grafik Bulanan per Jenis Sampah
new Chart(document.getElementById('chartJenis'), {
    type: 'line',
    data: {
        labels: {!! json_encode($chartJenis['labels']) !!},
        datasets: {!! json_encode($chartJenis['datasets']) !!}
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
        }
    }
});

    // Pie Chart
    new Chart(document.getElementById('sampahPie'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($chartSampahPie['labels']) !!},
            datasets: [{
                data: {!! json_encode($chartSampahPie['data']) !!},
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
            }]
        }
    });

    // Bar Chart
    new Chart(document.getElementById('sampahBar'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartSampahBar['labels']) !!},
            datasets: [{
                label: 'Jumlah Sampah (Kg)',
                data: {!! json_encode($chartSampahBar['data']) !!},
                backgroundColor: '#4e73df'
            }]
        }
    });

    // Line Chart
    new Chart(document.getElementById('transaksiChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($chartTransaksi['labels']) !!},
            datasets: [
                {
                    label: 'Setoran',
                    data: {!! json_encode($chartTransaksi['setoran']) !!},
                    backgroundColor: 'rgba(28,200,138,0.4)',
                    borderColor: 'rgba(28,200,138,1)',
                    fill: false
                },
                {
                    label: 'Tarik',
                    data: {!! json_encode($chartTransaksi['tarik']) !!},
                    backgroundColor: 'rgba(231,74,59,0.4)',
                    borderColor: 'rgba(231,74,59,1)',
                    fill: false
                }
            ]
        }
    });
</script>
@endsection
