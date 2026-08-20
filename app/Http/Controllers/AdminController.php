<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Admin;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Session::has('id_admin')) {
            return redirect('admin/login');
        }

        $kategori = Kategori::count();
        $barang = Barang::count();
        $pesanan = Pesanan::count();

        $terlaris = \Illuminate\Support\Facades\DB::table('tb_detail_pesanan')
            ->join('tb_barang', 'tb_detail_pesanan.id_barang', '=', 'tb_barang.id_barang')
            ->select('tb_barang.nama_barang', \Illuminate\Support\Facades\DB::raw('SUM(tb_detail_pesanan.jumlah_pesanan) as total_terjual'))
            ->groupBy('tb_barang.id_barang', 'tb_barang.nama_barang')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $page_title = 'Dasbor';
        $page_description = 'Beranda';

        return view('admin.dashboard', compact('kategori', 'barang', 'pesanan', 'terlaris', 'page_title', 'page_description'));
    }

    public function login()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $username = $request->input('username');
        $password = md5($request->input('password'));

        $admin = Admin::where('username_admin', $username)->where('password_admin', $password)->first();

        if ($admin) {
            Session::put('id_admin', $admin->id_admin);
            return redirect('admin');
        } else {
            return redirect('admin/login')->with('error', 'Username atau Password salah!');
        }
    }

    public function logout()
    {
        Session::forget('id_admin');
        return redirect('admin/login');
    }

    // Kategori
    public function kategori()
    {
        if (!Session::has('id_admin')) return redirect('admin/login');
        $page_title = 'Kategori';
        $page_description = 'Data kategori produk/barang';
        return view('admin.kategori.index', compact('page_title', 'page_description'));
    }

    public function kategoriData()
    {
        $data = Kategori::orderBy('id_kategori', 'desc')->get();
        return view('admin.kategori.data', compact('data'));
    }

    public function kategoriEditForm(Request $request)
    {
        $id = $request->input('id');
        $kategori = Kategori::find($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function kategoriAction(Request $request)
    {
        $aksi = $request->input('aksi');
        if ($aksi == 'insert') {
            Kategori::create(['nama_kategori' => $request->input('kategori')]);
        } elseif ($aksi == 'update') {
            $id = $request->input('id');
            $kategori = Kategori::find($id);
            if ($kategori) {
                $kategori->nama_kategori = $request->input('kategori');
                $kategori->save();
            }
        }
        return redirect('admin/kategori');
    }

    public function kategoriDeleteConfirm(Request $request)
    {
        $id = $request->input('id');
        return view('admin.kategori.delete_confirm', compact('id'));
    }

    public function kategoriDelete(Request $request)
    {
        $id = $request->input('id');
        Kategori::destroy($id);
        return redirect('admin/kategori');
    }

    // Produk
    public function produk()
    {
        if (!Session::has('id_admin')) return redirect('admin/login');
        $page_title = 'Produk';
        $page_description = 'Data produk/barang';
        $kategori = Kategori::all();
        return view('admin.produk.index', compact('page_title', 'page_description', 'kategori'));
    }

    public function produkData()
    {
        $data = Barang::with('kategori')->orderBy('id_barang', 'desc')->get();
        return view('admin.produk.data', compact('data'));
    }

    public function produkEditForm(Request $request)
    {
        $id = $request->input('id');
        $produk = Barang::find($id);
        $kategori = Kategori::all();
        return view('admin.produk.edit', compact('produk', 'kategori'));
    }

    public function produkAction(Request $request)
    {
        $aksi = $request->input('aksi');
        
        if ($aksi == 'insert') {
            $foto = 'produk_default.png';
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $foto = time() . "_" . $file->getClientOriginalName();
                $file->move(public_path('assets/produk'), $foto);
            }

            Barang::create([
                'id_kategori' => $request->input('kategori'),
                'nama_barang' => $request->input('nama'),
                'harga_barang' => $request->input('harga'),
                'stok_barang' => $request->input('stok'),
                'deskripsi_barang' => $request->input('deskripsi'),
                'foto_barang' => $foto
            ]);

        } elseif ($aksi == 'update') {
            $id = $request->input('id');
            $produk = Barang::find($id);
            if ($produk) {
                $produk->id_kategori = $request->input('kategori');
                $produk->nama_barang = $request->input('nama');
                $produk->harga_barang = $request->input('harga');
                $produk->stok_barang = $request->input('stok');
                $produk->deskripsi_barang = $request->input('deskripsi');

                if ($request->hasFile('foto')) {
                    $file = $request->file('foto');
                    $foto = time() . "_" . $file->getClientOriginalName();
                    $file->move(public_path('assets/produk'), $foto);
                    
                    if ($produk->foto_barang != 'produk_default.png' && file_exists(public_path('assets/produk/' . $produk->foto_barang))) {
                        unlink(public_path('assets/produk/' . $produk->foto_barang));
                    }
                    $produk->foto_barang = $foto;
                }
                
                $produk->save();
            }
        }
        
        return redirect('admin/produk');
    }

    public function produkDeleteConfirm(Request $request)
    {
        $id = $request->input('id');
        return view('admin.produk.delete_confirm', compact('id'));
    }

    public function produkDelete(Request $request)
    {
        $id = $request->input('id');
        $produk = Barang::find($id);
        if ($produk) {
            if ($produk->foto_barang != 'produk_default.png' && file_exists(public_path('assets/produk/' . $produk->foto_barang))) {
                unlink(public_path('assets/produk/' . $produk->foto_barang));
            }
            $produk->delete();
        }
        return redirect('admin/produk');
    }

    // Pesanan
    public function pesanan()
    {
        if (!Session::has('id_admin')) return redirect('admin/login');
        $page_title = 'Pesanan';
        $page_description = 'Data pesanan';
        return view('admin.pesanan.index', compact('page_title', 'page_description'));
    }

    public function pesananData()
    {
        $data = Pesanan::orderBy('tanggal_pesanan', 'desc')->get();
        return view('admin.pesanan.data', compact('data'));
    }

    public function pesananDetail(Request $request)
    {
        $id = $request->input('id');
        $pesanan = Pesanan::with('detailPesanan.barang')->find($id);
        return view('admin.pesanan.detail', compact('pesanan'));
    }

    public function pesananUpdateStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status_pesanan');
        $pesanan = Pesanan::find($id);
        
        if ($pesanan) {
            if (($status == 'Ditolak' || $status == 'Dibatalkan') && $pesanan->status_pesanan != 'Ditolak' && $pesanan->status_pesanan != 'Dibatalkan') {
                $detail = PesananDetail::where('id_pesanan', $id)->get();
                foreach ($detail as $item) {
                    $barang = Barang::find($item->id_barang);
                    if ($barang) {
                        $barang->stok_barang += $item->jumlah_pesanan;
                        $barang->save();
                    }
                }
            }
            $pesanan->status_pesanan = $status;
            $pesanan->save();
        }
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Status updated']);
        }
        return redirect('admin/pesanan');
    }

    // Laporan
    public function laporan()
    {
        if (!Session::has('id_admin')) return redirect('admin/login');
        $page_title = 'Laporan Pesanan';
        $page_description = 'Laporan pesanan bulanan';
        return view('admin.laporan.index', compact('page_title', 'page_description'));
    }

    public function laporanData(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        
        $query = Pesanan::where('status_pesanan', 'Selesai');
        
        if ($bulan && $bulan != 'all') {
            $query->whereMonth('tanggal_pesanan', $bulan);
        }
        if ($tahun && $tahun != 'all') {
            $query->whereYear('tanggal_pesanan', $tahun);
        }
        
        $data = $query->orderBy('tanggal_pesanan', 'desc')->get();
        return view('admin.laporan.data', compact('data'));
    }

    public function laporanCetak(Request $request)
    {
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');
        
        $query = Pesanan::where('status_pesanan', 'Selesai');
        
        if ($bulan && $bulan != 'all') {
            $query->whereMonth('tanggal_pesanan', $bulan);
        }
        if ($tahun && $tahun != 'all') {
            $query->whereYear('tanggal_pesanan', $tahun);
        }
        
        $data = $query->orderBy('tanggal_pesanan', 'desc')->get();
        return view('admin.laporan.cetak', compact('data', 'bulan', 'tahun'));
    }
}
