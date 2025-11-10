<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class,'index'])->name('index');
Route::get('/login',[HomeController::class,'login'])->name('login');
Route::post('login/post',[HomeController::class,'loginPost'])->name('login.post');

Route::prefix('admin')->middleware('auth')->group(function(){
    Route::get('/',[\App\Http\Controllers\UserController::class,'adminHome'])->name('AdminHome');
    Route::get('/adminList',[\App\Http\Controllers\UserController::class,'adminList'])->name('AdminList');
    Route::get('/adminAdd',[\App\Http\Controllers\UserController::class,'adminAdd'])->name('AdminAdd');
    Route::post('/createAdmin',[\App\Http\Controllers\UserController::class,'store'])->name('CreateAdmin');
    Route::get('/editAdmin/{id}',[\App\Http\Controllers\UserController::class,'editAdmin'])->name('EditAdmin');
    Route::put('/edit',[\App\Http\Controllers\UserController::class,'edit'])->name('editA');
    Route::delete('/delete/{id}',[\App\Http\Controllers\UserController::class,'delete'])->name('deleteA');
    Route::post('/restore/{id}',[\App\Http\Controllers\UserController::class,'restore'])->name('restoreA');
    Route::get('/logOut',[\App\Http\Controllers\UserController::class,'logOut'])->name('logOut');
    Route::get('/bankAccount/primary',[\App\Http\Controllers\UserController::class,'bankAccountPrimary'])->name('bankAccount.primary');
    Route::post('/bankAccount/editPrimary',[\App\Http\Controllers\UserController::class,'editPrimary'])->name('editPrimary');
    Route::get('/bankAccount/list',[\App\Http\Controllers\UserController::class,'bankAccountList'])->name('bankAccount.list');
    Route::post('/bankAccount/add',[\App\Http\Controllers\UserController::class,'addBankAccount'])->name('addBankAccount');
});
