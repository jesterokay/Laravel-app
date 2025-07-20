<?php

use App\Http\Middleware\UserAuthentication;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\System\CurrencyController;
use App\Http\Controllers\CRM\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\ImpersonateController;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\Finance\ExpenseController;
use App\Http\Controllers\Inventory\InventorySummaryController;
use App\Http\Controllers\Finance\PaymentMethodController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Purchases\PurchaseController;
use App\Http\Controllers\Sales\SaleController;
use App\Http\Controllers\Sales\SalesSummaryController;
use App\Http\Controllers\CRM\SupplierController;
use App\Http\Controllers\Finance\TaxRateController;
use App\Http\Controllers\Inventory\UnitController;
use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\HR\PositionController;
use App\Http\Controllers\HR\DepartmentController;
use App\Http\Controllers\System\RoleController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\System\PermissionController;
use App\Http\Controllers\Sales\PromotionController;
use App\Http\Controllers\Sales\DiscountController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    $ui = request()->query('ui', 'auth'); // Default to 'auth' if no query param
    if ($ui === 'admin') {
        return view('admin.login');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('user-auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('sales_summaries', SalesSummaryController::class);
    Route::resource('inventory_summaries', InventorySummaryController::class);
    Route::resource('currencies', CurrencyController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('employees', EmployeeController::class);
    
    Route::middleware('superadmin')->group(function () {
        Route::get('/impersonate/{employeeId}', [ImpersonateController::class, 'impersonate'])->name('impersonate');
    });
    Route::get('/stop-impersonating', [ImpersonateController::class, 'stopImpersonating'])->name('stop-impersonating');
    
    Route::resource('purchases', PurchaseController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('sales', SaleController::class);
    Route::resource('tax_rates', TaxRateController::class);
    Route::resource('units', UnitController::class);
    Route::resource('payment_methods', PaymentMethodController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('discounts', DiscountController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('roles', RoleController::class);
    
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/assign', [PermissionController::class, 'createAssign'])->name('permissions.assign');
    Route::post('/permissions/assign', [PermissionController::class, 'assignPermission'])->name('permissions.assign.store');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');

    Route::controller(AttendanceController::class)->group(function () {
        Route::get('/attendances', 'index')->name('attendances.index');
        Route::get('/attendances/create', 'create')->name('attendances.create');
        Route::post('/attendances', 'store')->name('attendances.store');
        Route::post('/attendances/toggle', 'toggle')->name('attendances.toggle.submit');
        Route::get('/attendances/{id}', 'show')->name('attendances.show');
        Route::get('/attendances/{id}/edit', 'edit')->name('attendances.edit');
        Route::put('/attendances/{id}', 'update')->name('attendances.update');
        Route::delete('/attendances/{id}', 'destroy')->name('attendances.destroy');
    });

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/test-db', function () {
    try {
        \DB::connection()->getPdo();
        return "Database connection successful!";
    } catch (\Exception $e) {
        return "Database connection failed: " . $e->getMessage();
    }
});