<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', [MovieController::class, 'home'])->name('home');
Route::get('/movies/all', [MovieController::class, 'homeAllMovie'])->name('home.movies.all');

Route::get('/schedule/{movie_id}', [MovieController::class, 'movieSchedule'])->name('schedule.detail');

Route::get('/signup', function () {
    return view('signUP');
})->name('signUp')->middleware('isGuest');

Route::get('/login', function () {
    return view('login');
})->name('login')->middleware('isGuest');

Route::post('/signup', [UserController::class, 'store'])->name('signup.store')->middleware('isGuest');
Route::post('/login', [UserController::class, 'login'])->name('login.auth')->middleware('isGuest');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/cinemas/list', [CinemaController::class, 'listCinema'])->name('cinemaList');
Route::get('/cinemas{cinema_id}/schedule', [CinemaController::class, 'cinemaSchedule'])->name('cinemas.schedule');
//pemberian awalan= frefix, jadi semua route yang ada di dalam group ini akan memiliki awalan /admin
//prefix digunakan ketika kita ingin mengelompokkan route yang memiliki kesamaan
//tanpa prefix 
// Route::get('/admin/dashboard', function ()...->name('admin.dashboard');
// Route::get('/admin/users', function ()...->name('admin.users');
//dengan prefix
//Route::prefix('/admin')->group(function () {
//    Route::get('/dashboard', function ()...->name('dashboard');
//    Route::get('/users', function ()...->name('users');
//});


Route::middleware('isUser')->group(function () {
    // halaman terkunci
    Route::get('/schedules/{scheduleId}/hours/{hourId}/show-seats', [TicketController::class, 'showSeats'])->name('schedule.show_seats');
    Route::prefix('/tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticket_id}/order', [TicketController::class, 'ticketOrder'])->name('order');
        Route::post('/{ticket_id}/barcode', [TicketController::class, 'createBarcode'])->name('barcode');
        Route::get('/{ticket_id}/payment', [TicketController::class, 'paymentPage'])->name('payment');
        Route::patch('/{ticket_id}/payment/proof', [TicketController::class, 'proofPayment'])->name('payment.proof');
        Route::get('/{ticket_id}', [TicketController::class, 'show'])->name('show');
        Route::get('/{ticket_id}/export/pdf', [TicketController::class, 'exportPdf'])->name('export-pdf');
    });
});
Route::middleware('isAdmin')->prefix('/admin')->name('admin.')->group(function () {
    Route::get('/tickets/charts', [TicketController::class, 'chartData'])->name('ticket.charts');
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::prefix('/cinemas')->name('cinemas.')->group(function () {
        Route::get('/',   [CinemaController::class, 'index'])->name('index');
        Route::get('/create', [CinemaController::class, 'create'])->name('create');
        Route::post('/store', [CinemaController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CinemaController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [CinemaController::class, 'destroy'])->name('delete');
        Route::put('/update/{id}', [CinemaController::class, 'update'])->name('update');
        Route::get('/export', [CinemaController::class, 'export'])->name('export');
        Route::get('/trash', [CinemaController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [CinemaController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [CinemaController::class, 'deletePermanen'])->name('delete-permanent');
        Route::get('/datatables', [CinemaController::class, 'dataForDataTable'])->name('dataTables');
    });

    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::get('/export', [UserController::class, 'export'])->name('export');
        Route::get('/trash', [UserController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [UserController::class, 'deletePermanen'])->name('delete-permanent');
        Route::get('/datatables', [UserController::class, 'dataForDataTable'])->name('dataTables');
    });

    Route::prefix('/movies')->name('movies.')->group(function () {
        Route::get('/', [MovieController::class, 'index'])->name('index');
        Route::get('/chart', [MovieController::class, 'chart'])->name('chart');
        Route::get('/create', [MovieController::class, 'create'])->name('create');
        Route::post('/store', [MovieController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MovieController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [MovieController::class, 'destroy'])->name('delete');
        Route::put('/update/{id}', [MovieController::class, 'update'])->name('update');
        Route::patch('/patch/{id}', [MovieController::class, 'patch'])->name('patch');
        Route::get('/export', [MovieController::class, 'export'])->name('export');
        Route::get('/datatables', [MovieController::class, 'dataForDataTable'])->name('dataTables');

        Route::get('/trash', [MovieController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [MovieController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [MovieController::class, 'deletePermanen'])->name('delete-permanent');
    });
});

Route::middleware('isStaff')->prefix('/staff')->name('staff.')->group(function () {
    Route::prefix('/promos')->name('promos.')->group(function () {
        Route::get('/', [PromoController::class, 'index'])->name('index');
        Route::get('/create', [PromoController::class, 'create'])->name('create');
        Route::post('/store', [PromoController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PromoController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [PromoController::class, 'destroy'])->name('delete');
        Route::put('/update/{id}', [PromoController::class, 'update'])->name('update');
        Route::patch('/patch/{id}', [PromoController::class, 'patch'])->name('patch');
        Route::get('/export', [PromoController::class, 'export'])->name('export');
        Route::get('/trash', [PromoController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [PromoController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [PromoController::class, 'deletePermanen'])->name('delete-permanent');
        Route::get('/datatables', [PromoController::class, 'dataForDataTable'])->name('dataTables');
    });
    Route::prefix('/schedules')->name('schedules.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::post('/store', [ScheduleController::class, 'store'])->name('store');
        Route::delete('/delete/{id}', [ScheduleController::class, 'destroy'])->name('delete');
        Route::get('/edit/{id}', [ScheduleController::class, 'edit'])->name('edit');
        Route::patch('/update/{id}', [ScheduleController::class, 'update'])->name('update');
        Route::get('/export', [ScheduleController::class, 'export'])->name('export');
        Route::get('/trash', [ScheduleController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [ScheduleController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent    /{id}', [ScheduleController::class, 'deletePermanen'])->name('delete-permanent');
        Route::get('/datatables', [ScheduleController::class, 'dataForDataTable'])->name('dataTables');
    });
});
