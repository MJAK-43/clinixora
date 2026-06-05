<?php

namespace App\Http\Controllers\Specialties\Concerns;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait RedirectsToSpecialtiesIndex
{
    /**
     * @param  array<string, mixed>  $overrides
     */
    protected function specialtiesRedirect(Request $request, array $overrides = [], ?string $success = null, ?string $error = null): RedirectResponse
    {
        $params = array_merge(
            $this->specialtiesRedirectContext($request),
            $overrides
        );

        $redirect = redirect()->route('specialties.index', array_filter(
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
    protected function specialtiesRedirectContext(Request $request): array
    {
        $fromForm = $request->input('_redirect', []);

        if (! is_array($fromForm)) {
            $fromForm = [];
        }

        return array_merge(
            $request->query(),
            $fromForm,
            $request->only([
                'search',
                'status',
                'per_page',
                'specialties_page',
            ])
        );
    }
}
