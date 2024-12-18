<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Backend\Selling;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailySalesExport implements FromCollection
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
        return Selling::selectRaw('DATE(date) as tanggal_penjualan')
            ->selectRaw('COUNT(*) as total_transaksi')
            ->selectRaw('SUM(total_price) as total_penjualan')
            ->whereBetween('date', [$this->tanggalMulai, $this->tanggalAkhir])
            ->groupBy('tanggal_penjualan')
            ->orderByDesc('tanggal_penjualan')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => Carbon::parse($item->tanggal_penjualan)->format('d M Y'),
                    'total_transaksi' => $item->total_transaksi,
                    'total_penjualan' => $item->total_penjualan,
                    'rata_rata_transaksi' => $item->total_penjualan / $item->total_transaksi,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Total Transaksi',
            'Total Penjualan (Rp)',
            'Rata-rata Nilai Transaksi (Rp)',
        ];
    }

    public function map($row): array
    {
        return [
            $row['tanggal'],
            $row['total_transaksi'],
            number_format($row['total_penjualan'], 0, ',', '.'),
            number_format($row['rata_rata_transaksi'], 0, ',', '.'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header style
            'B' => ['alignment' => ['horizontal' => 'center']], // Kolom total transaksi
            'C' => ['alignment' => ['horizontal' => 'right']], // Kolom total penjualan
            'D' => ['alignment' => ['horizontal' => 'right']], // Kolom rata-rata
        ];
    }
}
