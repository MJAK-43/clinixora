<?php

namespace App\Http\Requests\Geography;

use App\Http\Requests\Geography\Concerns\RedirectsGeographyValidationFailures;
use App\Models\District;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDistrictRequest extends FormRequest
{
    use RedirectsGeographyValidationFailures;

    public function authorize(): bool
    {
        $district = $this->route('district');

        return $district instanceof District && ($this->user()?->can('update', $district) ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var District $district */
        $district = $this->route('district');

        return [
            'name' => ['required', 'string', 'max:191'],
            'code' => [
                'required',
                'string',
                'max:20',
                'alpha_num',
                Rule::unique('districts', 'code')->where('city_id', $district->city_id)->ignore($district->id),
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
        /** @var District $district */
        $district = $this->route('district');
        $city = $district->city;
        $this->redirectOnGeographyValidationFailure($validator, [
            'edit_district' => $district->id,
            'country' => $city->country_id,
            'city' => $city->id,
        ]);
    }
}
