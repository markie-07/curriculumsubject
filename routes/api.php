<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\PrerequisiteController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\EquivalencyToolController;
use App\Http\Controllers\Api\GlobalSearchController; 

// --- Curriculum Routes ---
Route::get('/curriculums', [CurriculumController::class, 'index']);
Route::post('/curriculums', [CurriculumController::class, 'store']);
Route::get('/curriculums/{id}', [CurriculumController::class, 'getCurriculumData']);
Route::put('/curriculums/{id}', [CurriculumController::class, 'update']);
Route::delete('/curriculums/{id}', [CurriculumController::class, 'destroy']);
Route::post('/curriculums/save', [CurriculumController::class, 'saveSubjects']);
Route::post('/curriculum/remove-subject', [CurriculumController::class, 'removeSubject']);
Route::get('/curriculum/{id}/details', [CurriculumController::class, 'getCurriculumDetailsForExport']);
Route::get('/curriculums/{id}/subjects', [CurriculumController::class, 'getCurriculumSubjects']);
Route::post('/curriculums/{id}/add-subjects', [CurriculumController::class, 'addSubjectsToCurriculum']);
Route::post('/curriculums/{id}/approve', [CurriculumController::class, 'approve']);
Route::post('/curriculums/{id}/reject', [CurriculumController::class, 'reject']);


// --- Subject Routes ---
Route::get('/subjects', [SubjectController::class, 'index']);
Route::post('/subjects', [SubjectController::class, 'store']);
Route::get('/subjects/{id}', [SubjectController::class, 'show']);
Route::get('/subjects/{id}/versions', [SubjectController::class, 'getVersionHistory']);
Route::put('/subjects/{id}', [SubjectController::class, 'update']);
Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy']);

// --- Prerequisite Routes ---
Route::get('/prerequisites/{curriculum}', [PrerequisiteController::class, 'fetchData']);
Route::post('/prerequisites', [PrerequisiteController::class, 'store']);


// --- Grade Routes ---
Route::post('/grades', [GradeController::class, 'store']);
Route::get('/grades/{subjectId}', [GradeController::class, 'show']);
Route::get('/grades/{subjectId}/version-history', [GradeController::class, 'getGradeVersionHistory']);

// --- Curriculum Grade Routes ---
Route::get('/curriculum-grades', [GradeController::class, 'getAllCurriculumGrades']);
Route::post('/curriculum-grades', [GradeController::class, 'storeCurriculumGrades']);
Route::get('/curriculum-grades/{curriculumId}', [GradeController::class, 'getCurriculumGrades']);


// --- Equivalency Tool Routes ---
Route::get('/equivalencies', [EquivalencyToolController::class, 'getEquivalencies']);
Route::post('/equivalencies', [EquivalencyToolController::class, 'store']);
Route::patch('/equivalencies/{equivalency}', [EquivalencyToolController::class, 'update']);
Route::delete('/equivalencies/{equivalency}', [EquivalencyToolController::class, 'destroy']);

// --- Global Search Routes ---
Route::post('/global-search', [GlobalSearchController::class, 'search']);
Route::get('/quick-search/{type}', [GlobalSearchController::class, 'quickSearch']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});