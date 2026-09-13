<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('lang')) {
            $lang = strtolower($request->get('lang'));
            if (in_array($lang, ['sw', 'en'])) {
                Session::put('locale', $lang);
            }
        }

        $locale = Session::get('locale', config('app.locale', 'sw'));
        App::setLocale($locale);

        return $next($request);
    }
}
