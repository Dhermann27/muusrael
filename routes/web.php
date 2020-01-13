<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

// Public Pages

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/contact', 'ContactController@contactIndex')->name('contact');
Route::post('/contact', 'ContactController@contactStore')->name('contact');

Route::get('/cost', 'HomeController@campcost')->name('cost');
Route::get('/themespeaker', 'HomeController@themespeaker')->name('themespeaker');
Route::get('/scholarship', 'HomeController@scholarship')->name('scholarship');
Route::get('/programs', 'HomeController@programs')->name('programs');
Route::get('/housing', 'HomeController@housing')->name('housing');

Route::get('/workshops', 'WorkshopController@display')->name('workshops.display');
Route::get('/excursions', 'WorkshopController@excursions')->name('workshops.excursions');

Route::group(['prefix' => 'data'], function () {
    Route::get('loginsearch', 'DataController@loginsearch');
    //Route::get('camperlist', 'DataController@campers')->middleware('authorsomething');
    //Route::get('churchlist', 'DataController@churches');
});

Route::get('/brochure', function () {
    $year = date('Y');
    if (!is_file(public_path('MUUSA_' . $year . '_Brochure.pdf'))) $year--;
    return redirect('MUUSA_' . $year . '_Brochure.pdf');
})->name('brochure');
