<?php

namespace App\Http\Requests\Services\Concerns;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait RedirectsServiceValidationFailures
{
    protected function redirectOnServiceValidationFailure(Validator $validator, array $modalFlags = []): void
    {
        throw new HttpResponseException(
            redirect()
                ->route('services.index', array_merge(
                    request()->query(),
                    $modalFlags
                ))
                ->withErrors($validator)
                ->withInput()
        );
    }
}
