<?php

use App\Http\Middleware\UserAuthentication;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImpersonateController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InventorySummaryController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SalesSummaryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaxRateController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\DiscountController;
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
        Route::get('/attendances/toggle', 'showTogglePage')->name('attendances.toggle');
        Route::post('/attendances/toggle', 'toggleCheckInOut')->name('attendances.toggle.submit');
        Route::get('/attendances/{id}', 'show')->name('attendances.show');
        Route::get('/attendances/{id}/edit', 'edit')->name('attendances.edit');
        Route::put('/attendances/{id}', 'update')->name('attendances.update');
        Route::get('/attendances/scan-checkin', 'scanCheckIn')->name('attendances.scan-checkin');
    });

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});