<?php

namespace App\Http\Requests\CareRooms\Concerns;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait RedirectsCareRoomValidationFailures
{
    protected function redirectOnCareRoomValidationFailure(Validator $validator, array $modalFlags = []): void
    {
        throw new HttpResponseException(
            redirect()
                ->route('care_rooms.index', array_merge(
                    request()->query(),
                    $modalFlags
                ))
                ->withErrors($validator)
                ->withInput()
        );
    }
}
