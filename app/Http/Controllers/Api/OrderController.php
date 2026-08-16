<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Create a new order.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pesanan' => 'required|string|max:255',
            'no_hp_pesanan' => 'required|string|max:20',
            'alamat_pesanan' => 'required|string',
            'jenis_pembayaran' => 'required|in:COD,Transfer,QRIS',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:tb_barang,id_barang',
            'items.*.jumlah' => 'required|integer|min:1',
            'id_meja' => 'nullable|exists:tb_meja,id_meja'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $total_harga = 0;
            $items_to_insert = [];
            
            // Generate Order ID
            $order_id = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
            
            foreach ($request->items as $item) {
                $barang = Barang::lockForUpdate()->find($item['id_barang']);
                
                if ($barang->stok_barang < $item['jumlah']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok produk ' . $barang->nama_barang . ' tidak mencukupi.',
                        'error_code' => 'INSUFFICIENT_STOCK'
                    ], 400);
                }

                $subtotal = $barang->harga_barang * $item['jumlah'];
                $total_harga += $subtotal;

                // Kurangi stok
                $barang->stok_barang -= $item['jumlah'];
                $barang->save();

                $items_to_insert[] = [
                    'id_pesanan' => $order_id,
                    'id_barang' => $barang->id_barang,
                    'jumlah_pesanan' => $item['jumlah'],
                    'subtotal_harga' => $subtotal
                ];
            }

            // Hitung ongkir statis Rp. 10.000 jika COD, namun jika ada id_meja (makan di tempat), ongkir = 0.
            $ongkir = $request->id_meja ? 0 : 10000;
            $total_pembayaran = $total_harga + $ongkir;

            // Simpan Pesanan
            $pesanan = Pesanan::create([
                'id_pesanan' => $order_id,
                'nama_pesanan' => $request->nama_pesanan,
                'no_hp_pesanan' => $request->no_hp_pesanan,
                'alamat_pesanan' => $request->alamat_pesanan,
                'email_pesanan' => $request->email_pesanan ?? '-',
                'total_harga_pesanan' => $total_pembayaran,
                'status_pesanan' => 'Menunggu Pembayaran',
                'tanggal_pesanan' => date('Y-m-d'),
                'jenis_pembayaran' => $request->jenis_pembayaran,
                'id_meja' => $request->id_meja
            ]);

            // Simpan Detail
            foreach ($items_to_insert as $detail) {
                PesananDetail::create($detail);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => [
                    'order_number' => $order_id,
                    'total_pembayaran' => $total_pembayaran
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order details.
     */
    public function show($order_number)
    {
        $pesanan = Pesanan::with('detailPesanan.barang')->find($order_number);

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
                'error_code' => 'ORDER_NOT_FOUND'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data pesanan berhasil diambil',
            'data' => $pesanan
        ]);
    }

    /**
     * Track order by phone and ID.
     */
    public function track(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|string',
            'no_hp_pesanan' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak lengkap',
                'errors' => $validator->errors()
            ], 422);
        }

        $pesanan = Pesanan::with('detailPesanan.barang')
            ->where('id_pesanan', $request->order_number)
            ->where('no_hp_pesanan', $request->no_hp_pesanan)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan atau nomor HP salah',
                'error_code' => 'ORDER_NOT_FOUND'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil dilacak',
            'data' => $pesanan
        ]);
    }

    /**
     * Cancel an order.
     */
    public function cancel($order_number)
    {
        $pesanan = Pesanan::find($order_number);

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
                'error_code' => 'ORDER_NOT_FOUND'
            ], 404);
        }

        if ($pesanan->status_pesanan !== 'Menunggu Pembayaran') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat dibatalkan pada status ini',
                'error_code' => 'CANCELLATION_NOT_ALLOWED'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Restore stok
            $detail = PesananDetail::where('id_pesanan', $order_number)->get();
            foreach ($detail as $item) {
                $barang = Barang::find($item->id_barang);
                if ($barang) {
                    $barang->stok_barang += $item->jumlah_pesanan;
                    $barang->save();
                }
            }

            $pesanan->status_pesanan = 'Dibatalkan';
            $pesanan->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibatalkan',
                'data' => $pesanan
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Upload payment proof for QRIS.
     */
    public function uploadProof(Request $request, $order_number)
    {
        $validator = Validator::make($request->all(), [
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $pesanan = Pesanan::find($order_number);
        if (!$pesanan) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
        }

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('assets/bukti_pembayaran'), $filename);

            $pesanan->bukti_pembayaran = $filename;
            $pesanan->status_pesanan = 'Menunggu Pembayaran'; // atau 'Menunggu Verifikasi' jika ada
            $pesanan->save();

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diunggah'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal mengunggah bukti'], 500);
    }
}
