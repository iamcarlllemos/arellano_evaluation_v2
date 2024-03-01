<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FacultyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

require_once __DIR__ . '/fortify.php';
require_once __DIR__ . '/jetstream.php';

// ADMIN


Route::prefix('/')->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/branches', [BranchController::class, 'index'])->name('programs.branches');
    Route::get('/departments', [DepartmentController::class, 'index'])->name('programs.departments');
    Route::get('/courses', [CourseController::class, 'index'])->name('programs.courses');


    Route::prefix('accounts')->group(function() {
        Route::prefix('student')->group(function() {
            Route::get('/', [StudentController::class, 'index'])->name('students.index');
            Route::get('/{type}/form/{id?}', [StudentController::class, 'form'])->name('students.form');
            Route::post('/{type}/form', [StudentController::class, 'store'])->name('students.add.form');
            Route::put('/{type}/form/{op}/{id}', [StudentController::class, 'update'])->name('students.update.form');
            Route::delete('/delete/form/{id}', [StudentController::class, 'destroy'])->name('students.delete.form');
        });
        Route::prefix('faculty')->group(function() {
            Route::get('/', [FacultyController::class, 'index'])->name('faculty.index');
            Route::get('/{type}/form/{id?}', [FacultyController::class, 'form'])->name('faculty.form');
            Route::post('/{type}/form', [FacultyController::class, 'store'])->name('faculty.add.form');
            Route::put('/{type}/form/{op}/{id}', [FacultyController::class, 'update'])->name('faculty.update.form');
            Route::delete('/delete/form/{id}', [FacultyController::class, 'destroy'])->name('faculty.delete.form');
        });
    });

});
