<?php

namespace App\Http\Requests\Geography;

use App\Http\Requests\Geography\Concerns\RedirectsGeographyValidationFailures;
use App\Models\Country;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCountryRequest extends FormRequest
{
    use RedirectsGeographyValidationFailures;

    public function authorize(): bool
    {
        $country = $this->route('country');

        return $country instanceof Country && ($this->user()?->can('update', $country) ?? false);
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
            'code' => ['required', 'string', 'size:2', 'alpha', Rule::unique('countries', 'code')->ignore($country->id)],
            'flag_code' => ['nullable', 'string', 'max:10'],
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
            'code.size' => 'Le code doit contenir 2 caractères.',
            'code.unique' => 'Ce code pays est déjà utilisé.',
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
            'edit_country' => $country->id,
            'country' => $country->id,
        ]);
    }
}
