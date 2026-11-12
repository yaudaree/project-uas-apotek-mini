<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApotekController extends Controller
{
    // Menampilkan Katalog Produk untuk Pembeli / Umum
    public function index(Request $request)
    {
        $query = Obat::query();

        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama_obat', 'like', '%' . $request->cari . '%');
        }

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        $obats = $query->latest()->get();

        return view('pembeli.katalog', compact('obats'));
    }

    // Menambah Obat ke Keranjang
    public function tambahKeranjang(Request $request, $id)
    {
        $obat = Obat::findOrFail($id);
        
        $keranjang = Keranjang::where('user_id', Auth::id())
            ->where('obat_id', $id)
            ->first();

        if ($keranjang) {
            $keranjang->increment('jumlah');
        } else {
            Keranjang::create([
                'user_id' => Auth::id(),
                'obat_id' => $id,
                'jumlah' => 1
            ]);
        }

        return back()->with('success', 'Obat berhasil ditambahkan ke keranjang!');
    }

    // Menampilkan Halaman Keranjang Belanja
    public function lihatKeranjang()
    {
        $items = Keranjang::with('obat')
            ->where('user_id', Auth::id())
            ->get()
            ->filter(function ($item) {
                return $item->obat !== null;
            });

        $wajibResep = $items->contains(fn($i) => $i->obat->perlu_resep);
        $total = $items->sum(fn($i) => $i->obat->harga * $i->jumlah);

        return view('pembeli.keranjang', compact('items', 'wajibResep', 'total'));
    }

    // Menghapus Item dari Keranjang
    public function hapusKeranjang($id)
    {
        Keranjang::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    // Proses Checkout Pesanan
    public function checkout(Request $request)
    {
        $request->validate([
            'alamat' => 'required|string',
            'resep_dokter' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $items = Keranjang::with('obat')
            ->where('user_id', Auth::id())
            ->get()
            ->filter(fn($i) => $i->obat !== null);

        if ($items->isEmpty()) {
            return back()->with('error', 'Keranjang belanja Anda kosong.');
        }

        $wajibResep = $items->contains(fn($i) => $i->obat->perlu_resep);

        if ($wajibResep && !$request->hasFile('resep_dokter')) {
            return back()->with('error', 'Ada obat keras di keranjang yang wajib menyertakan foto resep dokter!');
        }

        $resepPath = null;
        if ($request->hasFile('resep_dokter')) {
            $resepPath = $request->file('resep_dokter')->store('resep', 'public');
        }

        $totalHarga = $items->sum(fn($i) => $i->obat->harga * $i->jumlah);

        // Buat Pesanan Baru
        $pesanan = Pesanan::create([
            'user_id' => Auth::id(),
            'total_harga' => $totalHarga,
            'status' => 'Pending',
            'alamat' => $request->alamat,
            'resep_dokter' => $resepPath
        ]);

        // Masukkan detail item pesanan
        foreach ($items as $item) {
            PesananDetail::create([
                'pesanan_id' => $pesanan->id,
                'obat_id' => $item->obat_id,
                'jumlah' => $item->jumlah,
                'harga' => $item->obat->harga
            ]);
        }

        // Kosongkan keranjang setelah checkout berhasil
        Keranjang::where('user_id', Auth::id())->delete();

        return redirect()->route('pesanan.saya')->with('success', 'Pesanan berhasil dibuat! Menunggu verifikasi admin.');
    }

    // Menampilkan Riwayat Pesanan Saya
    public function pesanansaya()
    {
        $pesanans = Pesanan::with('details.obat')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pembeli.pesanan_saya', compact('pesanans'));
    }
}