@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">📦 Riwayat Pesanan Saya</h4>

    @forelse($pesanans as $p)
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $p->kode_transaksi }}</strong>
                <span class="text-muted small ms-2">({{ $p->created_at->format('d M Y, H:i') }})</span>
            </div>
            <span class="badge 
                @if($p->status == 'Selesai') bg-success 
                @elseif($p->status == 'Ditolak') bg-danger 
                @else bg-primary @endif">
                {{ $p->status }}
            </span>
        </div>
        <div class="card-body">
            <ul class="mb-3">
                @foreach($p->details as $d)
                    <li>
                        {{ $d->obat->nama_obat }} — **{{ $d->jumlah }}x** (Rp {{ number_format($d->subtotal, 0, ',', '.') }})
                    </li>
                @endforeach
            </ul>
            
            <p class="mb-1"><strong>Total Harga:</strong> <span class="text-success fw-bold">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span></p>
            <p class="mb-1"><strong>Alamat Pengiriman:</strong> {{ $p->alamat_pengiriman }} (No. HP: {{ $p->no_hp }})</p>
            
            @if($p->foto_resep)
                <div class="mt-2">
                    <a href="{{ asset('storage/'.$p->foto_resep) }}" target="_blank" class="btn btn-sm btn-outline-info">
                        🔍 Lihat Foto Resep Dokter
                    </a>
                </div>
            @endif
        </div>
    </div>
    @empty
    <div class="alert alert-info text-center">
        Kamu belum pernah membuat pesanan obat. Yuk, belanja sekarang di katalog!
    </div>
    @endforelse
</div>
@endsection