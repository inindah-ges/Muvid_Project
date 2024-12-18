<?php

namespace App\Http\Services\Backend;

use App\Models\Backend\Product;

class ProductService
{
    public function select($paginate = null)
    {
        if ($paginate) {
            return Product::with('category:id,name')->latest()->select('id', 'uuid', 'name', 'category_id', 'description', 'price', 'status', 'stock', 'image')->paginate($paginate);
        }

        return Product::latest()->get();
    }

    public function selectFirstBy($column, $value)
    {
        return Product::with('category:id,name')->where($column, $value)->firstOrFail();
    }
}
