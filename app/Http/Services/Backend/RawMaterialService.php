<?php

namespace App\Http\Services\Backend;

use App\Models\Backend\RawMaterial;

class RawMaterialService
{
    public function select($paginate = null)
    {
        if ($paginate) {
            return RawMaterial::with('category:id,name')->latest()->select('id', 'uuid', 'name', 'category_id', 'stock', 'unit')->paginate($paginate);
        }

        return RawMaterial::latest()->get();
    }

    public function selectFirstBy($column, $value)
    {
        return RawMaterial::with('category:id,name')->where($column, $value)->firstOrFail();
    }
}
