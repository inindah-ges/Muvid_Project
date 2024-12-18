<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\RawMaterialUsage\StoreRequest;
use App\Http\Services\Backend\RawMaterialUsageService;
use App\Models\Backend\RawMaterial;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class RawMaterialUsageController extends Controller
{
    public function __construct(
        private RawMaterialUsageService $usageService
    ) 
    {
        $this->middleware(function ($request, $next) {
            if ($request->user()->isOwner() && !in_array($request->routeIs('panel.raw-material-usage.index', 'panel.raw-material-usage.report', 'panel.raw-material-usage.export-report'), ['panel.raw-material-usage.index', 'panel.raw-material-usage.report', 'panel.raw-material-usage.export-report'])) {
                abort(403, 'Unauthorized action.');
            }

            if (!$request->user()->isOwner() && !$request->user()->isPegawai()) {
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
        return view('backend.raw-material-usage.index', [
            'usages' => $this->usageService->getUsageHistory(
                10,
                $request->raw_material_id ?? null,
                $request->date_from ?? null,
                $request->date_to ?? null
            ),
            'rawMaterials' => RawMaterial::with('category')->get(),
            'filters' => $request->only(['raw_material_id', 'date_from', 'date_to']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.raw-material-usage.create', [
            'rawMaterials' => RawMaterial::with('category')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            $this->usageService->recordUsage(
                $request->raw_material_id,
                $request->quantity_used,
                $request->date
            );

            return redirect()
                ->route('panel.raw-material-usage.index')
                ->with('success', 'Usage recorded successfully');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
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

    public function report(Request $request)
    {
        $report = null;

        if ($request->has(['date_from', 'date_to'])) {
            $report = $this->usageService->generateReport(
                $request->date_from,
                $request->date_to,
                $request->raw_material_id
            );
        }

        return view('backend.raw-material-usage.report', [
            'rawMaterials' => RawMaterial::with('category')->get(),
            'report' => $report,
            'filters' => $request->only(['raw_material_id', 'date_from', 'date_to']),
        ]);
    }

    public function exportReport(Request $request)
    {
        try {
            if (!$request->filled(['date_from', 'date_to'])) {
                return redirect()->back()->with('error', 'Please select date range');
            }

            return $this->usageService->exportReport(
                $request->date_from,
                $request->date_to,
                $request->raw_material_id
            );
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
