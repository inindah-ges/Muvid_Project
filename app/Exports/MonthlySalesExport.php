<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Backend\Selling;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlySalesExport implements FromCollection
{
    use Exportable;

    protected $tanggalMulai;
    protected $tanggalAkhir;

    public function __construct($tanggalMulai = null, $tanggalAkhir = null)
    {
        $this->tanggalMulai = $tanggalMulai ?? Carbon::now()->startOfYear();
        $this->tanggalAkhir = $tanggalAkhir ?? Carbon::now();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Selling::selectRaw('MONTH(date) as bulan')
            ->selectRaw('YEAR(date) as tahun')
            ->selectRaw('COUNT(*) as total_transaksi')
            ->selectRaw('SUM(total_price) as total_penjualan')
            ->whereBetween('date', [$this->tanggalMulai, $this->tanggalAkhir])
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'periode' => Carbon::createFromDate($item->tahun, $item->bulan, 1)->format('F Y'),
                    'total_transaksi' => $item->total_transaksi,
                    'total_penjualan' => $item->total_penjualan,
                    'rata_rata_transaksi' => $item->total_penjualan / $item->total_transaksi
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Periode',
            'Total Transaksi',
            'Total Penjualan (Rp)',
            'Rata-rata Nilai Transaksi (Rp)',
        ];
    }

    public function map($row): array
    {
        return [
            $row['periode'],
            $row['total_transaksi'],
            number_format($row['total_penjualan'], 0, ',', '.'),
            number_format($row['rata_rata_transaksi'], 0, ',', '.'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'B' => ['alignment' => ['horizontal' => 'center']],
            'C' => ['alignment' => ['horizontal' => 'right']],
            'D' => ['alignment' => ['horizontal' => 'right']],
        ];
    }
}
