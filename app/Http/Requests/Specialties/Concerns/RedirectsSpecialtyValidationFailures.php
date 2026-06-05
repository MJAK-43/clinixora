<?php

namespace App\Http\Requests\Specialties\Concerns;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait RedirectsSpecialtyValidationFailures
{
    protected function redirectOnSpecialtyValidationFailure(Validator $validator, array $modalFlags = []): void
    {
        throw new HttpResponseException(
            redirect()
                ->route('specialties.index', array_merge(
                    request()->query(),
                    $modalFlags
                ))
                ->withErrors($validator)
                ->withInput()
        );
    }
}
