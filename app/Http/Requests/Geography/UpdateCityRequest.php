<?php

namespace App\Http\Requests\Geography;

use App\Http\Requests\Geography\Concerns\RedirectsGeographyValidationFailures;
use App\Models\City;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCityRequest extends FormRequest
{
    use RedirectsGeographyValidationFailures;

    public function authorize(): bool
    {
        $city = $this->route('city');

        return $city instanceof City && ($this->user()?->can('update', $city) ?? false);
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
                Rule::unique('cities', 'code')->where('country_id', $city->country_id)->ignore($city->id),
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
        /** @var City $city */
        $city = $this->route('city');
        $this->redirectOnGeographyValidationFailure($validator, [
            'edit_city' => $city->id,
            'country' => $city->country_id,
            'city' => $city->id,
        ]);
    }
}
