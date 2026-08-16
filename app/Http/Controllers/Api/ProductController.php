<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get all categories.
     */
    public function categories()
    {
        $categories = Kategori::all();
        
        return response()->json([
            'success' => true,
            'message' => 'Data kategori berhasil diambil',
            'data' => $categories
        ]);
    }

    /**
     * Get all products.
     */
    public function index()
    {
        $products = Barang::with('kategori')->get();
        
        // Append full image URL
        $products->map(function($product) {
            $product->foto_url = url('assets/produk/' . $product->foto_barang);
            return $product;
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Data produk berhasil diambil',
            'data' => $products
        ]);
    }

    /**
     * Get products by category.
     */
    public function byCategory($id)
    {
        $products = Barang::with('kategori')->where('id_kategori', $id)->get();
        
        $products->map(function($product) {
            $product->foto_url = url('assets/produk/' . $product->foto_barang);
            return $product;
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Data produk berdasarkan kategori berhasil diambil',
            'data' => $products
        ]);
    }

    /**
     * Get product detail.
     */
    public function show($id)
    {
        $product = Barang::with('kategori')->find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
                'error_code' => 'PRODUCT_NOT_FOUND'
            ], 404);
        }
        
        $product->foto_url = url('assets/produk/' . $product->foto_barang);
        
        return response()->json([
            'success' => true,
            'message' => 'Detail produk berhasil diambil',
            'data' => $product
        ]);
    }

    /**
     * Get best selling products.
     */
    public function bestSellers()
    {
        $products = Barang::with('kategori')
            ->withSum('pesananDetail', 'jumlah_pesanan')
            ->orderByDesc('pesanan_detail_sum_jumlah_pesanan')
            ->take(5)
            ->get();
        
        $products->map(function($product) {
            $product->foto_url = url('assets/produk/' . $product->foto_barang);
            return $product;
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Data produk best seller berhasil diambil',
            'data' => $products
        ]);
    }
}
