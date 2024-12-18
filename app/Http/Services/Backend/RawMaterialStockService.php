<?php

namespace App\Http\Services\Backend;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Backend\RawMaterial;
use Illuminate\Support\Facades\Auth;
use App\Models\Backend\RawMaterialStock;

class RawMaterialStockService
{
    const MIN_STOCK_ALERT = 10; // Minimum stock threshold for alert

    public function getStockHistory($paginate = null, $rawMaterialId = null, $type = null, $dateFrom = null, $dateTo = null)
    {
        $query = RawMaterialStock::with(['rawMaterial', 'user'])
            ->when($rawMaterialId, function ($q) use ($rawMaterialId) {
                return $q->where('raw_material_id', $rawMaterialId);
            })
            ->when($type, function ($q) use ($type) {
                return $q->where('type', $type);
            })
            ->when($dateFrom, function ($q) use ($dateFrom) {
                return $q->whereDate('date', '>=', Carbon::parse($dateFrom));
            })
            ->when($dateTo, function ($q) use ($dateTo) {
                return $q->whereDate('date', '<=', Carbon::parse($dateTo));
            })
            ->latest();

        return $paginate ? $query->paginate($paginate) : $query->get();
    }

    public function getStockAlert()
    {
        return RawMaterial::where('stock', '<=', self::MIN_STOCK_ALERT)
            ->orWhereRaw('stock <= (SELECT AVG(quantity_used) * 7 FROM raw_material_usages WHERE raw_material_id = raw_materials.id)')
            ->with('category')
            ->get();
    }

    public function addStock($rawMaterialId, $quantity, $type, $notes = null)
    {
        DB::beginTransaction();
        try {
            $rawMaterial = RawMaterial::findOrFail($rawMaterialId);

            // Check if we have enough stock for outgoing transaction
            if ($type === 'out' && $rawMaterial->stock < $quantity) {
                throw new \Exception("Insufficient stock. Available: {$rawMaterial->stock} {$rawMaterial->unit}");
            }

            // Create stock movement record
            RawMaterialStock::create([
                'raw_material_id' => $rawMaterialId,
                'user_id' => Auth::id(),
                'quantity' => $quantity,
                'type' => $type,
                'notes' => $notes,
                'date' => now(),
            ]);

            // Update raw material stock
            if ($type === 'in') {
                $rawMaterial->increment('stock', $quantity);
            } else {
                $rawMaterial->decrement('stock', $quantity);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getCurrentStock($rawMaterialId)
    {
        return RawMaterial::findOrFail($rawMaterialId)->stock;
    }

    public function getStockMovement($rawMaterialId, $startDate, $endDate)
    {
        return RawMaterialStock::where('raw_material_id', $rawMaterialId)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('
                DATE(date) as date,
                SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as stock_in,
                SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock_out
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
