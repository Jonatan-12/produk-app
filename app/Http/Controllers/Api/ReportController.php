<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Produk;
use DB;

class ReportController extends Controller
{
    use ApiResponse;

    public function summary()
    {
        // 1. jumlah total produk
        $totalProduk = Produk::count();

        // 2. rata-rata harga (integer)
        $rataRataHarga = (int) Produk::avg('harga');

        // 3. produk dengan stok paling sedikit (bisa lebih dari 1 jika tie)
        $minStok = Produk::min('stok');
        $produkMinStok = Produk::where('stok', $minStok)->get();

        return $this->success([
            'total_produk' => $totalProduk,
            'rata_rata_harga' => $rataRataHarga,
            'produk_stok_tersedikit' => $produkMinStok
        ], 'Report ringkas');
    }

    // contoh query lebih efisien di Query Builder (jika dataset besar)
    public function summaryEfficient()
    {
        $totalProduk = Produk::selectRaw('count(*) as total')->value('total');
        $rataRataHarga = Produk::selectRaw('avg(harga) as avg_harga')->value('avg_harga');
        $minStokProduk = Produk::orderBy('stok','asc')->limit(1)->get();

        return $this->success([
            'total_produk' => (int) $totalProduk,
            'rata_rata_harga' => (int) $rataRataHarga,
            'produk_stok_tersedikit' => $minStokProduk
        ]);
    }
}
