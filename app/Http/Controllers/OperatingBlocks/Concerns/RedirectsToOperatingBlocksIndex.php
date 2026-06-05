<?php

namespace App\Http\Controllers\OperatingBlocks\Concerns;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait RedirectsToOperatingBlocksIndex
{
    /**
     * @param  array<string, mixed>  $overrides
     */
    protected function operatingBlocksRedirect(Request $request, array $overrides = [], ?string $success = null, ?string $error = null): RedirectResponse
    {
        $params = array_merge(
            $this->operatingBlocksRedirectContext($request),
            $overrides
        );

        $redirect = redirect()->route('operating_blocks.index', array_filter(
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
    protected function operatingBlocksRedirectContext(Request $request): array
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
                'operating_blocks_page',
            ])
        );
    }
}
