<?php

namespace App\Http\Requests\ReceptionRooms;

use App\Http\Requests\ReceptionRooms\Concerns\RedirectsReceptionRoomValidationFailures;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreReceptionRoomRequest extends FormRequest
{
    use RedirectsReceptionRoomValidationFailures;

    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\ReceptionRoom::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'code' => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9-]+$/', 'unique:reception_rooms,code'],
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
            'code.unique' => 'Ce code salle est déjà utilisé.',
            'code.regex' => 'Le code ne peut contenir que des lettres, chiffres et tirets.',
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
        $this->redirectOnReceptionRoomValidationFailure($validator, ['create_reception_room' => 1]);
    }
}
