<?php

namespace App\Http\Controllers\CareRooms\Concerns;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait RedirectsToCareRoomsIndex
{
    /**
     * @param  array<string, mixed>  $overrides
     */
    protected function careRoomsRedirect(Request $request, array $overrides = [], ?string $success = null, ?string $error = null): RedirectResponse
    {
        $params = array_merge(
            $this->careRoomsRedirectContext($request),
            $overrides
        );

        $redirect = redirect()->route('care_rooms.index', array_filter(
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
    protected function careRoomsRedirectContext(Request $request): array
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
                'service',
                'per_page',
                'care_rooms_page',
            ])
        );
    }
}
