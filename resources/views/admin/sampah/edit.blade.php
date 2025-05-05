@extends('layouts.app') {{-- atau sesuaikan dengan layout yang kamu pakai --}}

@section('content')
<div class="container">
    <h2>Edit Data Sampah</h2>

    <form action="{{ route('sampah.update', $sampah->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis Sampah</label>
            <input type="text" class="form-control" name="jenis" value="{{ $sampah->jenis }}">
        </div>

        <div class="mb-3">
            <label for="harga_satuan" class="form-label">Harga Satuan</label>
            <input type="number" class="form-control" name="harga_satuan" value="{{ $sampah->harga_satuan }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
