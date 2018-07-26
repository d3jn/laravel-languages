<?php

namespace D3jn\LaravelLanguages\Http\Middleware;

use Closure;
use D3jn\LaravelLanguages\Facades\Languages;

class HandleLanguageRedirects
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
        // Omitting requests different from GET.
        if (!$request->isMethod('get')) {
            return $next($request);
        }

        $language = $request->segment(1);
        $segments = $request->segments();

        // Handling redirects based on locales configuration and current URI.
        if (!Languages::isAvailable($language)) {
            if (!Languages::shouldHideDefault() && !$request->is('/')) {
                array_unshift($segments, Languages::getDefault());

                return redirect(
                    implode($segments, '/'),
                    301
                );
            }
        } elseif ((Languages::shouldHideDefault() || count($segments) == 1)
            && $language == Languages::getDefault()
        ) {
            array_shift($segments);

            return redirect(
                implode($segments, '/'),
                301
            );
        }

        return $next($request);
    }
}
