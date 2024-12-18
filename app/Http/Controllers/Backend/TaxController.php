<?php

namespace App\Http\Controllers\Backend;


use App\Models\Backend\Tax;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TaxRequest;
use App\Http\Services\Backend\TaxService;

class TaxController extends Controller
{
    public function __construct(
        private TaxService $taxService
    ) 
    {
        $this->middleware(function ($request, $next) {
            if ($request->user()->isOwner() && !in_array($request->routeIs('panel.tax.index'), ['panel.tax.index'])) {
                abort(403, 'Unauthorized action.');
            }

            if (!$request->user()->isOwner() && !$request->user()->isAdmin()) {
                abort(403, 'Unauthorized action.');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.tax.index', [
            'taxes' => $this->taxService->select(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.tax.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaxRequest $request)
    {
        try {
            Tax::create($request->validated());

            return redirect()->route('panel.tax.index')->with('success', 'Tax successfully created');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('backend.tax.edit', [
            'tax' => $this->taxService->selectById($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaxRequest $request, $id)
    {
        try {
            $tax = $this->taxService->selectById($id);
            $tax->update($request->validated());

            return redirect()->route('panel.tax.index')->with('success', 'Tax successfully updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $tax = $this->taxService->selectById($id);
            $tax->delete();

            return response()->json([
                'message' => 'Tax successfully deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
