<?php

namespace App\Http\Requests\Geography\Concerns;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait RedirectsGeographyValidationFailures
{
    /**
     * @param  array<string, mixed>  $extraQuery
     */
    protected function redirectOnGeographyValidationFailure(Validator $validator, array $extraQuery = []): void
    {
        $params = array_merge(
            $this->query(),
            $this->only([
                'country',
                'city',
                'country_search',
                'city_search',
                'district_search',
                'countries_page',
                'cities_page',
                'districts_page',
            ]),
            $extraQuery
        );

        throw new HttpResponseException(
            redirect()
                ->route('geography.countries.index', array_filter($params, fn ($v) => $v !== null && $v !== ''))
                ->withInput()
                ->withErrors($validator)
        );
    }
}
