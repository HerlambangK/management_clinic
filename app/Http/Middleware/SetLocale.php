<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported locales.
     *
     * @var array<string>
     */
    protected array $supportedLocales = ['id', 'en'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if locale is passed via query string (for switching)
        if ($request->has('lang') && in_array($request->get('lang'), $this->supportedLocales)) {
            $locale = $request->get('lang');
            Session::put('locale', $locale);

            // Redirect to remove query string (clean URL)
            if ($request->isMethod('GET')) {
                return redirect()->to($request->url());
            }
        }

        // Get locale from session, or use default
        $locale = Session::get('locale', config('app.locale'));

        // Validate locale
        if (! in_array($locale, $this->supportedLocales)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
