<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\Backend\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'pelanggan') {
            $customerData = $this->dashboardService->getCustomerDashboardData();
            return view('backend.dashboard.index', $customerData);
        }

        $summary = $this->dashboardService->getSummaryData();
        $charts = $this->dashboardService->getChartData();
        $tables = $this->dashboardService->getTableData();

        return view('backend.dashboard.index', array_merge(
            $summary,
            [
                'salesChart' => $charts['salesChart'] ?? ['labels' => [], 'data' => []],
                'productChart' => $charts['productChart'] ?? ['labels' => [], 'data' => []],
                'forecastChart' => $charts['forecastChart'] ?? ['labels' => [], 'data' => []],
            ],
            $tables
        ));
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
}
