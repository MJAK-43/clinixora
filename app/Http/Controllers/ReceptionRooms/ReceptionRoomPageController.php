<?php

namespace App\Http\Controllers\ReceptionRooms;

use App\Domain\ReceptionRooms\Services\ReceptionRoomQueryService;
use App\Http\Controllers\Controller;
use App\Models\ReceptionRoom;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReceptionRoomPageController extends Controller
{
    public function __invoke(Request $request, ReceptionRoomQueryService $queries): View|Response
    {
        $this->authorize('viewAny', ReceptionRoom::class);

        $isPartial = $request->ajax() && $request->hasHeader('X-ReceptionRoom-Panel');

        $pageData = $this->buildPageData($request, $queries);

        if ($isPartial) {
            return $this->partialResponse($request->header('X-ReceptionRoom-Panel'), $pageData);
        }

        return view('reception_rooms.index', [
            'customizeWidgets' => $this->layoutWidgets(),
            'assistantMessages' => [],
            ...$pageData,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPageData(Request $request, ReceptionRoomQueryService $queries): array
    {
        $search = $request->string('search')->trim()->toString() ?: null;
        $status = $request->string('status')->trim()->toString() ?: null;

        if (! in_array($status, ['active', 'inactive'], true)) {
            $status = null;
        }

        $perPage = (int) $request->input('per_page', ReceptionRoomQueryService::DEFAULT_PER_PAGE);
        if (! in_array($perPage, [10, 25, 50], true)) {
            $perPage = ReceptionRoomQueryService::DEFAULT_PER_PAGE;
        }

        $rooms = $queries->paginateReceptionRooms($search, $status, $perPage);

        return [
            'rooms' => $rooms,
            'search' => $search ?? '',
            'status' => $status ?? '',
            'perPage' => $perPage,
            'editingRoom' => $request->filled('edit_reception_room')
                ? ReceptionRoom::query()->find($request->integer('edit_reception_room'))
                : null,
            'openCreateRoom' => $request->boolean('create_reception_room'),
            'iconOptions' => $this->iconOptions(),
        ];
    }

    /**
     * @param  array<string, mixed>  $viewData
     */
    private function partialResponse(string $panel, array $viewData): Response
    {
        $view = match ($panel) {
            'table' => 'reception_rooms.partials.table-body',
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
            ['value' => 'briefcase', 'label' => 'Accueil / bureau'],
            ['value' => 'stethoscope', 'label' => 'Consultations'],
            ['value' => 'syringe', 'label' => 'Urgences'],
            ['value' => 'baby', 'label' => 'Pédiatrie / maternité'],
            ['value' => 'users', 'label' => 'Public'],
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
