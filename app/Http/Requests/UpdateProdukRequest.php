<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ganti jadi true
    }

    public function rules(): array
    {
        return [
            'nama'  => 'sometimes|required|string|max:255',
            'harga' => 'sometimes|required|integer|min:0',
            'stok'  => 'sometimes|required|integer|min:0',
        ];
    }
}
