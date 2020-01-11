<?php

Auth::routes();

// Public Pages

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/contact', 'ContactController@contactIndex');
Route::post('/contact', 'ContactController@contactStore');

Route::get('/cost', 'HomeController@campcost');
Route::get('/themespeaker', 'HomeController@themespeaker');
Route::get('/scholarship', 'HomeController@scholarship');
Route::get('/programs', 'HomeController@programs');
Route::get('/housing', 'HomeController@housing');

Route::get('/workshops', 'WorkshopController@display');
Route::get('/excursions', 'WorkshopController@excursions');

Route::get('/brochure', function () {
    $year = date('Y');
    if (!is_file(public_path('MUUSA_' . $year . '_Brochure.pdf'))) $year--;
    return redirect('MUUSA_' . $year . '_Brochure.pdf');
});
