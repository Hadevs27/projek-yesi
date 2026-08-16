<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasFactory;
    protected $table = 'tb_admin';
    protected $primaryKey = 'id_admin';
    public $timestamps = false;
    protected $fillable = ['username_admin', 'password_admin'];
}
