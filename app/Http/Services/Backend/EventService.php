<?php

namespace App\Http\Services\Backend;

use App\Models\Backend\Event;

class EventService
{
    public function select($paginate = null)
    {
        if ($paginate) {
            return Event::with('category:id,name')->latest()->paginate($paginate);
        }

        return Event::with('category')->latest()->get();
    }

    public function selectFirstBy($column, $value)
    {
        return Event::with('category')->where($column, $value)->firstOrFail();
    }
}
