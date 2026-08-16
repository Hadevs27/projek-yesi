<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->query('s');
        return view('pages.status', compact('id'));
    }

    public function status(Request $request)
    {
        $id_pesanan = strtoupper($request->input('kode_pesanan'));
        if (!$id_pesanan) {
            return redirect('status-pesanan');
        }

        $pesanan = Pesanan::with('detailPesanan.barang')->find($id_pesanan);

        return view('pages.status_pesanan', compact('pesanan', 'id_pesanan'));
    }

    public function updateStatusAjax(Request $request)
    {
        $id_pesanan = $request->input('id_pesanan');
        $pesanan = Pesanan::find($id_pesanan);
        if ($pesanan) {
            $pesanan->status_pesanan = 'Diproses';
            $pesanan->save();
            return "success";
        }
        return "error";
    }

    public function cancelOrder(Request $request)
    {
        $id_pesanan = $request->input('id_pesanan');

        DB::beginTransaction();
        try {
            $pesanan = Pesanan::with('detailPesanan')->find($id_pesanan);
            if ($pesanan) {
                foreach ($pesanan->detailPesanan as $detail) {
                    $barang = Barang::find($detail->id_barang);
                    if ($barang) {
                        $barang->stok_barang += $detail->jumlah_pesanan;
                        $barang->save();
                    }
                }
                $pesanan->status_pesanan = 'Dibatalkan';
                $pesanan->save();
            }

            DB::commit();
            return "success";
        } catch (\Exception $e) {
            DB::rollBack();
            return "error";
        }
    }

    public function printReceipt($id)
    {
        $id_pesanan = strtoupper($id);
        $pesanan = Pesanan::with('detailPesanan.barang')->find($id_pesanan);

        return view('pages.cetak_struk', compact('pesanan', 'id_pesanan'));
    }

    public function notificationHandler(Request $request)
    {
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$serverKey = '';
        
        try {
            $notif = new \Midtrans\Notification();
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
        
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        $pesanan = Pesanan::find($order_id);
        if (!$pesanan) {
            return response('Pesanan tidak ditemukan', 404);
        }

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $pesanan->status_pesanan = 'Challenge';
                } else {
                    $pesanan->status_pesanan = 'Diproses';
                }
            }
        } else if ($transaction == 'settlement') {
            $pesanan->status_pesanan = 'Diproses';
        } else if ($transaction == 'pending') {
            $pesanan->status_pesanan = 'Menunggu Pembayaran';
        } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            if ($pesanan->status_pesanan !== 'Dibatalkan') {
                $detail = PesananDetail::where('id_pesanan', $order_id)->get();
                foreach ($detail as $item) {
                    $barang = Barang::find($item->id_barang);
                    if ($barang) {
                        $barang->stok_barang += $item->jumlah_pesanan;
                        $barang->save();
                    }
                }
                $pesanan->status_pesanan = 'Dibatalkan';
            }
        }

        $pesanan->save();
        return response('OK', 200);
    }
}
