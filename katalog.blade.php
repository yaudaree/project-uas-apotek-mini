@extends('layouts.app')

@section('content')
<!-- Banner Sambutan / Hero Section -->
<div class="p-5 mb-4 bg-success text-white rounded-3 shadow-sm">
    <div class="container-fluid py-3">
        <h1 class="display-5 fw-bold">🏥 Selamat Datang di Apotek Mini Sehat</h1>
        <p class="col-md-8 fs-5">
            Solusi praktis pemenuhan kebutuhan obat harian, vitamin, dan konsultasi resep dokter secara online langsung dari rumah Anda. Cepat, aman, dan terpercaya.
        </p>
        <hr class="my-4 bg-white">
        <p class="mb-0">Butuh obat resep khusus? Anda dapat langsung mengunggah foto resep dokter saat melakukan pemesanan di keranjang belanja.</p>
    </div>
</div>

<!-- Bagian Filter & Judul Katalog -->
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h3 class="fw-bold text-dark">💊 Katalog Produk & Obat</h3>
        <p class="text-muted mb-0">Temukan berbagai pilihan obat berkualitas di sini.</p>
    </div>
    <div class="col-md-6 mt-3 mt-md-0">
        <form action="{{ route('home') }}" method="GET" class="input-group shadow-sm">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama obat..." value="{{ request('cari') }}">
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                <option value="Obat Bebas" {{ request('kategori') == 'Obat Bebas' ? 'selected' : '' }}>Obat Bebas</option>
                <option value="Obat Bebas Terbatas" {{ request('kategori') == 'Obat Bebas Terbatas' ? 'selected' : '' }}>Obat Bebas Terbatas</option>
                <option value="Obat Keras (Resep)" {{ request('kategori') == 'Obat Keras (Resep)' ? 'selected' : '' }}>Obat Keras (Resep)</option>
                <option value="Vitamin & Suplemen" {{ request('kategori') == 'Vitamin & Suplemen' ? 'selected' : '' }}>Vitamin & Suplemen</option>
            </select>
            <button class="btn btn-dark" type="submit">Filter</button>
        </form>
    </div>
</div>

<!-- Daftar Kartu Obat -->
<div class="row">
    @forelse($obats as $obat)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-secondary">{{ $obat->kategori }}</span>
                        @if($obat->perlu_resep)
                            <span class="badge bg-danger">Butuh Resep</span>
                        @else
                            <span class="badge bg-success">Bebas</span>
                        @endif
                    </div>
                    <h5 class="card-title text-success fw-bold">{{ $obat->nama_obat }}</h5>
                    <p class="card-text text-muted small flex-grow-1">{{ $obat->deskripsi }}</p>
                    <div class="mb-3">
                        <span class="fs-5 fw-bold text-dark">Rp {{ number_format($obat->harga, 0, ',', '.') }}</span><br>
                        <small class="text-muted">Stok tersedia: {{ $obat->stok }}</small>
                    </div>
                    
                    @auth
                        @if(auth()->user()->role === 'pembeli')
                            <form action="{{ route('keranjang.tambah', $obat->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 btn-sm fw-bold shadow-sm">🛒 Tambah ke Keranjang</button>
                            </form>
                        @else
                            <div class="alert alert-warning py-1 px-2 small text-center mb-0">Akun Admin (Login sebagai pembeli untuk belanja)</div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-success w-100 btn-sm fw-bold">Login untuk Membeli</a>
                    @endauth
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-warning text-center py-4">
                <h5>Tidak ada obat yang ditemukan.</h5>
                <p class="mb-0">Coba kata kunci atau kategori pencarian yang lain.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection