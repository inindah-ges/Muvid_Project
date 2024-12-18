<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Backend\RawMaterialUsage;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsageReportExport implements FromCollection
{
    use Exportable;

    protected $dateFrom;
    protected $dateTo;
    protected $rawMaterialId;

    public function __construct($dateFrom = null, $dateTo = null, $rawMaterialId = null)
    {
        $this->dateFrom = $dateFrom ?? Carbon::now()->startOfMonth();
        $this->dateTo = $dateTo ?? Carbon::now();
        $this->rawMaterialId = $rawMaterialId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return RawMaterialUsage::with(['rawMaterial', 'rawMaterial.category', 'user'])
            ->when($this->dateFrom, function($query) {
                return $query->whereDate('date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($query) {
                return $query->whereDate('date', '<=', $this->dateTo);
            })
            ->when($this->rawMaterialId, function($query) {
                return $query->where('raw_material_id', $this->rawMaterialId);
            })
            ->orderBy('date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Bahan Baku',
            'Kategori',
            'Jumlah Penggunaan',
            'Unit',
            'Petugas'
        ];
    }

    public function map($usage): array
    {
        return [
            Carbon::parse($usage->date)->format('d/m/Y'),
            $usage->rawMaterial->name,
            $usage->rawMaterial->category->name,
            $usage->quantity_used,
            $usage->rawMaterial->unit,
            $usage->user->name
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'D' => ['alignment' => ['horizontal' => 'center']],
            'E' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}
