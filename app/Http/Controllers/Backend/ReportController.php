<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\DailySalesExport;
use App\Exports\UsageReportExport;
use App\Exports\YearlySalesExport;
use App\Exports\MonthlySalesExport;
use App\Exports\OrderHistoryExport;
use App\Exports\ProductSalesExport;
use App\Models\Backend\RawMaterial;
use App\Exports\StockMovementExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerBehaviorExport;
use App\Http\Services\Backend\ReportService;
use App\Http\Requests\Backend\ReportFilterRequest;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) 
    {
        $this->middleware(function ($request, $next) {
            if (!$request->user()->isAdmin() && !$request->user()->isOwner() && !$request->user()->isPegawai()) {
                abort(403, 'Unauthorized action.');
            }

            return $next($request);
        });

    }

    // Sales Reports
    public function salesIndex()
    {
        return view('backend.reports.sales.index', [
            'dailySales' => $this->reportService->getDailySales(),
            'monthlySales' => $this->reportService->getMonthlySales(),
            'yearlySales' => $this->reportService->getYearlySales(),
            'topProducts' => $this->reportService->getTopSellingProducts(),
        ]);
    }

    public function salesDaily()
    {
        return view('backend.reports.sales.daily', [
            'sales' => $this->reportService->getDailySales(),
        ]);
    }

    public function salesMonthly()
    {
        return view('backend.reports.sales.monthly', [
            'sales' => $this->reportService->getMonthlySales(),
        ]);
    }

    public function salesYearly()
    {
        return view('backend.reports.sales.yearly', [
            'sales' => $this->reportService->getYearlySales(),
        ]);
    }

    // Inventory Reports
    public function inventoryIndex()
    {
        return view('backend.reports.inventory.index', [
            'rawMaterials' => RawMaterial::with('category')->get(),
            'stockMovements' => $this->reportService->getStockMovements(),
            'materialUsage' => $this->reportService->getMaterialUsage(),
            'lowStockItems' => $this->reportService->getLowStockItems(),
        ]);
    }

    public function inventoryStock(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');
        $rawMaterialId = $request->raw_material_id;

        return view('backend.reports.inventory.stock', [
            'rawMaterials' => RawMaterial::with('category')->get(),
            'stockMovements' => $this->reportService->getStockMovements($dateFrom, $dateTo, $rawMaterialId),
            'filters' => [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'rawMaterialId' => $rawMaterialId,
            ]
        ]);
    }

    public function inventoryUsage(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');
        $rawMaterialId = $request->raw_material_id;

        return view('backend.reports.inventory.usage', [
            'rawMaterials' => RawMaterial::with('category')->get(),
            'materialUsage' => $this->reportService->getMaterialUsage($dateFrom, $dateTo, $rawMaterialId),
            'filters' => compact('dateFrom', 'dateTo', 'rawMaterialId')
        ]);
    }

    // Customer Reports
    public function customersIndex()
    {
        return view('backend.reports.customers.index', [
            'customerOrders' => $this->reportService->getCustomerOrders(),
            'customerBehavior' => $this->reportService->getCustomerBehavior(),
        ]);
    }

    public function customersOrders()
    {
        return view('backend.reports.customers.orders', [
            'customerOrders' => $this->reportService->getCustomerOrders(),
        ]);
    }

    public function customersBehavior()
    {
        return view('backend.reports.customers.behavior', [
            'customerBehavior' => $this->reportService->getCustomerBehavior(),
        ]);
    }

    // Export Methods for Sales Reports
    public function exportDailySales(ReportFilterRequest $request)
    {
        $fileName = 'laporan_penjualan_harian_' . Carbon::now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new DailySalesExport(
            $request->validated('date_from'),
            $request->validated('date_to')
        ), $fileName);
    }

    public function exportMonthlySales(ReportFilterRequest $request)
    {
        $fileName = 'monthly_sales_' . Carbon::now()->format('Y-m') . '.xlsx';
        return Excel::download(new MonthlySalesExport(
            $request->validated('date_from'),
            $request->validated('date_to')
        ), $fileName);
    }

    public function exportYearlySales(ReportFilterRequest $request)
    {
        $fileName = 'yearly_sales_' . Carbon::now()->format('Y') . '.xlsx';
        return Excel::download(new YearlySalesExport(
            $request->validated('date_from'),
            $request->validated('date_to')
        ), $fileName);
    }

    public function exportProductSales(ReportFilterRequest $request)
    {
        $fileName = 'product_sales_' . Carbon::now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new ProductSalesExport(
            $request->validated('date_from'),
            $request->validated('date_to')
        ), $fileName);
    }

    // Export Methods for Inventory Reports
    public function exportStockMovement(ReportFilterRequest $request)
    {
        $fileName = 'stock_movement_' . Carbon::now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new StockMovementExport(
            $request->validated('date_from'),
            $request->validated('date_to')
        ), $fileName);
    }

    public function exportUsageReport(ReportFilterRequest $request)
    {
        $fileName = 'usage_report_' . Carbon::now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new UsageReportExport(
            $request->validated('date_from'),
            $request->validated('date_to')
        ), $fileName);
    }

    // Export Methods for Customer Reports
    public function exportOrderHistory(ReportFilterRequest $request)
    {
        $fileName = 'order_history_' . Carbon::now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new OrderHistoryExport(
            $request->validated('date_from'),
            $request->validated('date_to')
        ), $fileName);
    }

    public function exportCustomerBehavior(ReportFilterRequest $request)
    {
        $fileName = 'customer_behavior_' . Carbon::now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new CustomerBehaviorExport(
            $request->validated('date_from'),
            $request->validated('date_to')
        ), $fileName);
    }
}
