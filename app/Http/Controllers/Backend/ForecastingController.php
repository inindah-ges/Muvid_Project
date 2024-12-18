<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Backend\RawMaterial;
use App\Http\Controllers\Controller;
use App\Models\Backend\ForecastingResult;
use App\Http\Services\Backend\ForecastingService;
use App\Http\Requests\Backend\Forecasting\FilterRequest;
use App\Http\Requests\Backend\Forecasting\GenerateRequest;
use App\Http\Requests\Backend\Forecasting\UpdateActualRequest;

class ForecastingController extends Controller
{
    public function __construct(
        private ForecastingService $forecastingService
    ) 
    {
        $this->middleware(function ($request, $next) {
            if ($request->user()->isOwner() && !in_array($request->routeIs('panel.forecasting.index', 'panel.forecasting.history', 'panel.forecasting.accuracy'), ['panel.forecasting.index', 'panel.forecasting.history', 'panel.forecasting.accuracy'])) {
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
    public function index()
    {
        return view('backend.forecasting.index', [
            'rawMaterials' => RawMaterial::with('category')->get(),
            'forecasts' => $this->forecastingService->getRecentForecasts(10),
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

    public function generate(GenerateRequest $request)
    {
        try {
            $forecast = $this->forecastingService->generateForecast(
                $request->validated('raw_material_id'),
                $request->validated('period')
            );

            return redirect()
                ->route('panel.forecasting.index')
                ->with('success', 'Forecast generated successfully');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function editActual($uuid)
    {
        $forecast = ForecastingResult::where('uuid', $uuid)->firstOrFail();
        return view('backend.forecasting.edit-actual', compact('forecast'));
    }

    public function updateActual(UpdateActualRequest $request, $uuid)
    {
        try {
            $this->forecastingService->updateActualUsage(
                $request->forecast_id,
                $request->actual_usage
            );

            return redirect()
                ->route('panel.forecasting.history')
                ->with('success', 'Actual usage updated successfully');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function history(FilterRequest $request)
    {
        return view('backend.forecasting.history', [
            'forecasts' => $this->forecastingService->getForecastHistory(
                10,
                $request->validated('raw_material_id'),
                $request->validated('date_from'),
                $request->validated('date_to')
            ),
            'rawMaterials' => RawMaterial::with('category')->get(),
            'filters' => $request->validated(),
        ]);
    }

    public function accuracy(FilterRequest $request)
    {
        $analysis = null;

        if ($request->validated('raw_material_id')) {
            $analysis = $this->forecastingService->getAccuracyAnalysis(
                $request->validated('raw_material_id'),
                $request->validated('date_from'),
                $request->validated('date_to')
            );
        }

        return view('backend.forecasting.accuracy', [
            'rawMaterials' => RawMaterial::with('category')->get(),
            'analysis' => $analysis,
            'filters' => $request->validated(),
        ]);
    }
}
