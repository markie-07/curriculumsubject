<?php

use App\Http\Controllers\CurriculumController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrerequisiteController;
use App\Http\Controllers\SubjectHistoryController;
use App\Http\Controllers\EquivalencyToolController;
use App\Http\Controllers\CurriculumExportToolController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubjectExportController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/curriculum_builder', function () {
    return view('curriculum_builder');
})->name('curriculum_builder');

Route::get('/course-builder', function () {
    return view('course_builder');
})->name('course_builder');

Route::get('/subject_mapping', function () {
    return view('subject_mapping');
})->name('subject_mapping');

Route::get('/pre_requisite', [PrerequisiteController::class, 'index'])->name('pre_requisite');

// This is the only route needed for the grade setup page itself.
Route::get('/grade-setup', [GradeController::class, 'setup'])->name('grade_setup');

Route::get('/equivalency_tool', [EquivalencyToolController::class, 'index'])->name('equivalency_tool');

Route::get('/subject_history', [SubjectHistoryController::class, 'index'])->name('subject_history');
Route::post('/subject_history/{history}/retrieve', [SubjectHistoryController::class, 'retrieve'])->name('subject_history.retrieve');


// CHED Compliance Validator
Route::get('/compliance-validator', function () {
    $curriculums = [];
    $cmos = [];
    return view('compliance_validator', compact('curriculums', 'cmos'));
})->name('compliance.validator');

Route::post('/compliance-validator/validate', function () {
    // Handle validation logic here
})->name('ched.validator.validate');

// CURRICULUM EXPORT
Route::get('/curriculum_export_tool', [CurriculumExportToolController::class, 'index'])->name('curriculum_export_tool');
Route::post('/curriculum_export_tool', [CurriculumExportToolController::class, 'store'])->name('curriculum_export_tool.store');

Route::get('/subjects/{subjectId}/export-pdf', [SubjectExportController::class, 'exportPdf'])->name('subjects.export-pdf');
