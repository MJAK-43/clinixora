<?php

namespace App\Http\Requests\Specialties;

use App\Http\Requests\Specialties\Concerns\RedirectsSpecialtyValidationFailures;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSpecialtyRequest extends FormRequest
{
    use RedirectsSpecialtyValidationFailures;

    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Specialty::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'code' => ['required', 'string', 'max:10', 'alpha_num', 'unique:specialties,code'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:32'],
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
            'code.unique' => 'Ce code spécialité est déjà utilisé.',
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
        $this->redirectOnSpecialtyValidationFailure($validator, ['create_specialty' => 1]);
    }
}
