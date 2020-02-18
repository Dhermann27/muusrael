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

Route::group(['prefix' => 'campers', 'middleware' => 'auth'], function () {
    Route::get('', 'CamperController@index')->name('campers.index');
    Route::get('/{id?}', 'CamperController@index')->name('campers.index')->middleware('can:is-council');
    Route::post('/', 'CamperController@store')->name('campers.store');
});

Route::group(['prefix' => 'payment', 'middleware' => 'auth'], function () {
    Route::get('', 'PaymentController@index')->name('payment.index');
    Route::get('/{id?}', 'PaymentController@index')->name('payment.index')->middleware('can:is-council');
    Route::post('', 'PaymentController@store')->name('payment.store');
    Route::post('/{id?}', 'PaymentController@write')->name('payment.store')->middleware('can:is-super');
});

Route::group(['prefix' => 'household', 'middleware' => 'auth'], function () {
    Route::get('', 'HouseholdController@index')->name('household.index')->middleware('can:has-paid');
    Route::get('/{id?}', 'HouseholdController@index')->name('household.index')->middleware('can:is-council');
    Route::post('', 'HouseholdController@store')->name('household.store')->middleware('can:has-paid');
    Route::post('/{id?}', 'HouseholdController@store')->name('household.store')->middleware('can:is-super');
});

Route::group(['prefix' => 'roomselection', 'middleware' => 'auth'], function () {
    Route::get('/', 'RoomSelectionController@index')->name('roomselection.index')->middleware('can:has-paid');
    Route::post('/', 'RoomSelectionController@store')->name('roomselection.store')->middleware('can:has-paid');
//    Route::get('/map', 'RoomSelectionController@map')->middleware('auth', 'role:admin|council');
//Route::get('/{i}/{id}', 'RoomSelectionController@read')->middleware('auth', 'role:admin|council');
//Route::post('/f/{id}', 'RoomSelectionController@write')->middleware('auth', 'role:admin');
});

Route::group(['prefix' => 'workshopchoice', 'middleware' => 'auth'], function () {
    Route::get('/', 'WorkshopController@index')->name('workshopchoice.index')->middleware('can:has-paid');
    Route::post('/', 'WorkshopController@store')->name('workshopchoice.store')->middleware('can:has-paid');
//    Route::get('/workshopchoice/{i}/{id}', 'WorkshopController@read')->middleware('auth', 'role:admin|council');
//    Route::post('/workshopchoice/f/{id}', 'WorkshopController@write')->middleware('auth', 'role:admin');
});

Route::group(['prefix' => 'nametag', 'middleware' => 'auth'], function () {
    Route::get('/', 'NametagController@index')->name('nametag.index')->middleware('can:has-paid');
    Route::post('/', 'NametagController@store')->name('nametag.store')->middleware('can:has-paid');
//    Route::get('/nametag/{i}/{id}', 'NametagController@read')->middleware('auth', 'role:admin|council');
//    Route::post('/nametag/f/{id}', 'NametagController@write')->middleware('auth', 'role:admin');
});

Route::group(['prefix' => 'data'], function () {
    Route::get('loginsearch', 'DataController@loginsearch');
    Route::get('camperlist', 'DataController@campers')->middleware('can:is-council');
    Route::get('churchlist', 'DataController@churches')->middleware('auth');
    Route::get('steps', 'DataController@steps')->middleware('can:has-paid');
});

Route::get('/brochure', function () {
    $year = date('Y');
    if (!is_file(public_path('MUUSA_' . $year . '_Brochure.pdf'))) $year--;
    return redirect('MUUSA_' . $year . '_Brochure.pdf');
})->name('brochure');
