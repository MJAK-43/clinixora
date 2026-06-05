<?php

namespace App\Http\Requests\OperatingBlocks;

use App\Http\Requests\OperatingBlocks\Concerns\RedirectsOperatingBlockValidationFailures;
use App\Models\OperatingBlock;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOperatingBlockRequest extends FormRequest
{
    use RedirectsOperatingBlockValidationFailures;

    public function authorize(): bool
    {
        /** @var OperatingBlock $block */
        $block = $this->route('operating_block');

        return $this->user()?->can('update', $block) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var OperatingBlock $block */
        $block = $this->route('operating_block');

        return [
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'name' => ['required', 'string', 'max:191'],
            'code' => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9-]+$/', Rule::unique('operating_blocks', 'code')->ignore($block->id)],
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
        /** @var OperatingBlock $block */
        $block = $this->route('operating_block');

        $this->redirectOnOperatingBlockValidationFailure($validator, ['edit_operating_block' => $block->id]);
    }
}
