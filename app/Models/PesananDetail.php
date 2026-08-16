<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PesananDetail extends Model
{
    use HasFactory;

    protected $table = 'tb_detail_pesanan';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;
    protected $fillable = ['id_pesanan', 'id_barang', 'jumlah_pesanan', 'subtotal_harga'];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
