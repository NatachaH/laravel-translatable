<?php

// Change language
Route::get('lang/{lang}', '\Nh\Translatable\Http\Controllers\LocalizationController')->name('localization');
