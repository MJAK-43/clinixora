<?php

namespace App\Http\Requests\OperatingBlocks\Concerns;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait RedirectsOperatingBlockValidationFailures
{
    protected function redirectOnOperatingBlockValidationFailure(Validator $validator, array $modalFlags = []): void
    {
        throw new HttpResponseException(
            redirect()
                ->route('operating_blocks.index', array_merge(
                    request()->query(),
                    $modalFlags
                ))
                ->withErrors($validator)
                ->withInput()
        );
    }
}
