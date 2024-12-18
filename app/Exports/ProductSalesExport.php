<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Backend\SellingDetail;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductSalesExport implements FromCollection
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
        return SellingDetail::with(['product', 'product.category'])
            ->selectRaw('product_id')
            ->selectRaw('SUM(quantity) as total_terjual')
            ->selectRaw('SUM(subtotal) as total_pendapatan')
            ->whereHas('selling', function($query) {
                $query->whereBetween('date', [$this->tanggalMulai, $this->tanggalAkhir]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_terjual')
            ->get()
            ->map(function ($item) {
                return [
                    'nama_produk' => $item->product->name,
                    'kategori' => $item->product->category->name,
                    'total_terjual' => $item->total_terjual,
                    'total_pendapatan' => $item->total_pendapatan,
                    'rata_rata_harga' => $item->total_pendapatan / $item->total_terjual
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Produk',
            'Kategori',
            'Total Terjual',
            'Total Pendapatan (Rp)',
            'Rata-rata Harga (Rp)',
        ];
    }

    public function map($row): array
    {
        return [
            $row['nama_produk'],
            $row['kategori'],
            $row['total_terjual'],
            number_format($row['total_pendapatan'], 0, ',', '.'),
            number_format($row['rata_rata_harga'], 0, ',', '.'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'C' => ['alignment' => ['horizontal' => 'center']],
            'D' => ['alignment' => ['horizontal' => 'right']],
            'E' => ['alignment' => ['horizontal' => 'right']],
        ];
    }
}
