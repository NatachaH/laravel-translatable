<?php

namespace Nh\Translatable\Http\Middleware;

use Illuminate\Support\Arr;
use Closure;
use URL;
use App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // Get the available languages
        $availables = config('localization.languages');

        // Get the requested language
        $lang = $request->segment(1);

        // Check if the language is available, otherwise take the default
        $locale = Arr::has($availables, $lang) ? $lang : app()->getLocale();

        // Set the new language
        URL::defaults(['locale' => $locale]);
        App::setLocale($locale);

        // Forget the parameter (To avoid bug when access route with other parameter)
        $request->route()->forgetParameter('locale');

        // Go next
        return $next($request);
    }
}
