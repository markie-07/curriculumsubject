<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\PrerequisiteController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\EquivalencyToolController; 

// --- Curriculum Routes ---
Route::get('/curriculums', [CurriculumController::class, 'index']);
Route::post('/curriculums', [CurriculumController::class, 'store']);
Route::get('/curriculums/{id}', [CurriculumController::class, 'getCurriculumData']);
Route::put('/curriculums/{id}', [CurriculumController::class, 'update']);
Route::delete('/curriculums/{id}', [CurriculumController::class, 'destroy']);
Route::post('/curriculums/save', [CurriculumController::class, 'saveSubjects']);
Route::post('/curriculum/remove-subject', [CurriculumController::class, 'removeSubject']);
Route::get('/curriculum/{id}/details', [CurriculumController::class, 'getCurriculumDetailsForExport']);


// --- Subject Routes ---
Route::get('/subjects', [SubjectController::class, 'index']);
Route::post('/subjects', [SubjectController::class, 'store']);
Route::get('/subjects/{id}', [SubjectController::class, 'show']);
Route::put('/subjects/{id}', [SubjectController::class, 'update']); // Add this line

// --- Prerequisite Routes ---
Route::get('/prerequisites/{curriculum}', [PrerequisiteController::class, 'fetchData']);
Route::post('/prerequisites', [PrerequisiteController::class, 'store']);


// --- Grade Routes ---
Route::post('/grades', [GradeController::class, 'store']);
Route::get('/grades/{subjectId}', [GradeController::class, 'show']);


// --- Equivalency Tool Routes ---
Route::post('/equivalencies', [EquivalencyToolController::class, 'store']);
Route::patch('/equivalencies/{equivalency}', [EquivalencyToolController::class, 'update']);
Route::delete('/equivalencies/{equivalency}', [EquivalencyToolController::class, 'destroy']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});