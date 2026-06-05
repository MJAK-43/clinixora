<?php

namespace App\Domain\ReceptionRooms\Services;

use App\Models\ReceptionRoom;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReceptionRoomQueryService
{
    public const DEFAULT_PER_PAGE = 10;

    public function paginateReceptionRooms(
        ?string $search = null,
        ?string $status = null,
        int $perPage = self::DEFAULT_PER_PAGE,
    ): LengthAwarePaginator {
        return ReceptionRoom::query()
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            }))
            ->when($status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'reception_rooms_page')
            ->withQueryString();
    }

    public function findByNameOrCode(string $needle): ?ReceptionRoom
    {
        $needle = trim($needle);

        if ($needle === '') {
            return null;
        }

        return ReceptionRoom::query()
            ->where(function ($q) use ($needle) {
                $q->where('code', strtoupper($needle))
                    ->orWhere('name', 'like', "%{$needle}%");
            })
            ->orderByRaw('CASE WHEN code = ? THEN 0 ELSE 1 END', [strtoupper($needle)])
            ->first();
    }
}
