<?php

use Illuminate\Support\Facades\Route;

use App\Mail\CorreosMailable;
use Illuminate\Support\Facades\Mail;

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

// Route::get('contactanos', function (){
//     $correo = new CorreosMailable("USUARIO123123123123", true);

//     Mail::to('jordimartinezz13@gmail.com')->send($correo);


//     return "mensaje enviado";
// });