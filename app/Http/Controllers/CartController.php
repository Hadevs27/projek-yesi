<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $items = [];
        $total = 0;

        if (count($cart) > 0) {
            $productIds = array_keys($cart);
            $products = Barang::whereIn('id_barang', $productIds)->get();
            
            foreach ($products as $product) {
                $qty = $cart[$product->id_barang];
                $subtotal = $qty * $product->harga_barang;
                $total += $subtotal;
                $items[] = (object) [
                    'product' => $product,
                    'qty' => $qty,
                    'subtotal' => $subtotal
                ];
            }
        }
        
        Session::put('cart_total', $total);

        return view('pages.cart', compact('items', 'total'));
    }

    public function add(Request $request)
    {
        $id = $request->input('id_barang');
        $qty = $request->input('jumlah_pesan', 1);
        
        $cart = Session::get('cart', []);
        $cart[$id] = $qty;
        Session::put('cart', $cart);

        return redirect('cart');
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');
        $nama = $request->input('nama');
        $harga = $request->input('harga');
        
        $cart = Session::get('cart', []);
        $qty = isset($cart[$id]) ? $cart[$id] : 1;
        
        $product = Barang::find($id);
        $max = $product ? $product->stok_barang : 100;
        
        return view('pages.cart_edit', compact('id', 'nama', 'harga', 'qty', 'max'));
    }

    public function deleteConfirm(Request $request)
    {
        $id = $request->input('id');
        return view('pages.cart_delete_confirm', compact('id'));
    }

    public function delete(Request $request)
    {
        $id = $request->input('id_barang');
        $cart = Session::get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }
        return redirect('cart');
    }
}
