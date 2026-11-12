<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model {
    protected $fillable = ['user_id', 'obat_id', 'jumlah'];
    public function obat() { return $this->belongsTo(Obat::class); }
}