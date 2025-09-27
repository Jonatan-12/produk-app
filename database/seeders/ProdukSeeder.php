<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        Produk::insert([
            ['nama'=>'Produk A','harga'=>100000,'stok'=>10,'created_at'=>now(),'updated_at'=>now()],
            ['nama'=>'Produk B','harga'=>150000,'stok'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['nama'=>'Produk C','harga'=>200000,'stok'=>2,'created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
