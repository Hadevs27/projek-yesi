<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Barang;

class HomeController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('pages.home', compact('kategoris'));
    }

    public function loadProducts(Request $request)
    {
        $action = $request->input('action', 'show-all');
        if ($action === 'show-all') {
            $products = Barang::orderBy('id_barang', 'desc')->get();
        } else {
            $products = Barang::where('id_kategori', $action)->orderBy('id_barang', 'desc')->get();
        }
        return view('pages.data_product', compact('products'));
    }

    public function detail($id)
    {
        $product = Barang::findOrFail($id);
        return view('pages.detail_product', compact('product'));
    }
}
