<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Backend\TaxController;
use App\Http\Controllers\Backend\ChefController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Frontend\MapController;
use App\Http\Controllers\Backend\EventController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Frontend\MenuController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\SellingController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Backend\ForecastingController;
use App\Http\Controllers\Backend\RawMaterialController;
use App\Http\Controllers\Backend\TestimonialController;
use App\Http\Controllers\Backend\RawMaterialStockController;
use App\Http\Controllers\Backend\RawMaterialUsageController;
use App\Http\Controllers\Frontend\EventController as FrontendEventController;

Route::get('/', [FrontendController::class, 'index'])
    ->name('frontend.home');

Route::get('/tentang-kami', [AboutController::class, 'index'])->name('frontend.about');
Route::get('/layanan', [ServiceController::class, 'index'])->name('frontend.service');
Route::post('/layanan', [ServiceController::class, 'store'])->name('frontend.service.store');
Route::get('/acara', [FrontendEventController::class, 'index'])->name('frontend.event');
Route::get('/menu', [MenuController::class, 'index'])->name('frontend.menu');
Route::get('/hubungi', [ContactController::class, 'index'])->name('frontend.contact');
Route::get('/map', [MapController::class, 'index'])->name('frontend.map');

Route::prefix('panel')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('panel.dashboard.index');

    Route::resource('category', CategoryController::class)
        ->names('panel.category');

    Route::resource('raw-material', RawMaterialController::class)
        ->names('panel.raw-material');

    Route::resource('product', ProductController::class)
        ->names('panel.product');

    Route::resource('chef', ChefController::class)
        ->names('panel.chef');

    Route::resource('event', EventController::class)
        ->names('panel.event');

    Route::resource('tax', TaxController::class)
        ->names('panel.tax');

    Route::resource('testimonial', TestimonialController::class)
        ->names('panel.testimonial');


    // Transaction Management
    Route::prefix('order')->name('panel.order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/show/{uuid}', [OrderController::class, 'show'])->name('show');
        Route::put('/{uuid}/complete', [OrderController::class, 'complete'])->name('complete');
        Route::put('/{uuid}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::put('/{uuid}/confirm', [OrderController::class, 'confirm'])->name('confirm');
        Route::put('/{uuid}/process-payment', [OrderController::class, 'processPayment'])->name('process-payment');
    });

    // Selling Management
    Route::prefix('selling', SellingController::class)->name('panel.selling.')->group(function () {
        Route::get('/', [SellingController::class, 'index'])->name('index');
        Route::get('/show/{uuid}', [SellingController::class, 'show'])->name('show');
        Route::get('/generate-invoice/{uuid}', [SellingController::class, 'generateInvoice'])->name('generate-invoice');
    });

    // Inventory Management
    Route::prefix('raw-material-stock')->name('panel.raw-material-stock.')->group(function () {
        Route::get('/', [RawMaterialStockController::class, 'index'])->name('index');
        Route::get('/create', [RawMaterialStockController::class, 'create'])->name('create');
        Route::post('/store', [RawMaterialStockController::class, 'store'])->name('store');
        Route::get('/history', [RawMaterialStockController::class, 'history'])->name('history');
    });

    // Usage Tracking
    Route::prefix('raw-material-usage')->name('panel.raw-material-usage.')->group(function () {
        Route::get('/', [RawMaterialUsageController::class, 'index'])->name('index');
        Route::get('/create', [RawMaterialUsageController::class, 'create'])->name('create');
        Route::post('/store', [RawMaterialUsageController::class, 'store'])->name('store');
        Route::get('/report', [RawMaterialUsageController::class, 'report'])->name('report');
        Route::get('/report/export', [RawMaterialUsageController::class, 'exportReport'])->name('export-report');
    });

    // Forecasting
    Route::prefix('forecasting')->name('panel.forecasting.')->group(function () {
        Route::get('/', [ForecastingController::class, 'index'])->name('index');
        Route::post('/generate', [ForecastingController::class, 'generate'])->name('generate');
        Route::get('/history', [ForecastingController::class, 'history'])->name('history');
        Route::get('/accuracy', [ForecastingController::class, 'accuracy'])->name('accuracy');
        Route::get('/{uuid}/edit-actual', [ForecastingController::class, 'editActual'])->name('edit-actual');
        Route::put('/{uuid}/update-actual', [ForecastingController::class, 'updateActual'])->name('update-actual');
    });

    // Reports Routes
    Route::prefix('reports')->name('panel.report.')->group(function () {
        // Sales Reports
        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/', [ReportController::class, 'salesIndex'])->name('index');
            Route::get('/daily', [ReportController::class, 'salesDaily'])->name('daily');
            Route::get('/monthly', [ReportController::class, 'salesMonthly'])->name('monthly');
            Route::get('/yearly', [ReportController::class, 'salesYearly'])->name('yearly');
            Route::post('/export-daily', [ReportController::class, 'exportDailySales'])->name('export-daily');
            Route::post('/export-monthly', [ReportController::class, 'exportMonthlySales'])->name('export-monthly');
            Route::post('/export-yearly', [ReportController::class, 'exportYearlySales'])->name('export-yearly');
            Route::post('/export-products', [ReportController::class, 'exportProductSales'])->name('export-products');
        });

        // Inventory Reports
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/', [ReportController::class, 'inventoryIndex'])->name('index');
            Route::get('/stock', [ReportController::class, 'inventoryStock'])->name('stock');
            Route::get('/usage', [ReportController::class, 'inventoryUsage'])->name('usage');
            Route::post('/export-stock', [ReportController::class, 'exportStockMovement'])->name('export-stock');
            Route::post('/export-usage', [ReportController::class, 'exportUsageReport'])->name('export-usage');
        });

        // Customer Reports
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', [ReportController::class, 'customersIndex'])->name('index');
            Route::get('/orders', [ReportController::class, 'customersOrders'])->name('orders');
            Route::get('/behavior', [ReportController::class, 'customersBehavior'])->name('behavior');
            Route::post('/export-orders', [ReportController::class, 'exportOrderHistory'])->name('export-orders');
            Route::post('/export-behavior', [ReportController::class, 'exportCustomerBehavior'])->name('export-behavior');
        });
    });

    // User Management
    Route::prefix('user')->name('panel.user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{uuid}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{uuid}', [UserController::class, 'update'])->name('update');
        Route::delete('/{uuid}', [UserController::class, 'destroy'])->name('destroy');
        Route::put('/{uuid}/change-password', [UserController::class, 'changePassword'])->name('change-password');
        Route::put('/{uuid}/change-role', [UserController::class, 'changeRole'])->name('change-role');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/check-payment-status/{orderId}', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
    Route::get('/payment/finish/{uuid}', [PaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/payment/error/{uuid}', [PaymentController::class, 'error'])->name('payment.error');
    Route::get('/payment/pending/{uuid}', [PaymentController::class, 'pending'])->name('payment.pending');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
