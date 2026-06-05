<?php

namespace App\Http\Requests\CareRooms;

use App\Http\Requests\CareRooms\Concerns\RedirectsCareRoomValidationFailures;
use App\Models\CareRoom;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCareRoomRequest extends FormRequest
{
    use RedirectsCareRoomValidationFailures;

    public function authorize(): bool
    {
        /** @var CareRoom $room */
        $room = $this->route('care_room');

        return $this->user()?->can('update', $room) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var CareRoom $room */
        $room = $this->route('care_room');

        return [
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'name' => ['required', 'string', 'max:191'],
            'code' => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9-]+$/', Rule::unique('care_rooms', 'code')->ignore($room->id)],
            'location' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:32'],
            'is_active' => ['sometimes', 'boolean'],
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
        /** @var CareRoom $room */
        $room = $this->route('care_room');

        $this->redirectOnCareRoomValidationFailure($validator, ['edit_care_room' => $room->id]);
    }
}
