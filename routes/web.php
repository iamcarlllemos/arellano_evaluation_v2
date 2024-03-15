<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\SchoolYearController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\Admin\QuestionnaireController;
use App\Http\Controllers\Admin\QuestionnaireItemController;
use App\Http\Controllers\Admin\FacultyTemplateController;


use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\AdministratorController;
use App\Http\Controllers\Admin\CurriculumTemplateController;

use App\Http\Controllers\User\LoginController as UserLoginController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;


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


Route::prefix('admin')->middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function() {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
   
    Route::middleware('role:admin,superadmin')->prefix('programs')->group(function() {
        Route::get('/departments', [DepartmentController::class, 'index'])->name('programs.departments');
        Route::get('/courses', [CourseController::class, 'index'])->name('programs.courses');
        Route::get('/subjects', [SubjectController::class, 'index'])->name('programs.subjects');
    });

    Route::middleware('role:superadmin')->prefix('programs')->group(function() {
        Route::get('/branches', [BranchController::class, 'index'])->name('programs.branches');
        Route::get('/school-year', [SchoolYearController::class, 'index'])->name('programs.school-year');
        Route::get('/criteria', [CriteriaController::class, 'index'])->name('programs.criteria');
        Route::get('/questionnaire', [QuestionnaireController::class, 'index'])->name('programs.questionnaire');
        Route::get('/questionnaire/{slug}', [QuestionnaireItemController::class, 'index'])->name('programs.questionnaire.item');
    });

    Route::middleware('role:admin,superadmin')->prefix('linking')->group(function() {
        Route::get('/curriculum-template', [CurriculumTemplateController::class, 'index'])->name('linking.curriculum-template');
        Route::get('/faculty-template', [FacultyTemplateController::class, 'index'])->name('linking.faculty-template');
    });

    Route::prefix('accounts')->group(function() {
        Route::get('/students', [StudentController::class, 'index'])->name('accounts.student')->middleware('role:admin,superadmin');
        Route::get('/faculty', [FacultyController::class, 'index'])->name('accounts.faculty')->middleware('role:admin,superadmin');
        Route::get('/administrators', [AdministratorController::class, 'index'])->name('accounts.administrator')->middleware('role:superadmin');
    });
});

Route::prefix('user')->group(function() {
    Route::get('login', [UserLoginController::class, 'index'])->name('user.index');
    Route::post('login', [UserLoginController::class, 'login'])->name('user.login');
    Route::any('logout', [UserLoginController::class, 'logout'])->name('user.logout');

    Route::middleware('students')->group(function() {
        Route::get('dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    });
});

