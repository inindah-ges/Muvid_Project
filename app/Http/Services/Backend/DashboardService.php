<?php

namespace App\Http\Services\Backend;

use App\Models\Backend\ForecastingResult;
use App\Models\Backend\Order;
use App\Models\Backend\Product;
use App\Models\Backend\RawMaterial;
use App\Models\Backend\Selling;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getSummaryData()
    {
        $today = Carbon::today();

        return [
            'todaySales' => $this->getTodaySales(),
            'salesGrowth' => $this->getSalesGrowth(),
            'totalCustomers' => $this->getTotalCustomers(),
            'newCustomersToday' => $this->getNewCustomersToday(),
            'pendingOrders' => $this->getPendingOrders(),
            'orderCompletionRate' => $this->getOrderCompletionRate(),
            'lowStockCount' => $this->getLowStockCount(),
            'completedOrdersToday' => $this->getCompletedOrdersToday(),
        ];
    }

    public function getChartData()
    {
        return [
            'salesChart' => $this->getSalesChartData(),
            'productChart' => $this->getProductChartData(),
            'forecastChart' => $this->getForecastChartData(),
            'categoryChart' => $this->getCategoryChartData(),
            'orderTypeChart' => $this->getOrderTypeChartData(),
            'rawMaterialUsageChart' => $this->getRawMaterialUsageChartData(),
        ];
    }

    public function getTableData()
    {
        return [
            'recentOrders' => $this->getRecentOrders(),
            'lowStockItems' => $this->getLowStockItems(),
            'topProducts' => $this->getTopProducts(),
            'topCustomers' => $this->getTopCustomers(),
            'upcomingForecast' => $this->getUpcomingForecast(),
        ];
    }

    private function getTodaySales()
    {
        return Selling::whereDate('date', Carbon::today())->sum('total_price');
    }

    private function getSalesGrowth()
    {
        $todaySales = $this->getTodaySales();
        $yesterdaySales = Selling::whereDate('date', Carbon::yesterday())->sum('total_price');

        return $yesterdaySales > 0
        ? (($todaySales - $yesterdaySales) / $yesterdaySales) * 100
        : 0;
    }

    private function getTotalCustomers()
    {
        return User::where('role', 'pelanggan')->count();
    }

    private function getNewCustomersToday()
    {
        return User::where('role', 'pelanggan')
            ->whereDate('created_at', Carbon::today())
            ->count();
    }

    private function getPendingOrders()
    {
        return Order::where('status', 'pending')->count();
    }

    private function getOrderCompletionRate()
    {
        $totalOrders = Order::whereDate('created_at', Carbon::today())->count();
        $completedOrders = Order::whereDate('created_at', Carbon::today())
            ->where('status', 'completed')
            ->count();

        return $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0;
    }

    private function getLowStockCount()
    {
        // Menggabungkan low stock dari products dan raw materials
        $lowStockProducts = Product::where('stock', '<=', 10)->count();
        $lowStockMaterials = RawMaterial::where('stock', '<=', 10)->count();

        return $lowStockProducts + $lowStockMaterials;
    }

    private function getCompletedOrdersToday()
    {
        return Order::whereDate('created_at', Carbon::today())
            ->where('status', 'completed')
            ->count();
    }

    private function getSalesChartData()
    {
        $dates = collect(range(6, 0))->map(function ($days) {
            return Carbon::now()->subDays($days)->format('Y-m-d');
        });

        $sales = Selling::whereIn(DB::raw('DATE(date)'), $dates)
            ->groupBy(DB::raw('DATE(date)'))
            ->select(DB::raw('DATE(date) as date'), DB::raw('SUM(total_price) as total'))
            ->pluck('total', 'date');

        return [
            'labels' => $dates->map(fn($date) => Carbon::parse($date)->format('D')),
            'data' => $dates->map(fn($date) => $sales[$date] ?? 0),
        ];
    }

    private function getProductChartData()
    {
        $topProducts = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return [
            'labels' => $topProducts->pluck('name'),
            'data' => $topProducts->pluck('total_sold'),
        ];
    }

    private function getForecastChartData()
    {
        $forecasts = ForecastingResult::with('rawMaterial')
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date')
            ->limit(7)
            ->get();

        return [
            'labels' => $forecasts->pluck('date')->map(fn($date) => Carbon::parse($date)->format('D')),
            'data' => $forecasts->pluck('predicted_amount'),
            'materials' => $forecasts->pluck('raw_material.name')->unique(),
        ];
    }

    private function getCategoryChartData()
    {
        return DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->select('categories.name', DB::raw('COUNT(order_details.id) as total_orders'))
            ->groupBy('categories.id', 'categories.name')
            ->get();
    }

    private function getOrderTypeChartData()
    {
        return Order::select('order_type', DB::raw('COUNT(*) as total'))
            ->groupBy('order_type')
            ->get();
    }

    private function getRawMaterialUsageChartData()
    {
        return DB::table('raw_material_usages')
            ->join('raw_materials', 'raw_material_usages.raw_material_id', '=', 'raw_materials.id')
            ->select(
                'raw_materials.name',
                DB::raw('SUM(raw_material_usages.quantity_used) as total_used')
            )
            ->whereMonth('raw_material_usages.date', Carbon::now()->month)
            ->groupBy('raw_materials.id', 'raw_materials.name')
            ->orderByDesc('total_used')
            ->limit(5)
            ->get();
    }

    private function getRecentOrders()
    {
        return Order::with('user')
            ->latest()
            ->limit(5)
            ->get();
    }

    private function getLowStockItems()
    {
        // Mengambil produk dengan stock rendah
        $lowStockProducts = Product::select(
            'id',
            'name',
            'stock',
            DB::raw("'product' as type"),
            DB::raw("'pcs' as unit")
        )
            ->where('stock', '<=', 10)
            ->where('status', 'available');

        // Mengambil raw materials dengan stock rendah
        $lowStockMaterials = RawMaterial::select(
            'id',
            'name',
            'stock',
            DB::raw("'material' as type"),
            'unit'
        )
            ->where('stock', '<=', 10);

        // Menggabungkan keduanya dan ambil 5 item dengan stock terendah
        return $lowStockProducts->union($lowStockMaterials)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();
    }

    private function getTopProducts()
    {
        return DB::table('products')
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->select(
                'products.name',
                'products.price',
                DB::raw('SUM(order_details.quantity) as total_sold'),
                DB::raw('SUM(order_details.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
    }

    private function getTopCustomers()
    {
        return DB::table('users')
            ->join('customer_orders', 'users.id', '=', 'customer_orders.user_id')
            ->where('users.role', 'pelanggan')
            ->select(
                'users.name',
                DB::raw('COUNT(customer_orders.id) as total_orders'),
                DB::raw('SUM(customer_orders.total_price) as total_spent')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();
    }

    private function getUpcomingForecast()
    {
        return ForecastingResult::with('rawMaterial')
            ->whereDate('date', '>', Carbon::today())
            ->orderBy('date')
            ->limit(5)
            ->get();
    }

    public function getCustomerDashboardData()
    {
        $user_id = Auth::id();

        // Summary Data
        $summary = [
            'activeOrders' => Order::where('user_id', $user_id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->count(),

            'totalOrders' => Order::where('user_id', $user_id)
                ->count(),

            'totalSpent' => Order::where('user_id', $user_id)
                ->where('status', 'completed')
                ->sum('total_price'),

            'latestOrder' => Order::where('user_id', $user_id)
                ->latest()
                ->first(),

            'customerOrders' => Order::where('user_id', $user_id)
                ->latest()
                ->take(5)
                ->get(),
        ];

        return $summary;
    }
}
