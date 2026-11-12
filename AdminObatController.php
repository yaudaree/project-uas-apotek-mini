<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class AdminObatController extends Controller
{
    public function index()
    {
        $obats = Obat::latest()->get();
        $pesanans = Pesanan::with('user', 'details.obat')->latest()->get();
        
        return view('admin.dashboard', compact('obats', 'pesanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_obat' => 'required|unique:obats,kode_obat',
            'nama_obat' => 'required|string',
            'kategori' => 'required|string',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'tanggal_kadaluarsa' => 'required|date',
            'deskripsi' => 'nullable|string'
        ]);

        Obat::create([
            'kode_obat' => $request->kode_obat,
            'nama_obat' => $request->nama_obat,
            'kategori' => $request->kategori,
            'perlu_resep' => $request->has('perlu_resep'),
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
        ]);

        return back()->with('success', 'Obat baru berhasil ditambahkan!');
    }

    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}