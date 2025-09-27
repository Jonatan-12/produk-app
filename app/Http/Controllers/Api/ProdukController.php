<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Produk;
use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;

class ProdukController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $produks = Produk::orderBy('created_at','desc')->get();
        return $this->success($produks, 'List produk');
    }

    public function store(StoreProdukRequest $request)
    {
        $produk = Produk::create($request->validated());
        return $this->success($produk, 'Produk dibuat', 201);
    }

    public function show($id)
    {
        $produk = Produk::find($id);
        if (!$produk) return $this->error('Produk tidak ditemukan', 404);
        return $this->success($produk, 'Detail produk');
    }

    public function update(UpdateProdukRequest $request, $id)
    {
        $produk = Produk::find($id);
        if (!$produk) return $this->error('Produk tidak ditemukan', 404);
        $produk->update($request->validated());
        return $this->success($produk, 'Produk diupdate');
    }

    public function destroy($id)
    {
        $produk = Produk::find($id);
        if (!$produk) return $this->error('Produk tidak ditemukan', 404);
        $produk->delete();
        return $this->success(null, 'Produk dihapus');
    }
}
