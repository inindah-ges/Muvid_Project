<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\OrderRequest;
use App\Http\Services\Backend\OrderService;
use App\Models\Backend\Product;
use App\Models\Backend\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) 
    {
        $this->middleware(function ($request, $next) {
            if ($request->user()->isOwner() && !in_array($request->routeIs('panel.order.index', 'panel.order.show'), ['panel.order.index', 'panel.order.show'])) {
                abort(403, 'Unauthorized action.');
            }

            if (!$request->user()->isAdmin() && !$request->user()->isOwner() && !$request->user()->isPelanggan()) {
                abort(403, 'Unauthorized action.');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');

        return view('backend.order.index', [
            'status' => $status,
            'orders' => $this->orderService->getOrders($status, 10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.order.create', [
            'products' => Product::where('status', 'available')
                            ->select('id', 'name', 'price')
                            ->get(),
            'tax' => Tax::where('name', 'PPN')->first(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'order_type' => 'required|in:dine_in,takeaway',
            'notes' => 'nullable|string',
        ]);

        try {
            $order = $this->orderService->store(Auth::id(), $validated);
            return redirect()->route('panel.order.create', $order->uuid)
                ->with('success', 'Order created successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        return view('backend.order.show', [
            'order' => $this->orderService->getOrderByUuid($uuid),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function complete(string $uuid)
    {
        try {
            $this->orderService->completeOrder($uuid);
            return redirect()->route('panel.order.index')->with('success', 'Order has been completed');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function confirm(string $uuid)
    {
        try {
            $this->orderService->confirmOrder($uuid);

            return redirect()->route('panel.order.index')->with('success', 'Order has been confirmed');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cancel(string $uuid)
    {
        try {
            $this->orderService->cancelOrder($uuid);

            return redirect()->route('panel.order.index')->with('success', 'Order has been cancelled');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function processPayment(OrderRequest $request, string $uuid)
    {
        try {
            // Validate payment amount for cash payments
            if ($request->payment_method === 'cash') {
                $order = $this->orderService->getOrderByUuid($uuid);
                if (!$request->payment_amount || $request->payment_amount < $order->total_price) {
                    return redirect()->back()->with('error', 'Payment amount is insufficient');
                }
            }

            $this->orderService->processPayment($uuid, $request->payment_method, $request->payment_amount ?? null);

            return redirect()->route('panel.order.index')
                ->with('success', 'Payment has been processed successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
