<?php

namespace App\Domain\CareRooms\Services;

use App\Models\CareRoom;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CareRoomQueryService
{
    public const DEFAULT_PER_PAGE = 10;

    public function paginateCareRooms(
        ?string $search = null,
        ?string $status = null,
        ?int $serviceId = null,
        int $perPage = self::DEFAULT_PER_PAGE,
    ): LengthAwarePaginator {
        return CareRoom::query()
            ->with('service')
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('service', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            }))
            ->when($status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($serviceId, fn ($q) => $q->where('service_id', $serviceId))
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'care_rooms_page')
            ->withQueryString();
    }

    public function findByNameOrCode(string $needle): ?CareRoom
    {
        $needle = trim($needle);

        if ($needle === '') {
            return null;
        }

        return CareRoom::query()
            ->with('service')
            ->where(function ($q) use ($needle) {
                $q->where('code', strtoupper($needle))
                    ->orWhere('name', 'like', "%{$needle}%");
            })
            ->orderByRaw('CASE WHEN code = ? THEN 0 ELSE 1 END', [strtoupper($needle)])
            ->first();
    }
}
