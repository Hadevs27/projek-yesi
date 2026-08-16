<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Barang extends Model
{
    use HasFactory;

    protected $table = 'tb_barang';
    protected $primaryKey = 'id_barang';
    public $timestamps = false;
    protected $fillable = ['id_kategori', 'nama_barang', 'deskripsi_barang', 'harga_barang', 'stok_barang', 'foto_barang'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function pesananDetail()
    {
        return $this->hasMany(PesananDetail::class, 'id_barang', 'id_barang');
    }
}
