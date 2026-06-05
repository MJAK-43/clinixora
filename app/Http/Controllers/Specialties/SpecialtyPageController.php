<?php

namespace App\Http\Controllers\Specialties;

use App\Domain\Specialties\Services\SpecialtyQueryService;
use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SpecialtyPageController extends Controller
{
    public function __invoke(Request $request, SpecialtyQueryService $queries): View|Response
    {
        $this->authorize('viewAny', Specialty::class);

        $isPartial = $request->ajax() && $request->hasHeader('X-Specialty-Panel');

        $pageData = $this->buildPageData($request, $queries);

        if ($isPartial) {
            return $this->partialResponse($request->header('X-Specialty-Panel'), $pageData);
        }

        return view('specialties.index', [
            'customizeWidgets' => $this->layoutWidgets(),
            'assistantMessages' => [],
            ...$pageData,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPageData(Request $request, SpecialtyQueryService $queries): array
    {
        $search = $request->string('search')->trim()->toString() ?: null;
        $status = $request->string('status')->trim()->toString() ?: null;

        if (! in_array($status, ['active', 'inactive'], true)) {
            $status = null;
        }

        $perPage = (int) $request->input('per_page', SpecialtyQueryService::DEFAULT_PER_PAGE);
        if (! in_array($perPage, [10, 25, 50], true)) {
            $perPage = SpecialtyQueryService::DEFAULT_PER_PAGE;
        }

        $specialties = $queries->paginateSpecialties($search, $status, $perPage);

        return [
            'specialties' => $specialties,
            'search' => $search ?? '',
            'status' => $status ?? '',
            'perPage' => $perPage,
            'editingSpecialty' => $request->filled('edit_specialty')
                ? Specialty::query()->find($request->integer('edit_specialty'))
                : null,
            'openCreateSpecialty' => $request->boolean('create_specialty'),
            'iconOptions' => $this->iconOptions(),
        ];
    }

    /**
     * @param  array<string, mixed>  $viewData
     */
    private function partialResponse(string $panel, array $viewData): Response
    {
        $view = match ($panel) {
            'table' => 'specialties.partials.table-body',
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
            ['value' => 'heart', 'label' => 'Cœur'],
            ['value' => 'brain', 'label' => 'Cerveau'],
            ['value' => 'bone', 'label' => 'Os'],
            ['value' => 'stethoscope', 'label' => 'Stéthoscope'],
            ['value' => 'eye', 'label' => 'Œil'],
            ['value' => 'lungs', 'label' => 'Poumons'],
            ['value' => 'syringe', 'label' => 'Seringue'],
            ['value' => 'scalpel', 'label' => 'Scalpel'],
            ['value' => 'baby', 'label' => 'Pédiatrie'],
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
