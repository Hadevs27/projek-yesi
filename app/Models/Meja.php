<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Meja extends Model
{
    use HasFactory;

    protected $table = 'tb_meja';
    protected $primaryKey = 'id_meja';
    public $timestamps = true;

    protected $fillable = [
        'kode_meja',
        'nama_meja',
        'token_qr',
        'status'
    ];
}
