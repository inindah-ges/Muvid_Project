<?php

namespace App\Http\Controllers\Backend;

use App\Models\Backend\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Backend\ProductRequest;
use App\Http\Services\Backend\ProductService;
use App\Http\Services\Backend\CategoryService;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService, private CategoryService $categoryService
    ) 
    {
        $this->middleware(function ($request, $next) {
            if ($request->user()->isOwner() && !in_array($request->routeIs('panel.product.index'), ['panel.product.index'])) {
                abort(403, 'Unauthorized action.');
            }

            if (!$request->user()->isOwner() && !$request->user()->isAdmin()) {
                abort(403, 'Unauthorized action.');
            }

            return $next($request);
        });
    }

    public function index()
    {
        return view('backend.products.index', [
            'products' => $this->productService->select(10),
        ]);
    }

    public function create()
    {
        return view('backend.products.create', [
            'categories' => $this->categoryService->select(),
        ]);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        try {
            Product::create($data);

            return redirect()->route('panel.product.index')->with('success', 'Product successfully saved');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(string $uuid)
    {
        //
    }

    public function edit(string $uuid)
    {
        return view('backend.products.edit', [
            'product' => $this->productService->selectFirstBy('uuid', $uuid),
            'categories' => $this->categoryService->select(),
        ]);
    }

    public function update(ProductRequest $request, string $uuid)
    {
        $data = $request->validated();

        try {
            $product = $this->productService->selectFirstBy('uuid', $uuid);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);

            return redirect()->route('panel.product.index')->with('success', 'Product successfully updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $uuid)
    {
        $product = $this->productService->selectFirstBy('uuid', $uuid);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product successfully deleted',
        ]);
    }
}
