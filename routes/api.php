<?php

use App\Http\Controllers\Api\Admin\AlertController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\BlogPostController;
use App\Http\Controllers\Api\Admin\ContractController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\ExpenseController;
use App\Http\Controllers\Api\Admin\LocationController;
use App\Http\Controllers\Api\Admin\MaintenanceController;
use App\Http\Controllers\Api\Admin\PaymentController;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\ReservationController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\VehicleBrandController;
use App\Http\Controllers\Api\Admin\VehicleCategoryController;
use App\Http\Controllers\Api\Admin\VehicleController;
use App\Http\Controllers\Api\Admin\VehiclePhotoController;
use App\Http\Controllers\Api\LookupController;
use App\Http\Controllers\Api\Public\PublicBlogPostController;
use App\Http\Controllers\Api\Public\PublicContactMessageController;
use App\Http\Controllers\Api\Public\PublicLocationController;
use App\Http\Controllers\Api\Public\PublicReservationController;
use App\Http\Controllers\Api\Public\PublicVehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'app' => 'Limosud Cars API',
    ]);
});

Route::prefix('public')->group(function (): void {
    Route::get('/lookups', [LookupController::class, 'publicIndex']);
    Route::get('/locations', [PublicLocationController::class, 'index']);
    Route::get('/vehicles', [PublicVehicleController::class, 'index']);
    Route::get('/vehicles/{vehicle}/availability', [PublicVehicleController::class, 'availability']);
    Route::get('/vehicles/{vehicle}/schedule', [PublicVehicleController::class, 'schedule']);
    Route::get('/vehicles/{slug}', [PublicVehicleController::class, 'show']);
    Route::post('/reservations', [PublicReservationController::class, 'store']);
    Route::post('/reservations/check-availability', [PublicReservationController::class, 'checkAvailability']);
    Route::post('/contact-messages', [PublicContactMessageController::class, 'store']);
    Route::get('/blog-posts', [PublicBlogPostController::class, 'index']);
    Route::get('/blog-posts/{slug}', [PublicBlogPostController::class, 'show']);
});

Route::prefix('admin')->group(function (): void {
    Route::post('/auth/login', [AuthController::class, 'login']);
});

