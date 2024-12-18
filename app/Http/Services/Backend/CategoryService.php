<?php

namespace App\Http\Services\Backend;

use Illuminate\Support\Str;
use App\Models\Backend\Category;

class CategoryService
{
    public function select($column = null, $value = null)
    {
        if ($column) {
            return Category::where($column, $value)->select('id', 'uuid', 'name', 'slug')->firstOrFail();
        }

        return Category::latest()->get(['id', 'uuid', 'name', 'slug']);
    }

    public function selectPaginate($paginate = null)
    {
        if ($paginate) {
            return Category::latest()->paginate($paginate);
        }

        return Category::latest()->get();
    }

    public function selectFirstBy($column, $value)
    {
        return Category::where($column, $value)->firstOrFail();
    }

    public function create($data)
    {
        return Category::create($data);
    }

    public function update($data, $uuid)
    {
        $data['slug'] = Str::slug($data['name']);
        return Category::where('uuid', $uuid)->update($data);
    }
}
