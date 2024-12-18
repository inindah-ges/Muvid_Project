<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        // all event
        $events = Event::orderBy('id', 'desc')
            ->limit(8)
            ->get(['name', 'image']);

        return view('frontend.event.index', [
            'events' => $events,
            'event_bazar'           => $this->getEvent(3),
            'event_live_musik'      => $this->getEvent(4),
            'event_nonton_bareng'   => $this->getEvent(5),
            'event_game_night'      => $this->getEvent(6),
        ]);
    }

    // show event by category
    private function getEvent(string $id)
    {
        return Event::with('category:id')
            ->latest()
            ->where('category_id', $id)
            ->limit(8)
            ->get(['category_id', 'name', 'image']);
    }
}
