<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    /**
     * Get table by code / validate QR.
     */
    public function validateQr($table_code)
    {
        $table = DB::table('tb_meja')
            ->where('kode_meja', $table_code)
            ->orWhere('token_qr', $table_code)
            ->first();

        if (!$table) {
            return response()->json([
                'success' => false,
                'message' => 'Meja tidak ditemukan atau QR tidak valid',
                'error_code' => 'TABLE_NOT_FOUND'
            ], 404);
        }

        if ($table->status !== 'ACTIVE') {
            return response()->json([
                'success' => false,
                'message' => 'Meja sedang tidak aktif atau dalam perbaikan',
                'error_code' => 'TABLE_INACTIVE'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Meja valid',
            'data' => $table
        ]);
    }
}
