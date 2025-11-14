<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use App\Models\Keranjang;

class ProdukUserController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
        }

        $produks = $query->latest()->get();

        if ($request->ajax()) {
            $html = '';
            foreach ($produks as $produk) {
                $html .= '
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm product-card rounded-4">
                            <div class="ratio ratio-1x1 overflow-hidden rounded-top">
                                <img src="' . asset('storage/' . $produk->gambar) . '" class="w-100 h-100 object-fit-cover transition-scale" alt="' . e($produk->nama) . '">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <small class="text-muted"><i class="bi bi-tag me-1"></i>' . e(optional($produk->kategori)->name) . '</small>
                                <h6 class="fw-semibold mt-1 text-truncate" title="' . e($produk->nama) . '">' . e($produk->nama) . '</h6>
                                <p class="fw-bold text-dark mb-3">
                                    <i class="bi bi-cash-coin me-1"></i>Rp ' . number_format($produk->harga, 0, ',', '.') . '
                                </p>
                                <a href="' . url('/user/produk/' . $produk->id) . '" class="btn btn-outline-dark w-100 mb-2 rounded-pill">
                                    <i class="bi bi-eye me-1"></i> Lihat Detail
                                </a>
                                <form action="' . url('/user/keranjang') . '" method="POST">
                                    ' . csrf_field() . '
                                    <input type="hidden" name="produk_id" value="' . $produk->id . '">
                                    <button type="submit" class="btn btn-dark w-100 rounded-pill">
                                        <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                ';
            }

            return response($html);
        }

        return view('user.produk.produk', compact('produks'));
    }

    public function tambahKeKeranjang(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
        ]);

        $userId = Auth::id();

        $existing = Keranjang::where('user_id', $userId)
            ->where('produk_id', $request->produk_id)
            ->first();

        if ($existing) {
            $existing->jumlah += 1;
            $existing->save();
        } else {
            Keranjang::create([
                'user_id' => $userId,
                'produk_id' => $request->produk_id,
                'jumlah' => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        return view('user.produk.produk_list', compact('produk'));
    }
}
