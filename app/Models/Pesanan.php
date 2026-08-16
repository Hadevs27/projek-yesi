<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'tb_pesanan';
    protected $primaryKey = 'id_pesanan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'id_pesanan', 'nama_pesanan', 'alamat_pesanan', 'no_hp_pesanan', 'email_pesanan', 
        'total_harga_pesanan', 'status_pesanan', 'tanggal_pesanan', 'jenis_pembayaran', 'snap_token', 'bukti_pembayaran'
    ];

    public function detailPesanan()
    {
        return $this->hasMany(PesananDetail::class, 'id_pesanan', 'id_pesanan');
    }
}
