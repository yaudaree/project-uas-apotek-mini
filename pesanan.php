<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model {
    protected $fillable = ['kode_transaksi', 'user_id', 'total_harga', 'alamat_pengiriman', 'no_hp', 'foto_resep', 'status'];
    public function user() { return $this->belongsTo(User::class); }
    public function details() { return $this->hasMany(DetailPesanan::class); }
}