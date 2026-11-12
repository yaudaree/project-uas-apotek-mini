@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2>🛒 Keranjang Belanja Anda</h2>
        <p class="text-muted">Periksa kembali daftar obat sebelum melakukan checkout.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        @if($items->count() > 0)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Obat</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    @php 
                                        $subtotal = $item->obat->harga * $item->jumlah;
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $item->obat->nama_obat }}</span><br>
                                            <small class="text-muted">{{ $item->obat->kategori }}</small>
                                        </td>
                                        <td>Rp {{ number_format($item->obat->harga, 0, ',', '.') }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td class="fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('keranjang.hapus', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning text-center py-4">
                <h5>Keranjang belanja masih kosong.</h5>
                <a href="{{ route('home') }}" class="btn btn-success btn-sm mt-2">Mulai Belanja Obat</a>
            </div>
        @endif
    </div>

    <!-- Ringkasan Checkout & Upload Resep -->
    <div class="col-md-4">
        @if($items->count() > 0)
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white fw-bold">
                    📝 Konfirmasi & Pembayaran
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Total Pembayaran:</label>
                            <h4 class="text-success fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                        </div>

                        @if($wajibResep)
                            <div class="alert alert-danger p-2 small">
                                ⚠️ Ada obat keras dalam keranjang yang <strong>wajib menyertakan resep dokter</strong>.
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Unggah Foto Resep Dokter</label>
                                <input type="file" name="resep_dokter" class="form-control" required>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Pengiriman</label>
                            <textarea name="alamat" class="form-control" rows="2" placeholder="Masukkan alamat lengkap..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">🚀 Selesaikan Checkout</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection