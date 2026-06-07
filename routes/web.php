<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminKonten as AdminKontenController;
use App\Http\Controllers\Admin\AdminZakat as AdminZakatController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminPosController;
use App\Http\Controllers\Admin\AdminPosZakatController;
use App\Http\Controllers\Admin\AdminAssessmentController;
use App\Http\Controllers\Admin\AdminRekeningController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\User\UserAssessmentController;

// User
Route::get('/', function () {
    return view('user.index');
});

Route::get('/jadwal-shalat', function () {
    return view('user.jadwal-shalat', [
        'prayerSchedule' => [
            ['name' => 'Subuh', 'time' => '04:38', 'note' => 'Persiapan dimulai 10 menit sebelum iqamah.'],
            ['name' => 'Dzuhur', 'time' => '11:52', 'note' => 'Kajian singkat ba’da dzuhur setiap Senin dan Kamis.'],
            ['name' => 'Ashar', 'time' => '15:12', 'note' => 'Ruang utama dibuka penuh 15 menit sebelum adzan.'],
            ['name' => 'Maghrib', 'time' => '17:46', 'note' => 'Tersedia kultum ba’da maghrib pada akhir pekan.'],
            ['name' => 'Isya', 'time' => '18:58', 'note' => 'Dilanjutkan halaqah pemuda setiap malam Jumat.'],
        ],
    ]);
})->name('pages.jadwal-shalat');

Route::view('/about', 'user.about')->name('pages.about');

Route::prefix('auth')->middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.submit');
});

Route::post('/auth/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('user.logout');

Route::prefix('auth')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.submit');
});

// admin
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {

    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // DASHBOARD
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // KONTEN
    Route::prefix('konten')->name('konten.')->group(function () {
        Route::get('/', [AdminKontenController::class, 'index'])->name('index');
        Route::get('/create', [AdminKontenController::class, 'create'])->name('create');
        Route::post('/', [AdminKontenController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminKontenController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminKontenController::class, 'update'])->name('update');
    });

    // ZAKAT
    Route::prefix('zakat')->name('zakat.')->group(function () {
        Route::get('/', [AdminZakatController::class, 'index'])->name('index');
        Route::get('/create', [AdminZakatController::class, 'create'])->name('create');
        Route::post('/', [AdminZakatController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminZakatController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminZakatController::class, 'update'])->name('update');
    });

    

    // POS
    Route::prefix('pos')->name('pos.')->group(function () {
        // POS Umum
        Route::get('/', [AdminPosController::class, 'index'])->name('index');
        Route::get('/create', [AdminPosController::class, 'create'])->name('create');
        Route::post('/', [AdminPosController::class, 'store'])->name('store');

        // POS Zakat Fitrah & Mal
        Route::prefix('zakat')->name('zakat.')->group(function () {
            Route::get('/', [AdminPosZakatController::class, 'index'])->name('index');
            Route::post('/', [AdminPosZakatController::class, 'store'])->name('store');
            Route::get('/{id}/receipt', [AdminPosZakatController::class, 'receipt'])->name('receipt');
            Route::post('/{id}/cancel', [AdminPosZakatController::class, 'cancel'])->name('cancel');
        });

        Route::patch('/transaksi/{id}/confirm', [AdminPosZakatController::class, 'confirm'])
            ->name('transaksi.confirm');
    });

    // ASSESSMENT ZAKAT
    Route::prefix('assessment')->name('assessment.')->group(function () {
        Route::get('/', [AdminAssessmentController::class, 'index'])->name('index');
    });

    // REKENING TUJUAN
    Route::prefix('rekening')->name('rekening.')->group(function () {
        Route::get('/', [AdminRekeningController::class, 'index'])->name('index');
        Route::get('/create', [AdminRekeningController::class, 'create'])->name('create');
        Route::post('/', [AdminRekeningController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminRekeningController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminRekeningController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminRekeningController::class, 'destroy'])->name('destroy');
    });

    // LIST USER
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
    });

    // DETEKSI UANG
    Route::prefix('deteksi')->name('detect.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DetectController::class, 'index'])->name('index');
        Route::post('/predict', [\App\Http\Controllers\Admin\DetectController::class, 'predict'])->name('predict');
    });
});

Route::prefix('user')->name('user.')->middleware(['auth', 'user.auth'])->group(function () {
    Route::prefix('assessment')->name('assessment.')->group(function () {
        Route::get('/', [UserAssessmentController::class, 'index'])->name('index');
        Route::get('/create', [UserAssessmentController::class, 'create'])->name('create');
        Route::post('/', [UserAssessmentController::class, 'store'])->name('store');
    });
});