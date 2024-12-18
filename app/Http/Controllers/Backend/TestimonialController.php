<?php

namespace App\Http\Controllers\Backend;
use Illuminate\Http\JsonResponse;
use App\Models\Backend\Testimonial;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($request->user()->isOwner() && !in_array($request->routeIs('panel.testimonial.index', 'panel.testimonial.show'), ['panel.testimonial.index', 'panel.testimonial.show'])) {
                abort(403, 'Unauthorized action.');
            }

            if (!$request->user()->isAdmin() && !$request->user()->isOwner()) {
                abort(403, 'Unauthorized action.');
            }

            return $next($request);
        });
    }

    public function index(): View 
    {
        return view('backend.testimonials.index', [
            'testimonials' => Testimonial::with('selling:id,invoice')->paginate(10),
        ]);
    }

    public function show(string $uuid): View
    {
        $testimonial = Testimonial::with([
            'selling' => function ($query) {
                $query->select('id', 'invoice', 'user_id') 
                    ->with(['user' => function ($query) {
                        $query->select('id', 'name', 'email', 'created_at'); 
                    }]);
            }
        ])->whereUuid($uuid)->firstOrFail();
        
        return view('backend.testimonials.show', [
            'testimonial' => $testimonial
        ]);
    }

    public function destroy(string $uuid): JsonResponse 
    {
        $testimonial = Testimonial::where('uuid', $uuid)->firstOrFail();
        $testimonial->delete();

        return response()->json([
            'message' => 'Testimonial has been deleted'
        ]);
    }
}
