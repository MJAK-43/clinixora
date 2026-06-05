<?php

namespace App\Http\Controllers\CareRooms;

use App\Domain\CareRooms\Services\CareRoomQueryService;
use App\Http\Controllers\Controller;
use App\Models\CareRoom;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CareRoomPageController extends Controller
{
    public function __invoke(Request $request, CareRoomQueryService $queries): View|Response
    {
        $this->authorize('viewAny', CareRoom::class);

        $isPartial = $request->ajax() && $request->hasHeader('X-CareRoom-Panel');

        $pageData = $this->buildPageData($request, $queries);

        if ($isPartial) {
            return $this->partialResponse($request->header('X-CareRoom-Panel'), $pageData);
        }

        return view('care_rooms.index', [
            'customizeWidgets' => $this->layoutWidgets(),
            'assistantMessages' => [],
            ...$pageData,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPageData(Request $request, CareRoomQueryService $queries): array
    {
        $search = $request->string('search')->trim()->toString() ?: null;
        $status = $request->string('status')->trim()->toString() ?: null;
        $serviceId = $request->filled('service') ? $request->integer('service') : null;

        if (! in_array($status, ['active', 'inactive'], true)) {
            $status = null;
        }

        $perPage = (int) $request->input('per_page', CareRoomQueryService::DEFAULT_PER_PAGE);
        if (! in_array($perPage, [10, 25, 50], true)) {
            $perPage = CareRoomQueryService::DEFAULT_PER_PAGE;
        }

        $rooms = $queries->paginateCareRooms($search, $status, $serviceId, $perPage);

        return [
            'rooms' => $rooms,
            'search' => $search ?? '',
            'status' => $status ?? '',
            'serviceFilter' => $serviceId ?? '',
            'services' => Service::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'perPage' => $perPage,
            'editingRoom' => $request->filled('edit_care_room')
                ? CareRoom::query()->with('service')->find($request->integer('edit_care_room'))
                : null,
            'openCreateRoom' => $request->boolean('create_care_room'),
            'iconOptions' => $this->iconOptions(),
        ];
    }

    /**
     * @param  array<string, mixed>  $viewData
     */
    private function partialResponse(string $panel, array $viewData): Response
    {
        $view = match ($panel) {
            'table' => 'care_rooms.partials.table-body',
            default => abort(404),
        };

        return response(view($view, $viewData)->render());
    }

    /**
     * @return list<array{value: string, label: string}>
     */
    private function iconOptions(): array
    {
        return [
            ['value' => 'bed', 'label' => 'Lit / soins'],
            ['value' => 'stethoscope', 'label' => 'Stéthoscope'],
            ['value' => 'syringe', 'label' => 'Seringue'],
            ['value' => 'baby', 'label' => 'Pédiatrie / maternité'],
            ['value' => 'heart', 'label' => 'Cœur'],
            ['value' => 'running', 'label' => 'Kinésithérapie'],
            ['value' => 'kidney', 'label' => 'Dialyse'],
            ['value' => 'ribbon', 'label' => 'Oncologie'],
        ];
    }

    /**
     * @return list<array{id: string, label: string, default: bool}>
     */
    private function layoutWidgets(): array
    {
        return [
            ['id' => 'assistant_panel', 'label' => 'Assistant Clinixora', 'default' => true],
        ];
    }
}
