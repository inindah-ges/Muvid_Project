<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Backend\Selling;
use App\Models\Backend\Testimonial;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with([
            'selling.user' => function ($query) {
                $query->select('id', 'name', 'email'); 
            }
        ])->latest()
            ->limit(5) 
            ->get(['selling_id', 'rate', 'comment']);

        return view('frontend.service.index', [
            'testimonials' => $testimonials
        ]);
    }
    
    public function store(Request $request)
    {
        //validasi input
        $data = $request->validate([
            'invoice' => 'required|max:255',
            'rate' => 'required|in:1,2,3,4,5',
            'comment' => 'nullable|max:255'
        ]);

        //cek ada atau tidak transaksinya
        $selling = Selling::where('invoice', $data['invoice'])->first();

        if (!$selling) {
            return redirect()->back()->with('error', 'Order not found');
        }

        //cek sudah ada reviewnya 
        $testimonial = Testimonial::where('selling_id', $selling->id)->first();

        if ($testimonial) {
            return redirect()->back()->with('error', 'Testimonial already exists');
        }

        try {
            Testimonial::create([
                'selling_id' => $selling->id,
                'rate' => $data['rate'],
                'comment' => $data['comment']
            ]);

            return redirect()->back()->with('success', 'Testimonial has been sent');
        } catch (\Exception $err) {
            return redirect()->back()->with('error', $err->getMessage());
        }
    }
}
