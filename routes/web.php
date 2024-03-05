<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\QuestionnaireItemController;



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
   
    Route::prefix('programs')->group(function() {
        Route::get('/branches', [BranchController::class, 'index'])->name('programs.branches');
        Route::get('/departments', [DepartmentController::class, 'index'])->name('programs.departments');
        Route::get('/courses', [CourseController::class, 'index'])->name('programs.courses');
        Route::get('/subjects', [SubjectController::class, 'index'])->name('programs.subjects');
        Route::get('/school-year', [SchoolYearController::class, 'index'])->name('programs.school-year');
        Route::get('/criteria', [CriteriaController::class, 'index'])->name('programs.criteria');
        Route::get('/questionnaire', [QuestionnaireController::class, 'index'])->name('programs.questionnaire');
        Route::get('/questionnaire/{slug}', [QuestionnaireItemController::class, 'index'])->name('programs.questionnaire.item');
    });

    Route::prefix('accounts')->group(function() {
        Route::get('/students', [StudentController::class, 'index'])->name('accounts.student');
        Route::get('/faculty', [FacultyController::class, 'index'])->name('accounts.faculty');
    });

});
