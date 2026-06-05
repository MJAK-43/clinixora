<?php

namespace App\Http\Requests\Geography;

use App\Http\Requests\Geography\Concerns\RedirectsGeographyValidationFailures;
use App\Models\Country;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCityRequest extends FormRequest
{
    use RedirectsGeographyValidationFailures;

    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\City::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Country $country */
        $country = $this->route('country');

        return [
            'name' => ['required', 'string', 'max:191'],
            'code' => [
                'required',
                'string',
                'max:20',
                'alpha_num',
                Rule::unique('cities', 'code')->where('country_id', $country->id),
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
            'code.unique' => 'Ce code existe déjà pour ce pays.',
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
        /** @var Country $country */
        $country = $this->route('country');
        $this->redirectOnGeographyValidationFailure($validator, [
            'create_city' => 1,
            'country' => $country->id,
        ]);
    }
}
