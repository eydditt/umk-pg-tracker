<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardReportController;

Route::redirect('/', '/admin');

Route::get('/admin/reports/dashboard', [DashboardReportController::class, 'generate'])
    ->middleware(['web', 'auth']);
