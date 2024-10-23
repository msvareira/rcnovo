<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\FuncionariosController;
use App\Http\Controllers\ContatosController;
use App\Http\Controllers\ServicoOSAnexosController;
use App\Http\Controllers\FaturamentoController;
use App\Http\Controllers\PreventivasController;

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
        Route::post('/concluir/{id}', [OrdemServicoController::class, 'concluir'])->name('os.concluir');
        Route::post('/reabrir/{id}', [OrdemServicoController::class, 'reabrir'])->name('os.reabrir');
    });


    Route::prefix('servico_os')->group(function () {
        Route::prefix('anexos')->group(function () {
            Route::post('/store', [ServicoOSAnexosController::class, 'store'])->name('anexos.store');
            Route::any('/destroy/{id}', [ServicoOSAnexosController::class, 'destroy'])->name('anexos.destroy');
            Route::get('/download/{id}', [ServicoOSAnexosController::class, 'download'])->name('anexos.download');
        });    
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

    Route::prefix('faturamento')->group(function () {
        Route::get('/index', [FaturamentoController::class, 'index'])->name('faturamento.index');
        Route::get('/relatorios', [FaturamentoController::class, 'relatorios'])->name('faturamento.relatorios');
        Route::get('/notas', [FaturamentoController::class, 'notas'])->name('faturamento.notas');
        Route::get('/recebimentos', [FaturamentoController::class, 'recebimentos'])->name('faturamento.recebimentos');
    });

    Route::prefix('preventivas')->group(function () {
        Route::get('/index', [PreventivasController::class, 'index'])->name('preventivas.index');
        Route::any('/form/{id?}', [PreventivasController::class, 'form'])->name('preventivas.form');
        Route::any('/destroy/{id}', [PreventivasController::class, 'destroy'])->name('preventivas.destroy');
        Route::post('/store', [PreventivasController::class, 'store'])->name('preventivas.store');
        Route::post('/executar', [PreventivasController::class, 'executar'])->name('preventivas.executar');
        Route::get('/print/{id}', [PreventivasController::class, 'print'])->name('preventivas.print');
    });


    // Define a GET route with dynamic placeholders for route parameters
    Route::get('{routeName}/{name?}', [HomeController::class, 'pageView']);

    
});
