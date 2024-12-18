<?php

namespace App\Http\Services\Backend;

use App\Models\Backend\Tax;

class TaxService
{
    public function select($paginate = null)
    {
        if ($paginate) {
            return Tax::latest()->paginate($paginate);
        }

        return Tax::latest()->get();
    }

    public function selectById($id)
    {
        return Tax::findOrFail($id);
    }
}
