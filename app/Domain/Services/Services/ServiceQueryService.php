<?php

namespace App\Domain\Services\Services;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ServiceQueryService
{
    public const DEFAULT_PER_PAGE = 10;

    /**
     * @param  'all'|'active'|'inactive'|null  $status
     */
    public function paginateServices(
        ?string $search = null,
        ?string $status = null,
        int $perPage = self::DEFAULT_PER_PAGE,
    ): LengthAwarePaginator {
        return Service::query()
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }))
            ->when($status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'services_page')
            ->withQueryString();
    }

    public function findByNameOrCode(string $needle): ?Service
    {
        $needle = trim($needle);

        if ($needle === '') {
            return null;
        }

        return Service::query()
            ->where(function ($q) use ($needle) {
                $q->where('code', strtoupper($needle))
                    ->orWhere('name', 'like', "%{$needle}%");
            })
            ->orderByRaw('CASE WHEN code = ? THEN 0 ELSE 1 END', [strtoupper($needle)])
            ->first();
    }
}
