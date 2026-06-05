<?php

namespace App\Http\Requests\OperatingBlocks;

use App\Http\Requests\OperatingBlocks\Concerns\RedirectsOperatingBlockValidationFailures;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreOperatingBlockRequest extends FormRequest
{
    use RedirectsOperatingBlockValidationFailures;

    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\OperatingBlock::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'name' => ['required', 'string', 'max:191'],
            'code' => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9-]+$/', 'unique:operating_blocks,code'],
            'location' => ['required', 'string', 'max:100'],
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
            'code.unique' => 'Ce code bloc est déjà utilisé.',
            'code.regex' => 'Le code ne peut contenir que des lettres, chiffres et tirets.',
            'service_id.required' => 'Le service est obligatoire.',
            'location.required' => 'La localisation est obligatoire.',
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
        $this->redirectOnOperatingBlockValidationFailure($validator, ['create_operating_block' => 1]);
    }
}
