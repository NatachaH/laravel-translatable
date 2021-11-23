# Installation

Install the package via composer:

```
composer require nh/translatable
```

Publish the config files for the translatable:
*You can define the available languages in this config file*

```
php artisan vendor:publish --tag=translatable
```

To make a model translatable, add the **Translatable** trait to your model:

```
use Nh\Translatable\Traits\Translatable;

use Translatable;
```

## Route

To make a route translatable, add the middleware **localization** and the prefixe **{locale}** to your route:

```
Route::middleware('localization')->prefix('{locale}')->get('/', function () {
    return view('welcome');
});
```

And for redirect if url is *http://www.mysite.com/* to *http://www.mysite.com/en*

```
Route::middleware('localization')->get('/', function () {
  return redirect()->route('welcome');
});
```

## Change the language

You can change the language of the website by using the **localization** route:

```
@foreach (config('translatable.languages') as $key => $value)
  <a href="{{ route('localization', ['lang' => $key]) }}">
    {{ $value }}
  </a>
@endforeach
```

## Javascript

If you need to display/hide elements by lang you can use the JS file:
*This is a global change in the current page, not for a specific zone !*

```
require('../../vendor/nh/translatable/resources/js/translatable');
```

Then in your html add the links with the class **.lang-toggle** and the data attribute **data-lang**:
*To display a different value than the abbreviation of the language, you can add the attribute data-lang-value*

```
The current language is: <span class="lang-toggle-current">Français</span>

<nav>
  <a href="#" class="lang-toggle" data-lang="fr" data-lang-value="Français">FR</a>
  <a href="#" class="lang-toggle" data-lang="en" data-lang-value="Anglais">EN</a>
</nav>
```

And add the data attribute **data-lang** to the content to filter by lang:

```
<div data-lang="fr">
  Uniquement en Français
</div>
<div data-lang="en">
  Only in english
</div>
<div data-lang="en|fr">
  Multiple languages
</div>
```

# Exemples

Exemple for a form with multiple translations to insert.

Add Somewhere the language toggle with all the available languages:

```
<div id="myLangToggle" class="dropdown">
  <button class="btn dropdown-toggle lang-toggle-current" type="button" data-bs-toggle="dropdown" aria-expanded="false">{{ config('translatable.default') }}</button>
  <div class="dropdown-menu">
    @foreach (config('translatable.languages') as $key => $value)
      <a class="dropdown-item lang-toggle {{ $key == config('translatable.default') ? 'active' : '' }}" href="#" data-lang="{{ $key }}">{{ $value }}</a>
    @endforeach
  </div>
</div>
```

And the inputs for all the available languages:

```
<label>Title</label>
<input class="form-control" name="title" type="text" value="{{ $page->title }}" data-lang="{{ config('translatable.default') }}"/>

@foreach (Arr::except(config('translatable.languages'), config('translatable.default')) as $key => $value)
  <input class="form-control d-none" name="translations[{{ $key }}][title]" type="text" value="{{ $page->getTranslation('title',$key) }}" data-lang="{{ $key }}"/>
@endforeach
```

# Events

You can use the **TranslationEvent** for dispatch events that happen to the addresses.
*You can pass a name, the parent model, the translation model (or null) and the number of translations affected*

```
TranslationEvent::dispatch('my-event', $model, $translation, 1);
```
