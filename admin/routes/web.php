<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\FuncionariosController;
use App\Http\Controllers\ContatosController;
use Illuminate\Support\Facades\Auth;

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


Auth::routes();


// Define a group of routes with 'auth' middleware applied
Route::middleware(['auth'])->group(function () {
    // Define a GET route for the root URL ('/')
    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::prefix('os')->group(function () {
        Route::get('/index', [OrdemServicoController::class, 'index'])->name('os.index');
        Route::any('/form/{id?}', [OrdemServicoController::class, 'form'])->name('os.form'); ;
        Route::any('/destroy/{id}', [OrdemServicoController::class, 'destroy'])->name('os.destroy');
        Route::post('/store', [OrdemServicoController::class, 'store'])->name('os.store');
        Route::get('/edit/{id}', [OrdemServicoController::class, 'edit'])->name('os.edit');
        Route::get('/print/{id}', [OrdemServicoController::class, 'print'])->name('os.print');
        Route::post('/send-email/{id}', [OrdemServicoController::class, 'sendEmail'])->name('os.sendEmail');
    });

    Route::prefix('clientes')->group(function () {
        Route::get('/index', [ClientesController::class, 'index'])->name('clientes.index');
        Route::any('/form/{id?}', [ClientesController::class, 'form'])->name('clientes.form');
        Route::any('/destroy/{id}', [ClientesController::class, 'destroy'])->name('clientes.destroy');
        Route::post('/store', [ClientesController::class, 'store'])->name('clientes.store');
        Route::post('/contatos', [ClientesController::class, 'store'])->name('clientes.contatos');
        
    });

    Route::prefix('funcionarios')->group(function () {
        Route::get('/index', [FuncionariosController::class, 'index'])->name('funcionarios.index');
        Route::any('/form/{id?}', [FuncionariosController::class, 'form'])->name('funcionarios.form');
        Route::any('/destroy/{id}', [FuncionariosController::class, 'destroy'])->name('funcionarios.destroy');
        Route::post('/store', [FuncionariosController::class, 'store'])->name('funcionarios.store');
        
    });

    Route::prefix('contatos')->group(function () {
        Route::get('/index', [ContatosController::class, 'index'])->name('contatos.index');
        Route::any('/form/{id?}', [ContatosController::class, 'form'])->name('contatos.form');
        Route::any('/destroy/{id}', [ContatosController::class, 'destroy'])->name('contatos.destroy');
        Route::post('/store', [ContatosController::class, 'store'])->name('contatos.store');
    });


    // Define a GET route with dynamic placeholders for route parameters
    Route::get('{routeName}/{name?}', [HomeController::class, 'pageView']);

    
});
