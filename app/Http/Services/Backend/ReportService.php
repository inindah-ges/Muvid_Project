<?php

namespace App\Http\Services\Backend;

use Carbon\Carbon;
use App\Models\Backend\Order;
use App\Models\Backend\Selling;
use Illuminate\Support\Facades\DB;
use App\Models\Backend\RawMaterial;
use App\Models\Backend\SellingDetail;
use App\Models\Backend\RawMaterialStock;
use App\Models\Backend\RawMaterialUsage;

class ReportService
{
    // Laporan Penjualan Harian
    public function getDailySales()
    {
        // Pastikan kita mendapatkan data hari ini
        return Selling::selectRaw('DATE(date) as tanggal_penjualan')
            ->selectRaw('COUNT(*) as total_transaksi')
            ->selectRaw('SUM(total_price) as total_penjualan')
            ->whereDate('date', '>=', Carbon::now()->startOfMonth())
            ->whereDate('date', '<=', Carbon::now())
            ->groupBy('tanggal_penjualan')
            ->orderByDesc('tanggal_penjualan')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->tanggal_penjualan,
                    'total_transaksi' => $item->total_transaksi,
                    'total_penjualan' => $item->total_penjualan,
                    'rata_rata_transaksi' => $item->total_penjualan / $item->total_transaksi,
                ];
            });
    }

    // Laporan Penjualan Bulanan
    public function getMonthlySales()
    {
        return Selling::selectRaw('MONTH(date) as bulan')
            ->selectRaw('YEAR(date) as tahun')
            ->selectRaw('COUNT(*) as total_transaksi')
            ->selectRaw('SUM(total_price) as total_penjualan')
            ->whereYear('date', Carbon::now()->year)
            ->groupBy('tahun', 'bulan')
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get()
            ->map(function ($item) {
                return [
                    'bulan' => $item->bulan,
                    'tahun' => $item->tahun,
                    'periode' => Carbon::createFromDate($item->tahun, $item->bulan, 1)->format('M Y'),
                    'total_transaksi' => $item->total_transaksi,
                    'total_penjualan' => $item->total_penjualan,
                    'rata_rata_transaksi' => $item->total_penjualan / $item->total_transaksi,
                ];
            });
    }

    // Laporan Penjualan Tahunan
    public function getYearlySales()
    {
        return Selling::selectRaw('YEAR(date) as tahun')
            ->selectRaw('COUNT(*) as total_transaksi')
            ->selectRaw('SUM(total_price) as total_penjualan')
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->get()
            ->map(function ($item) {
                return [
                    'tahun' => $item->tahun,
                    'total_transaksi' => $item->total_transaksi,
                    'total_penjualan' => $item->total_penjualan,
                    'rata_rata_transaksi' => $item->total_penjualan / $item->total_transaksi,
                ];
            });
    }

    // Produk Terlaris
    public function getTopSellingProducts()
    {
        // Hitung total quantity terjual untuk bulan ini
        $topProducts = SellingDetail::select(
            'products.id',
            'products.name',
            DB::raw('SUM(selling_details.quantity) as total_quantity'),
            DB::raw('SUM(selling_details.subtotal) as total_pendapatan')
        )
            ->join('products', 'selling_details.product_id', '=', 'products.id')
            ->join('sellings', 'selling_details.selling_id', '=', 'sellings.id')
            ->whereMonth('sellings.date', Carbon::now()->month)
            ->whereYear('sellings.date', Carbon::now()->year)
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->get();

        return $topProducts;
    }

    // Pergerakan Stok
    public function getStockMovements($dateFrom = null, $dateTo = null, $rawMaterialId = null)
    {
        $query = RawMaterialStock::with(['rawMaterial', 'rawMaterial.category', 'user'])
            ->when($rawMaterialId, function ($q) use ($rawMaterialId) {
                return $q->where('raw_material_id', $rawMaterialId);
            })
            ->when($dateFrom, function ($q) use ($dateFrom) {
                return $q->whereDate('date', '>=', $dateFrom);
            })
            ->when($dateTo, function ($q) use ($dateTo) {
                return $q->whereDate('date', '<=', $dateTo);
            })
            ->when(!$dateFrom && !$dateTo, function ($q) {
                return $q->whereMonth('date', Carbon::now()->month);
            })
            ->orderByDesc('date');

        return $query->get();
    }

    // Penggunaan Bahan Baku
    public function getMaterialUsage($dateFrom = null, $dateTo = null, $rawMaterialId = null)
    {
        $query = RawMaterialUsage::with(['rawMaterial', 'rawMaterial.category', 'user'])
            ->when($rawMaterialId, function ($q) use ($rawMaterialId) {
                return $q->where('raw_material_id', $rawMaterialId);
            })
            ->when($dateFrom, function ($q) use ($dateFrom) {
                return $q->whereDate('date', '>=', $dateFrom);
            })
            ->when($dateTo, function ($q) use ($dateTo) {
                return $q->whereDate('date', '<=', $dateTo);
            })
            ->when(!$dateFrom && !$dateTo, function ($q) {
                return $q->whereMonth('date', Carbon::now()->month);
            })
            ->orderByDesc('date');

        return $query->get();
    }

    // Bahan Baku dengan Stok Menipis
    public function getLowStockItems()
    {
        return RawMaterial::with('category')
            ->select('raw_materials.*')
            ->where('stock', '<=', 10)
            ->get()
            ->map(function ($item) {
                // Hitung total pergerakan stok bulan ini
                $movement = RawMaterialStock::where('raw_material_id', $item->id)
                    ->whereMonth('date', Carbon::now()->month)
                    ->selectRaw('SUM(CASE WHEN type = "in" THEN quantity ELSE -quantity END) as total_movement')
                    ->first();

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category->name,
                    'stock' => $item->stock,
                    'unit' => $item->unit,
                    'movement' => $movement ? $movement->total_movement : 0,
                ];
            });
    }

    // Riwayat Pesanan Pelanggan
    public function getCustomerOrders($dateFrom = null, $dateTo = null)
    {
        $query = Order::with(['user'])
            ->when($dateFrom, function ($q) use ($dateFrom) {
                return $q->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($q) use ($dateTo) {
                return $q->whereDate('created_at', '<=', $dateTo);
            })
            ->when(!$dateFrom && !$dateTo, function ($q) {
                return $q->whereMonth('created_at', Carbon::now()->month);
            });

        // Untuk data detail orders
        return $query->latest()
            ->get()
            ->map(function ($order) {
                return [
                    'pelanggan' => $order->user->name,
                    'invoice' => $order->order_number,
                    'tipe_pesanan' => $order->order_type === 'dine_in' ? 'Makan di Tempat' : 'Bawa Pulang',
                    'status' => $order->status,
                    'payment_method' => $order->payment_method,
                    'payment_status' => $order->payment_status,
                    'total_pembelian' => $order->total_price,
                    'created_at' => $order->created_at,
                    'total_pesanan' => 1, // Untuk perhitungan total pesanan di summary cards
                    'jumlah_pesanan' => 1, // Untuk chart line
                    'rata_rata_nilai_pesanan' => $order->total_price, // Untuk rata-rata di chart
                ];
            });
    }

    // Perilaku Pelanggan
    public function getCustomerBehavior($dateFrom = null, $dateTo = null)
    {
        return Order::with(['user'])
            ->selectRaw('user_id, order_type')
            ->selectRaw('COUNT(*) as jumlah_pesanan')
            ->selectRaw('SUM(total_price) as total_pembelian')
            ->selectRaw('AVG(total_price) as rata_rata_nilai_pesanan')
            ->when($dateFrom, function ($q) use ($dateFrom) {
                return $q->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($q) use ($dateTo) {
                return $q->whereDate('created_at', '<=', $dateTo);
            })
            ->when(!$dateFrom && !$dateTo, function ($q) {
                return $q->whereMonth('created_at', Carbon::now()->month);
            })
            ->groupBy('user_id', 'order_type')
            ->orderByDesc('jumlah_pesanan')
            ->get()
            ->map(function ($item) {
                return [
                    'pelanggan' => $item->user->name,
                    'tipe_pesanan' => $item->order_type === 'dine_in' ? 'Makan di Tempat' : 'Bawa Pulang',
                    'jumlah_pesanan' => $item->jumlah_pesanan,
                    'total_pembelian' => $item->total_pembelian,
                    'rata_rata_nilai_pesanan' => $item->rata_rata_nilai_pesanan,
                ];
            });
    }
}
