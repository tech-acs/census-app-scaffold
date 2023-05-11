<?php

use Uneca\Scaffold\Http\Controllers\HomeController;
use Uneca\Scaffold\Http\Controllers\Manage\AnnouncementController;
use Uneca\Scaffold\Http\Controllers\Manage\AreaController;
use Uneca\Scaffold\Http\Controllers\Manage\AreaHierarchyController;
use Uneca\Scaffold\Http\Controllers\Manage\ConnectionTestController;
use Uneca\Scaffold\Http\Controllers\Manage\SourceController;
use Uneca\Scaffold\Http\Controllers\Manage\RoleController;
use Uneca\Scaffold\Http\Controllers\Manage\UsageStatsController;
use Uneca\Scaffold\Http\Controllers\Manage\UserController;
use Uneca\Scaffold\Http\Controllers\Manage\UserSuspensionController;
use Uneca\Scaffold\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('scaffold::welcome');
})->name('landing')->middleware('web');

Route::middleware(['web', 'auth:sanctum', 'verified', 'log_page_views', 'enforce_2fa'])->group(function () {
    //Route::get('home', HomeController::class)->name('home');

    Route::get('notification', NotificationController::class)->name('notification.index');

    Route::middleware(['can:Super Admin'])->prefix('manage')->group(function () {
        Route::resource('role', RoleController::class)->only(['index', 'store', 'edit', 'destroy']);
        Route::resource('user', UserController::class)->only(['index', 'edit', 'update']);
        Route::get('user/{user}/suspension', UserSuspensionController::class)->name('user.suspension');

        Route::prefix('developer')->name('developer.')->group(function () {
            Route::get('source/{source}/test-connection', ConnectionTestController::class)->name('questionnaire.connection.test');
            Route::resource('source', SourceController::class);
            Route::resource('area-hierarchy', AreaHierarchyController::class)->only(['index']);
            Route::resource('area', AreaController::class)->only(['index', 'edit', 'update']);
            if (app()->environment('local')) {
                Route::resource('area-hierarchy', AreaHierarchyController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
                Route::resource('area', AreaController::class)->only(['create', 'store']);
                Route::delete('area/truncate', [AreaController::class, 'destroy'])->name('area.destroy');
            }
        });
        
        Route::resource('announcement', AnnouncementController::class)->only(['index', 'create', 'store']);
        Route::get('usage_stats', UsageStatsController::class)->name('usage_stats');
    });

    Route::fallback(function () {
        return redirect()->route('profile.show');
    });
});
