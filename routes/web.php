<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class,'index'])->name('index');
Route::get('/login',[HomeController::class,'login'])->name('login');
Route::post('login/post',[HomeController::class,'loginPost'])->name('login.post');

Route::prefix('admin')->middleware('auth')->group(function(){
    Route::get('/',[\App\Http\Controllers\UserController::class,'adminHome'])->name('AdminHome');
    Route::get('/update-chart', [UserController::class, 'updateChart'])->name('admin.updateChart');
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
    Route::get('/percentTransactionCategory',[\App\Http\Controllers\UserController::class,'percentTransactionCategory'])->name('percentTransactionCategory');
    Route::post('/percentTransactionCategory/submit',[\App\Http\Controllers\UserController::class,'percentTransactionCategorySubmit'])->name('percentTransactionCategory.submit');
    Route::post('/transaction/submit',[\App\Http\Controllers\UserController::class,'transactionSubmit'])->name('transaction.submit');
    Route::get('transaction/delete/{id}',[\App\Http\Controllers\UserController::class,'transactionDelete'])->name('transaction.delete');
    Route::get('/debt/list',[\App\Http\Controllers\UserController::class,'debtList'])->name('debt.list');
    Route::patch('/admin/debt/{debt}/pay',[\App\Http\Controllers\UserController::class,'payInstallment'])->name('admin.debt.pay');
    Route::get('/bankChecks',[\App\Http\Controllers\UserController::class,'bankChecks'])->name('bankChecks');
    Route::post('/bankChecks/submit',[\App\Http\Controllers\UserController::class,'bankCheckSubmit'])->name('bankChecks.submit');
    Route::get('bankCheck/delete/{id}',[\App\Http\Controllers\UserController::class,'bankCheckDelete'])->name('bankCheck.delete');


});
