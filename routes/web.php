<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Test1Controller;
use App\Http\Controllers\BikeFit\TopController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bikefit', [TopController::class, 'index'])->name('bikefit.index');

// /test1 のテストページ
Route::get('/test1', [Test1Controller::class, 'index'])->name('test1.index');

// /test2 のキャンバス真円描画テストページ（シンプルなクロージャで返却）
Route::get('/test2', function () {
    return view('test2');
})->name('test2.index');


// /test3 人の顔（目・口で喜怒哀楽を表現）
Route::get('/test3', function () {
    return view('test3');
})->name('test3.index');

// 生涯学習センター検索 画面1: 郵便番号入力
Route::get('/llc', function () {
    return view('llc.index');
})->name('llc.index');

// 生涯学習センター検索 画面2: Livewire で一覧表示
Route::get('/llc/search', function () {
    return view('llc.search');
})->name('llc.search');
