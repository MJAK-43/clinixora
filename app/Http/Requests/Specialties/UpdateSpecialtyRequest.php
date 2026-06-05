<?php

namespace App\Http\Requests\Specialties;

use App\Http\Requests\Specialties\Concerns\RedirectsSpecialtyValidationFailures;
use App\Models\Specialty;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSpecialtyRequest extends FormRequest
{
    use RedirectsSpecialtyValidationFailures;

    public function authorize(): bool
    {
        /** @var Specialty $specialty */
        $specialty = $this->route('specialty');

        return $this->user()?->can('update', $specialty) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Specialty $specialty */
        $specialty = $this->route('specialty');

        return [
            'name' => ['required', 'string', 'max:191'],
            'code' => ['required', 'string', 'max:10', 'alpha_num', Rule::unique('specialties', 'code')->ignore($specialty->id)],
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
        /** @var Specialty $specialty */
        $specialty = $this->route('specialty');

        $this->redirectOnSpecialtyValidationFailure($validator, ['edit_specialty' => $specialty->id]);
    }
}
