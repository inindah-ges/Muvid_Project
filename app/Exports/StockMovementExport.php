<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Backend\RawMaterialStock;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockMovementExport implements FromCollection
{
    use Exportable;

    protected $tanggalMulai;
    protected $tanggalAkhir;

    public function __construct($tanggalMulai = null, $tanggalAkhir = null)
    {
        $this->tanggalMulai = $tanggalMulai ?? Carbon::now()->startOfMonth();
        $this->tanggalAkhir = $tanggalAkhir ?? Carbon::now();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return RawMaterialStock::with(['rawMaterial', 'rawMaterial.category', 'user'])
            ->whereBetween('date', [$this->tanggalMulai, $this->tanggalAkhir])
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->date->format('d M Y'),
                    'bahan_baku' => $item->rawMaterial->name,
                    'kategori' => $item->rawMaterial->category->name,
                    'tipe' => $item->type === 'in' ? 'Masuk' : 'Keluar',
                    'jumlah' => $item->quantity . ' ' . $item->rawMaterial->unit,
                    'catatan' => $item->notes,
                    'petugas' => $item->user->name
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Bahan Baku',
            'Kategori',
            'Tipe',
            'Jumlah',
            'Catatan',
            'Petugas',
        ];
    }

    public function map($row): array
    {
        return [
            $row['tanggal'],
            $row['bahan_baku'],
            $row['kategori'],
            $row['tipe'],
            $row['jumlah'],
            $row['catatan'] ?? '-',
            $row['petugas'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'E' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}
