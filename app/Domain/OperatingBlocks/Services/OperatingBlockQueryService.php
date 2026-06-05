<?php

namespace App\Domain\OperatingBlocks\Services;

use App\Models\OperatingBlock;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OperatingBlockQueryService
{
    public const DEFAULT_PER_PAGE = 10;

    public function paginateOperatingBlocks(
        ?string $search = null,
        ?string $status = null,
        ?int $serviceId = null,
        int $perPage = self::DEFAULT_PER_PAGE,
    ): LengthAwarePaginator {
        return OperatingBlock::query()
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
            ->paginate($perPage, ['*'], 'operating_blocks_page')
            ->withQueryString();
    }

    public function findByNameOrCode(string $needle): ?OperatingBlock
    {
        $needle = trim($needle);

        if ($needle === '') {
            return null;
        }

        return OperatingBlock::query()
            ->with('service')
            ->where(function ($q) use ($needle) {
                $q->where('code', strtoupper($needle))
                    ->orWhere('name', 'like', "%{$needle}%");
            })
            ->orderByRaw('CASE WHEN code = ? THEN 0 ELSE 1 END', [strtoupper($needle)])
            ->first();
    }
}
