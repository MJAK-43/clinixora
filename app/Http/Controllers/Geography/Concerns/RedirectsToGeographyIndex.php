<?php

namespace App\Http\Controllers\Geography\Concerns;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait RedirectsToGeographyIndex
{
    /**
     * @param  array<string, mixed>  $overrides
     */
    protected function geographyRedirect(Request $request, array $overrides = [], ?string $success = null, ?string $error = null): RedirectResponse
    {
        $params = array_merge(
            $this->geographyRedirectContext($request),
            $overrides
        );

        $redirect = redirect()->route('geography.countries.index', array_filter(
            $params,
            fn ($v) => $v !== null && $v !== ''
        ));

        if ($success !== null) {
            $redirect->with('success', $success);
        }

        if ($error !== null) {
            $redirect->with('error', $error);
        }

        return $redirect;
    }

    /**
     * @return array<string, mixed>
     */
    protected function geographyRedirectContext(Request $request): array
    {
        $fromForm = $request->input('_redirect', []);

        if (! is_array($fromForm)) {
            $fromForm = [];
        }

        return array_merge(
            $request->query(),
            $fromForm,
            $request->only([
                'country',
                'city',
                'country_search',
                'city_search',
                'district_search',
                'countries_page',
                'cities_page',
                'districts_page',
            ])
        );
    }
}
