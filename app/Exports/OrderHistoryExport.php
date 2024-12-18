<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Backend\Order;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderHistoryExport implements FromCollection
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
            ->whereBetween('created_at', [$this->tanggalMulai, $this->tanggalAkhir])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->created_at->format('d M Y'),
                    'invoice' => $item->order_number,
                    'pelanggan' => $item->user->name,
                    'tipe_pesanan' => $item->order_type === 'dine_in' ? 'Makan di Tempat' : 'Bawa Pulang',
                    'status' => $this->getStatusLabel($item->status),
                    'metode_pembayaran' => $this->getPaymentMethodLabel($item->payment_method),
                    'status_pembayaran' => $item->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas',
                    'total' => $item->total_price,
                    'catatan' => $item->notes
                ];
            });
    }

    private function getStatusLabel($status)
    {
        return [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ][$status] ?? $status;
    }

    private function getPaymentMethodLabel($method)
    {
        return [
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'ewallet' => 'E-Wallet',
        ][$method] ?? $method;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No. Pesanan',
            'Pelanggan',
            'Tipe Pesanan',
            'Status',
            'Metode Pembayaran',
            'Status Pembayaran',
            'Total (Rp)',
            'Catatan'
        ];
    }

    public function map($row): array
    {
        return [
            $row['tanggal'],
            $row['invoice'],
            $row['pelanggan'],
            $row['tipe_pesanan'],
            $row['status'],
            $row['metode_pembayaran'],
            $row['status_pembayaran'],
            number_format($row['total'], 0, ',', '.'),
            $row['catatan'] ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'B' => ['alignment' => ['horizontal' => 'center']],
            'D' => ['alignment' => ['horizontal' => 'center']],
            'E' => ['alignment' => ['horizontal' => 'center']],
            'F' => ['alignment' => ['horizontal' => 'center']],
            'G' => ['alignment' => ['horizontal' => 'center']],
            'H' => ['alignment' => ['horizontal' => 'right']],
        ];
    }
}
