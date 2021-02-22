<?php

use App\Models\Stock;
use App\Models\User;
use App\Notifications\importantStockUpdate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('/mail-preview', function () {
    $user = User::factory()->create();
    return (new importantStockUpdate(Stock::first()))->toMail($user);
});
