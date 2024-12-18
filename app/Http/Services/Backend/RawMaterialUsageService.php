<?php

namespace App\Http\Services\Backend;

use App\Exports\RawMaterialUsageExport;
use App\Models\Backend\RawMaterial;
use App\Models\Backend\RawMaterialUsage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RawMaterialUsageService
{
    public function getUsageHistory($paginate = null, $rawMaterialId = null, $dateFrom = null, $dateTo = null)
    {
        $query = RawMaterialUsage::with(['rawMaterial.category', 'user'])
            ->when($rawMaterialId, function ($q) use ($rawMaterialId) {
                return $q->where('raw_material_id', $rawMaterialId);
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

    public function recordUsage($rawMaterialId, $quantityUsed, $date)
    {
        DB::beginTransaction();
        try {
            $rawMaterial = RawMaterial::findOrFail($rawMaterialId);

            // Check stock availability
            if ($rawMaterial->stock < $quantityUsed) {
                throw new \Exception("Insufficient stock. Available: {$rawMaterial->stock} {$rawMaterial->unit}");
            }

            // Create usage record
            RawMaterialUsage::create([
                'raw_material_id' => $rawMaterialId,
                'user_id' => Auth::id(),
                'quantity_used' => $quantityUsed,
                'date' => $date,
            ]);

            // Decrease stock
            $rawMaterial->decrement('stock', $quantityUsed);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function generateReport($dateFrom, $dateTo, $rawMaterialId = null)
    {
        $query = RawMaterialUsage::with(['rawMaterial.category'])
            ->whereDate('date', '>=', Carbon::parse($dateFrom))
            ->whereDate('date', '<=', Carbon::parse($dateTo));

        if ($rawMaterialId) {
            $query->where('raw_material_id', $rawMaterialId);
        }

        $report = [
            'period' => [
                'from' => Carbon::parse($dateFrom)->format('d M Y'),
                'to' => Carbon::parse($dateTo)->format('d M Y'),
            ],
            'summary' => [],
            'details' => $query->get(),
        ];

        // Calculate summary per raw material
        $summary = $query->get()->groupBy('raw_material_id')->map(function ($items) {
            $material = $items->first()->rawMaterial;
            return [
                'material' => $material->name,
                'category' => $material->category->name,
                'unit' => $material->unit,
                'total_usage' => $items->sum('quantity_used'),
                'average_usage' => $items->avg('quantity_used'),
                'usage_count' => $items->count(),
            ];
        });

        $report['summary'] = $summary;

        return $report;
    }

    public function exportReport($dateFrom, $dateTo, $rawMaterialId = null)
    {
        $report = $this->generateReport($dateFrom, $dateTo, $rawMaterialId);

        if (!$report || empty($report['details'])) {
            throw new \Exception('No data available for the selected period');
        }

        $fileName = 'raw_material_usage_' . now()->format('YmdHis') . '.xlsx';

        return Excel::download(
            new RawMaterialUsageExport($report, $dateFrom, $dateTo),
            $fileName
        );
    }
}
