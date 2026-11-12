<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model {
    protected $fillable = ['kode_obat', 'nama_obat', 'kategori', 'perlu_resep', 'harga', 'stok', 'tanggal_kadaluarsa', 'deskripsi', 'gambar'];
}