<?php

namespace App\Http\Services\Backend;

use App\Models\Backend\Chef;

class ChefService
{
    public function select($paginate = null)
    {
        if ($paginate) {
            return Chef::latest()->paginate($paginate);
        }

        return Chef::latest()->get();
    }

    public function selectFirstBy($column, $value)
    {
        return Chef::where($column, $value)->firstOrFail();
    }
}
