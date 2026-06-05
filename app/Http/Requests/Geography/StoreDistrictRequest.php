<?php

namespace App\Http\Requests\Geography;

use App\Http\Requests\Geography\Concerns\RedirectsGeographyValidationFailures;
use App\Models\City;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDistrictRequest extends FormRequest
{
    use RedirectsGeographyValidationFailures;

    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\District::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var City $city */
        $city = $this->route('city');

        return [
            'name' => ['required', 'string', 'max:191'],
            'code' => [
                'required',
                'string',
                'max:20',
                'alpha_num',
                Rule::unique('districts', 'code')->where('city_id', $city->id),
            ],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'code.required' => 'Le code est obligatoire.',
            'code.unique' => 'Ce code existe déjà pour cette ville.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('code')) {
            $this->merge(['code' => strtoupper((string) $this->input('code'))]);
        }
    }

    protected function failedValidation(Validator $validator): void
    {
        /** @var City $city */
        $city = $this->route('city');
        $this->redirectOnGeographyValidationFailure($validator, [
            'create_district' => 1,
            'country' => $city->country_id,
            'city' => $city->id,
        ]);
    }
}
