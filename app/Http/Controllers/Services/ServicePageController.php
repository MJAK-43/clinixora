<?php

namespace App\Http\Controllers\Services;

use App\Domain\Services\Services\ServiceQueryService;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ServicePageController extends Controller
{
    public function __invoke(Request $request, ServiceQueryService $queries): View|Response
    {
        $this->authorize('viewAny', Service::class);

        $isPartial = $request->ajax() && $request->hasHeader('X-Service-Panel');

        $pageData = $this->buildPageData($request, $queries);

        if ($isPartial) {
            return $this->partialResponse($request->header('X-Service-Panel'), $pageData);
        }

        return view('services.index', [
            'customizeWidgets' => $this->layoutWidgets(),
            'assistantMessages' => [],
            ...$pageData,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPageData(Request $request, ServiceQueryService $queries): array
    {
        $search = $request->string('search')->trim()->toString() ?: null;
        $status = $request->string('status')->trim()->toString() ?: null;

        if (! in_array($status, ['active', 'inactive'], true)) {
            $status = null;
        }

        $perPage = (int) $request->input('per_page', ServiceQueryService::DEFAULT_PER_PAGE);
        if (! in_array($perPage, [10, 25, 50], true)) {
            $perPage = ServiceQueryService::DEFAULT_PER_PAGE;
        }

        $services = $queries->paginateServices($search, $status, $perPage);

        return [
            'services' => $services,
            'search' => $search ?? '',
            'status' => $status ?? '',
            'perPage' => $perPage,
            'editingService' => $request->filled('edit_service')
                ? Service::query()->find($request->integer('edit_service'))
                : null,
            'openCreateService' => $request->boolean('create_service'),
            'iconOptions' => $this->iconOptions(),
        ];
    }

    /**
     * @param  array<string, mixed>  $viewData
     */
    private function partialResponse(string $panel, array $viewData): Response
    {
        $view = match ($panel) {
            'table' => 'services.partials.table-body',
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
            ['value' => 'briefcase', 'label' => 'Porte-documents'],
            ['value' => 'stethoscope', 'label' => 'Stéthoscope'],
            ['value' => 'heart', 'label' => 'Cœur'],
            ['value' => 'bed', 'label' => 'Lit / hospitalisation'],
            ['value' => 'scan', 'label' => 'Imagerie'],
            ['value' => 'syringe', 'label' => 'Seringue'],
            ['value' => 'scalpel', 'label' => 'Scalpel'],
            ['value' => 'baby', 'label' => 'Pédiatrie'],
            ['value' => 'blood', 'label' => 'Laboratoire'],
            ['value' => 'running', 'label' => 'Mobilité'],
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
