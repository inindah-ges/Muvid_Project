<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Backend\Order;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerBehaviorExport implements FromCollection
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
        return Order::with(['user'])
            ->selectRaw('user_id')
            ->selectRaw('COUNT(*) as total_pesanan')
            ->selectRaw('SUM(total_price) as total_pembelian')
            ->selectRaw('AVG(total_price) as rata_rata_pembelian')
            ->selectRaw('COUNT(CASE WHEN order_type = "dine_in" THEN 1 END) as makan_ditempat')
            ->selectRaw('COUNT(CASE WHEN order_type = "takeaway" THEN 1 END) as bawa_pulang')
            ->whereBetween('created_at', [$this->tanggalMulai, $this->tanggalAkhir])
            ->groupBy('user_id')
            ->orderByDesc('total_pesanan')
            ->get()
            ->map(function ($item) {
                $frekuensiKunjungan = $this->hitungFrekuensi($item->total_pesanan);
                return [
                    'pelanggan' => $item->user->name,
                    'total_pesanan' => $item->total_pesanan,
                    'total_pembelian' => $item->total_pembelian,
                    'rata_rata_pembelian' => $item->rata_rata_pembelian,
                    'makan_ditempat' => $item->makan_ditempat,
                    'bawa_pulang' => $item->bawa_pulang,
                    'preferensi' => $this->hitungPreferensi($item->makan_ditempat, $item->bawa_pulang),
                    'frekuensi_kunjungan' => $frekuensiKunjungan,
                    'kategori_pelanggan' => $this->kategoriPelanggan($item->total_pembelian, $frekuensiKunjungan)
                ];
            });
    }

    private function hitungFrekuensi($totalPesanan)
    {
        $periodeBulan = Carbon::parse($this->tanggalMulai)->diffInMonths(Carbon::parse($this->tanggalAkhir)) + 1;
        return round($totalPesanan / $periodeBulan, 1);
    }

    private function hitungPreferensi($makanDitempat, $bawaPulang)
    {
        if ($makanDitempat > $bawaPulang) {
            return 'Lebih suka makan di tempat';
        } elseif ($bawaPulang > $makanDitempat) {
            return 'Lebih suka bawa pulang';
        }
        return 'Seimbang';
    }

    private function kategoriPelanggan($totalPembelian, $frekuensiKunjungan)
    {
        if ($frekuensiKunjungan >= 4 && $totalPembelian >= 1000000) {
            return 'Pelanggan Setia';
        } elseif ($frekuensiKunjungan >= 2 || $totalPembelian >= 500000) {
            return 'Pelanggan Regular';
        }
        return 'Pelanggan Biasa';
    }

    public function headings(): array
    {
        return [
            'Pelanggan',
            'Total Pesanan',
            'Total Pembelian (Rp)',
            'Rata-rata Pembelian (Rp)',
            'Makan di Tempat',
            'Bawa Pulang',
            'Preferensi',
            'Frekuensi Kunjungan/Bulan',
            'Kategori Pelanggan'
        ];
    }

    public function map($row): array
    {
        return [
            $row['pelanggan'],
            $row['total_pesanan'],
            number_format($row['total_pembelian'], 0, ',', '.'),
            number_format($row['rata_rata_pembelian'], 0, ',', '.'),
            $row['makan_ditempat'],
            $row['bawa_pulang'],
            $row['preferensi'],
            $row['frekuensi_kunjungan'],
            $row['kategori_pelanggan']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'B' => ['alignment' => ['horizontal' => 'center']],
            'C' => ['alignment' => ['horizontal' => 'right']],
            'D' => ['alignment' => ['horizontal' => 'right']],
            'E' => ['alignment' => ['horizontal' => 'center']],
            'F' => ['alignment' => ['horizontal' => 'center']],
            'H' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}
