<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EtapaProjetoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjetoController;
use App\Models\Categoria;
use App\Models\Projeto;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'storeLogin'])->name('login.store');
    Route::get('cadastro', [AuthController::class, 'register'])->name('register');
    Route::post('cadastro', [AuthController::class, 'storeRegister'])->name('register.store');
});

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/', function () {
    $user = auth()->user();

    return view('dashboard', [
        'totalProjetos' => Projeto::where('user_id', $user->id)->count(),
        'totalCategorias' => Categoria::where('user_id', $user->id)->count(),
        'projetosRecentes' => Projeto::with('categoria')->where('user_id', $user->id)->latest()->take(4)->get(),
        'categorias' => Categoria::withCount('projetos')->where('user_id', $user->id)->orderBy('nome')->take(6)->get(),
    ]);
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('categorias', CategoriaController::class);
    Route::resource('projetos', ProjetoController::class);

    Route::post('projetos/{projeto}/etapas', [EtapaProjetoController::class, 'store'])->name('projetos.etapas.store');
    Route::put('projetos/{projeto}/etapas/{etapaProjeto}', [EtapaProjetoController::class, 'update'])->name('projetos.etapas.update');
    Route::delete('projetos/{projeto}/etapas/{etapaProjeto}', [EtapaProjetoController::class, 'destroy'])->name('projetos.etapas.destroy');
});
