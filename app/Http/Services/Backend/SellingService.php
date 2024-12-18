<?php

namespace App\Http\Services\Backend;

use Carbon\Carbon;
use App\Models\Backend\Selling;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SellingService
{
    public function getSellings($dateFrom = null, $dateTo = null, $paginate = null)
    {
        $query = Selling::with(['user', 'sellingDetails.product', 'sellingDetails.tax']);

        if (Auth::user()->hasRole('pelanggan')) {
            $query->where('user_id', Auth::id());
        }

        if ($dateFrom) {
            $query->whereDate('date', '>=', Carbon::parse($dateFrom));
        }

        if ($dateTo) {
            $query->whereDate('date', '<=', Carbon::parse($dateTo));
        }

        $query->latest();

        return $paginate ? $query->paginate($paginate) : $query->get();
    }

    public function getSellingByUuid($uuid)
    {
        return Selling::with(['user', 'sellingDetails.product', 'sellingDetails.tax'])
            ->where('uuid', $uuid)
            ->firstOrFail();
    }

    public function generateInvoice($uuid)
    {
        $selling = $this->getSellingByUuid($uuid);

        $data = [
            'selling' => $selling,
            'company' => [
                'name' => 'Kanal Social Space',
                'address' => ' Jl. Wijaya Kusuma No.6, Makassar',
                'phone' => '0852-5602-9021',
                'email' => 'info@kanalsocialspace.com',
            ],
        ];

        $pdf = PDF::loadView('backend.selling.invoice', $data);
        return $pdf;
    }

    public function calculateTotalAmount($sellingDetails)
    {
        $total = 0;
        foreach ($sellingDetails as $detail) {
            $subtotal = $detail->quantity * $detail->unit_price;
            if ($detail->discount) {
                $subtotal -= $detail->discount;
            }
            if ($detail->tax) {
                $subtotal += ($subtotal * $detail->tax->rate / 100);
            }
            $total += $subtotal;
        }
        return $total;
    }
}
