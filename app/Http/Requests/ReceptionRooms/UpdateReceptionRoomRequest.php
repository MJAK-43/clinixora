<?php

namespace App\Http\Requests\ReceptionRooms;

use App\Http\Requests\ReceptionRooms\Concerns\RedirectsReceptionRoomValidationFailures;
use App\Models\ReceptionRoom;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReceptionRoomRequest extends FormRequest
{
    use RedirectsReceptionRoomValidationFailures;

    public function authorize(): bool
    {
        /** @var ReceptionRoom $room */
        $room = $this->route('reception_room');

        return $this->user()?->can('update', $room) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var ReceptionRoom $room */
        $room = $this->route('reception_room');

        return [
            'name' => ['required', 'string', 'max:191'],
            'code' => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9-]+$/', Rule::unique('reception_rooms', 'code')->ignore($room->id)],
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
        /** @var ReceptionRoom $room */
        $room = $this->route('reception_room');

        $this->redirectOnReceptionRoomValidationFailure($validator, ['edit_reception_room' => $room->id]);
    }
}
