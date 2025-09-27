# Task 5: Debugging & Code Review

## Potongan kode

```php
$produk = Produk::where('nama', $nama);
return response()->json($produk);
```

---

## Bug yang ditemukan

- `Produk::where(...)` hanya menghasilkan **Query Builder**, bukan data.
- Jika langsung di-`return` ke `response()->json()`, yang dikembalikan adalah _builder object_, bukan hasil query.
- Akibatnya API tidak mengembalikan data produk yang diharapkan.

---

## Penjelasan masalah

- Pada Eloquent, method `where()` digunakan untuk menyusun query, bukan mengeksekusi query.
- Untuk benar-benar mendapatkan data, kita perlu menjalankan query tersebut dengan:
  - `->first()` → mengambil satu record pertama.
  - `->firstOrFail()` → ambil satu record, jika tidak ada → 404.
  - `->get()` → ambil banyak record (collection).
  - `->paginate()` → ambil dengan pagination.

---

## Solusi perbaikan

### 1) Jika hanya butuh satu produk (exact match)

```php
$produk = Produk::where('nama', $nama)->first();

if (! $produk) {
    return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
}

return response()->json(['success' => true, 'data' => $produk]);
```

Atau lebih singkat:

```php
$produk = Produk::where('nama', $nama)->firstOrFail();
return response()->json(['success' => true, 'data' => $produk]);
```

---

### 2) Jika ingin banyak produk (collection)

```php
$produks = Produk::where('nama', $nama)->get();
return response()->json(['success' => true, 'data' => $produks]);
```

---

### 3) Jika ingin pencarian fleksibel (LIKE)

```php
$produks = Produk::where('nama', 'like', "%{$nama}%")->get();
return response()->json(['success' => true, 'data' => $produks]);
```

---

### 4) Versi clean & siap pakai (controller method)

```php
public function searchByName(Request $request)
{
    $request->validate(['nama' => 'required|string']);

    $produks = Produk::where('nama', 'like', '%' . $request->nama . '%')->get();

    return response()->json([
        'success' => true,
        'count'   => $produks->count(),
        'data'    => $produks
    ], 200);
}
```
