<?php

namespace App\Http\Controllers\OperatingBlocks;

use App\Domain\OperatingBlocks\Services\OperatingBlockQueryService;
use App\Http\Controllers\Controller;
use App\Models\OperatingBlock;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class OperatingBlockPageController extends Controller
{
    public function __invoke(Request $request, OperatingBlockQueryService $queries): View|Response
    {
        $this->authorize('viewAny', OperatingBlock::class);

        $isPartial = $request->ajax() && $request->hasHeader('X-OperatingBlock-Panel');

        $pageData = $this->buildPageData($request, $queries);

        if ($isPartial) {
            return $this->partialResponse($request->header('X-OperatingBlock-Panel'), $pageData);
        }

        return view('operating_blocks.index', [
            'customizeWidgets' => $this->layoutWidgets(),
            'assistantMessages' => [],
            ...$pageData,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPageData(Request $request, OperatingBlockQueryService $queries): array
    {
        $search = $request->string('search')->trim()->toString() ?: null;
        $status = $request->string('status')->trim()->toString() ?: null;
        $serviceId = $request->filled('service') ? $request->integer('service') : null;

        if (! in_array($status, ['active', 'inactive'], true)) {
            $status = null;
        }

        $perPage = (int) $request->input('per_page', OperatingBlockQueryService::DEFAULT_PER_PAGE);
        if (! in_array($perPage, [10, 25, 50], true)) {
            $perPage = OperatingBlockQueryService::DEFAULT_PER_PAGE;
        }

        $blocks = $queries->paginateOperatingBlocks($search, $status, $serviceId, $perPage);

        return [
            'blocks' => $blocks,
            'search' => $search ?? '',
            'status' => $status ?? '',
            'serviceFilter' => $serviceId ?? '',
            'services' => Service::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'perPage' => $perPage,
            'editingBlock' => $request->filled('edit_operating_block')
                ? OperatingBlock::query()->with('service')->find($request->integer('edit_operating_block'))
                : null,
            'openCreateBlock' => $request->boolean('create_operating_block'),
            'iconOptions' => $this->iconOptions(),
        ];
    }

    /**
     * @param  array<string, mixed>  $viewData
     */
    private function partialResponse(string $panel, array $viewData): Response
    {
        $view = match ($panel) {
            'table' => 'operating_blocks.partials.table-body',
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
            ['value' => 'scalpel', 'label' => 'Scalpel'],
            ['value' => 'heart', 'label' => 'Cœur'],
            ['value' => 'bone', 'label' => 'Os'],
            ['value' => 'eye', 'label' => 'Œil'],
            ['value' => 'baby', 'label' => 'Pédiatrie / maternité'],
            ['value' => 'syringe', 'label' => 'Seringue'],
            ['value' => 'stomach', 'label' => 'Endoscopie'],
            ['value' => 'stethoscope', 'label' => 'Stéthoscope'],
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
