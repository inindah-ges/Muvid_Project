<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Backend\SellingService;

class SellingController extends Controller
{
    public function __construct(
        private SellingService $sellingService
    ) 
    {
        $this->middleware(function ($request, $next) {
            if (!$request->user()->isAdmin() && !$request->user()->isOwner() && !$request->user()->isPelanggan()) {
                abort(403, 'Unauthorized action.');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('backend.selling.index', [
            'sellings' => $this->sellingService->getSellings($request->date_from, $request->date_to, 10),
            'dateFrom' => $request->date_from,
            'dateTo' => $request->date_to
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($uuid)
    {
        return view('backend.selling.show', [
            'selling' => $this->sellingService->getSellingByUuid($uuid)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function generateInvoice($uuid)
    {
        try {
            $pdf = $this->sellingService->generateInvoice($uuid);
            return $pdf->download('invoice-' . $uuid . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
