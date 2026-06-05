<?php

namespace App\Http\Requests\ReceptionRooms\Concerns;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait RedirectsReceptionRoomValidationFailures
{
    protected function redirectOnReceptionRoomValidationFailure(Validator $validator, array $modalFlags = []): void
    {
        throw new HttpResponseException(
            redirect()
                ->route('reception_rooms.index', array_merge(
                    request()->query(),
                    $modalFlags
                ))
                ->withErrors($validator)
                ->withInput()
        );
    }
}
