<?php

use Illuminate\Support\Arr;

// Change language
Route::get('lang/{lang}', function(string $lang){

  // Clean the previous url
    $previous = str_replace(url('/'), '', url()->previous());

    // Get all segments
    $segments = explode('/',$previous);

    // Get the available languages
    $availables = config('localization.languages');

    // Check if the language is available, otherwise take the default
    $locale = Arr::has($availables, $lang) ? $lang : app()->getLocale();

    // Change the segments
    $segments[1] = $locale;

    // Get the new url
    $url = implode('/', $segments);

    // Redirect
    return redirect($url);

})->name('localization');