Route::middleware('auth:api')->prefix('admin')->group(function (): void {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::patch('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/lookups', [LookupController::class, 'adminIndex']);

    Route::get('/dashboard/statistics', [DashboardController::class, 'statistics'])->middleware('permission:dashboard.view');

    Route::get('/users', [UserController::class, 'index'])->middleware('permission:users.view');
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('permission:users.view');
    Route::patch('/users/{user}', [UserController::class, 'update'])->middleware('permission:users.update');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('permission:users.update');
    Route::patch('/users/{user}/permissions', [UserController::class, 'syncPermissions'])->middleware('permission:permissions.assign');

    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:roles.view');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->middleware('permission:roles.view');
    Route::patch('/roles/{role}/permissions', [RoleController::class, 'syncPermissions'])->middleware('permission:permissions.assign');

    Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:permissions.view');

    Route::get('/dashboard/revenue', [DashboardController::class, 'revenue'])->middleware('permission:dashboard.view');
    Route::get('/dashboard/expenses', [DashboardController::class, 'expenses'])->middleware('permission:dashboard.view');

    Route::get('/vehicles', [VehicleController::class, 'index'])->middleware('permission:vehicles.view');
    Route::post('/vehicles', [VehicleController::class, 'store'])->middleware('permission:vehicles.create');
    Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->middleware('permission:vehicles.view');
    Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->middleware('permission:vehicles.update');
    Route::patch('/vehicles/{vehicle}', [VehicleController::class, 'update'])->middleware('permission:vehicles.update');
    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->middleware('permission:vehicles.delete');
    Route::get('/vehicles/{vehicle}/maintenances', [MaintenanceController::class, 'forVehicle'])->middleware('permission:maintenance.view');
    Route::get('/vehicles/{vehicle}/expenses', [ExpenseController::class, 'forVehicle'])->middleware('permission:expenses.view');
    Route::post('/vehicles/{vehicle}/photos', [VehiclePhotoController::class, 'store'])->middleware('permission:vehicles.update');
    Route::patch('/vehicle-photos/{photo}', [VehiclePhotoController::class, 'update'])->middleware('permission:vehicles.update');
    Route::delete('/vehicle-photos/{photo}', [VehiclePhotoController::class, 'destroy'])->middleware('permission:vehicles.update');

    Route::get('/vehicle-brands', [VehicleBrandController::class, 'index'])->middleware('permission:vehicle_brands.view');
    Route::post('/vehicle-brands', [VehicleBrandController::class, 'store'])->middleware('permission:vehicle_brands.create');
    Route::get('/vehicle-brands/{brand}', [VehicleBrandController::class, 'show'])->middleware('permission:vehicle_brands.view');
    Route::put('/vehicle-brands/{brand}', [VehicleBrandController::class, 'update'])->middleware('permission:vehicle_brands.update');
    Route::patch('/vehicle-brands/{brand}', [VehicleBrandController::class, 'update'])->middleware('permission:vehicle_brands.update');
    Route::delete('/vehicle-brands/{brand}', [VehicleBrandController::class, 'destroy'])->middleware('permission:vehicle_brands.delete');
    Route::post('/vehicle-brands/{brand}/image', [VehicleBrandController::class, 'storeImage'])->middleware('permission:vehicle_brands.update');
    Route::delete('/vehicle-brands/{brand}/image', [VehicleBrandController::class, 'destroyImage'])->middleware('permission:vehicle_brands.update');

    Route::get('/vehicle-categories', [VehicleCategoryController::class, 'index'])->middleware('permission:vehicle_categories.view');
    Route::post('/vehicle-categories', [VehicleCategoryController::class, 'store'])->middleware('permission:vehicle_categories.create');
    Route::get('/vehicle-categories/{category}', [VehicleCategoryController::class, 'show'])->middleware('permission:vehicle_categories.view');
    Route::put('/vehicle-categories/{category}', [VehicleCategoryController::class, 'update'])->middleware('permission:vehicle_categories.update');
    Route::patch('/vehicle-categories/{category}', [VehicleCategoryController::class, 'update'])->middleware('permission:vehicle_categories.update');
    Route::delete('/vehicle-categories/{category}', [VehicleCategoryController::class, 'destroy'])->middleware('permission:vehicle_categories.delete');

    Route::get('/customers', [CustomerController::class, 'index'])->middleware('permission:customers.view');
    Route::post('/customers', [CustomerController::class, 'store'])->middleware('permission:customers.create');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->middleware('permission:customers.view');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->middleware('permission:customers.update');
    Route::patch('/customers/{customer}', [CustomerController::class, 'update'])->middleware('permission:customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->middleware('permission:customers.delete');
    Route::post('/customers/{customer}/documents', [CustomerController::class, 'storeDocument'])->middleware('permission:customers.update');
    Route::delete('/customer-documents/{document}', [CustomerController::class, 'destroyDocument'])->middleware('permission:customers.update');

    Route::get('/locations', [LocationController::class, 'index'])->middleware('permission:locations.view');
    Route::post('/locations', [LocationController::class, 'store'])->middleware('permission:locations.create');
    Route::get('/locations/{location}', [LocationController::class, 'show'])->middleware('permission:locations.view');
    Route::put('/locations/{location}', [LocationController::class, 'update'])->middleware('permission:locations.update');
    Route::patch('/locations/{location}', [LocationController::class, 'update'])->middleware('permission:locations.update');
    Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->middleware('permission:locations.delete');

    Route::get('/reservations', [ReservationController::class, 'index'])->middleware('permission:reservations.view');
    Route::post('/reservations', [ReservationController::class, 'store'])->middleware('permission:reservations.create');
    Route::post('/reservations/check-availability', [ReservationController::class, 'checkAvailability'])->middleware('permission:reservations.view');
    Route::get('/reservations/vehicle-availability', [ReservationController::class, 'vehicleAvailability'])->middleware('permission:reservations.view');
    Route::get('/reservations-calendar', [ReservationController::class, 'calendar'])->middleware('permission:reservations.view');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->middleware('permission:reservations.view');
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->middleware('permission:reservations.update');
    Route::patch('/reservations/{reservation}', [ReservationController::class, 'update'])->middleware('permission:reservations.update');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->middleware('permission:reservations.delete');
    Route::post('/reservations/{reservation}/confirm', [ReservationController::class, 'confirm'])->middleware('permission:reservations.confirm');
    Route::post('/reservations/{reservation}/start', [ReservationController::class, 'start'])->middleware('permission:reservations.start');
    Route::post('/reservations/{reservation}/complete', [ReservationController::class, 'complete'])->middleware('permission:reservations.complete');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->middleware('permission:reservations.cancel');
    Route::post('/reservations/{reservation}/reject', [ReservationController::class, 'reject'])->middleware('permission:reservations.reject');
    Route::post('/reservations/{reservation}/reopen', [ReservationController::class, 'reopen'])->middleware('permission:reservations.update');
    Route::get('/reservations/{reservation}/payment-summary', [PaymentController::class, 'summary'])->middleware('permission:payments.view');
    Route::get('/reservations/{reservation}/contract/form', [ContractController::class, 'form'])->middleware('permission:contracts.generate');
    Route::post('/reservations/{reservation}/contract/generate', [ContractController::class, 'generate'])->middleware('permission:contracts.generate');
    Route::get('/reservations/{reservation}/contract', [ContractController::class, 'showByReservation'])->middleware('permission:contracts.view');

    Route::get('/payments', [PaymentController::class, 'index'])->middleware('permission:payments.view');
    Route::post('/payments', [PaymentController::class, 'store'])->middleware('permission:payments.manage');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->middleware('permission:payments.view');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->middleware('permission:payments.manage');
    Route::patch('/payments/{payment}', [PaymentController::class, 'update'])->middleware('permission:payments.manage');
    Route::post('/payments/{payment}/cancel', [PaymentController::class, 'cancel'])->middleware('permission:payments.manage');

    Route::get('/contracts/{contract}/download', [ContractController::class, 'download'])->middleware('permission:contracts.view');
    Route::post('/contracts/{contract}/signed', [ContractController::class, 'signed'])->middleware('permission:contracts.update');
    Route::post('/contracts/{contract}/cancel', [ContractController::class, 'cancel'])->middleware('permission:contracts.update');

    Route::get('/maintenances', [MaintenanceController::class, 'index'])->middleware('permission:maintenance.view');
    Route::post('/maintenances', [MaintenanceController::class, 'store'])->middleware('permission:maintenance.create');
    Route::get('/maintenances/upcoming', [MaintenanceController::class, 'upcoming'])->middleware('permission:maintenance.view');
    Route::get('/maintenances/{maintenance}', [MaintenanceController::class, 'show'])->middleware('permission:maintenance.view');
    Route::put('/maintenances/{maintenance}', [MaintenanceController::class, 'update'])->middleware('permission:maintenance.update');
    Route::patch('/maintenances/{maintenance}', [MaintenanceController::class, 'update'])->middleware('permission:maintenance.update');
    Route::delete('/maintenances/{maintenance}', [MaintenanceController::class, 'destroy'])->middleware('permission:maintenance.delete');

    Route::get('/expenses', [ExpenseController::class, 'index'])->middleware('permission:expenses.view');
    Route::post('/expenses', [ExpenseController::class, 'store'])->middleware('permission:expenses.create');
    Route::get('/expenses/monthly-summary', [ExpenseController::class, 'monthlySummary'])->middleware('permission:expenses.view');
    Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->middleware('permission:expenses.view');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->middleware('permission:expenses.update');
    Route::patch('/expenses/{expense}', [ExpenseController::class, 'update'])->middleware('permission:expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->middleware('permission:expenses.delete');

    Route::get('/alerts', [AlertController::class, 'index'])->middleware('permission:alerts.view');
    Route::get('/alerts/pending', [AlertController::class, 'pending'])->middleware('permission:alerts.view');
    Route::post('/alerts', [AlertController::class, 'store'])->middleware('permission:alerts.create');
    Route::post('/alerts/generate', [AlertController::class, 'generate'])->middleware('permission:alerts.create');
    Route::get('/alerts/{alert}', [AlertController::class, 'show'])->middleware('permission:alerts.view');
    Route::patch('/alerts/{alert}/seen', [AlertController::class, 'seen'])->middleware('permission:alerts.update');
    Route::patch('/alerts/{alert}/done', [AlertController::class, 'done'])->middleware('permission:alerts.update');
    Route::patch('/alerts/{alert}/ignore', [AlertController::class, 'ignore'])->middleware('permission:alerts.update');

    Route::get('/blog-posts', [BlogPostController::class, 'index'])->middleware('permission:site_pages.view');
    Route::post('/blog-posts', [BlogPostController::class, 'store'])->middleware('permission:site_pages.create');
    Route::get('/blog-posts/{blogPost}', [BlogPostController::class, 'show'])->middleware('permission:site_pages.view');
    Route::patch('/blog-posts/{blogPost}', [BlogPostController::class, 'update'])->middleware('permission:site_pages.update');
    Route::put('/blog-posts/{blogPost}', [BlogPostController::class, 'update'])->middleware('permission:site_pages.update');
    Route::delete('/blog-posts/{blogPost}', [BlogPostController::class, 'destroy'])->middleware('permission:site_pages.delete');
});
