<?php

namespace App\Http\Controllers\Geography;

use App\Domain\Geography\Services\GeographyQueryService;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CountryPageController extends Controller
{
    public function __invoke(Request $request, GeographyQueryService $queries): View|RedirectResponse|Response
    {
        $this->authorize('viewAny', Country::class);

        $isPartial = $request->ajax() && $request->hasHeader('X-Geography-Panel');

        $pageData = $this->buildPageData($request, $queries, ! $isPartial);

        if ($pageData instanceof RedirectResponse) {
            return $pageData;
        }

        if ($isPartial) {
            return $this->partialResponse($request->header('X-Geography-Panel'), $pageData);
        }

        return view('geography.pays-index', [
            'customizeWidgets' => $this->layoutWidgets(),
            'assistantMessages' => [],
            ...$pageData,
        ]);
    }

    /**
     * @return array<string, mixed>|RedirectResponse
     */
    private function buildPageData(Request $request, GeographyQueryService $queries, bool $allowRedirects): array|RedirectResponse
    {
        $countrySearch = $request->string('country_search')->trim()->toString() ?: null;
        $citySearch = $request->string('city_search')->trim()->toString() ?: null;
        $districtSearch = $request->string('district_search')->trim()->toString() ?: null;

        $countries = $queries->paginateCountries($countrySearch);

        $selectedCountry = null;
        if ($request->filled('country')) {
            $selectedCountry = Country::query()->find($request->integer('country'));
        }
        if ($selectedCountry === null && $countries->isNotEmpty()) {
            $selectedCountry = $countries->first();
        }

        $selectedCity = null;
        if ($selectedCountry && $request->filled('city')) {
            $selectedCity = City::query()
                ->where('country_id', $selectedCountry->id)
                ->find($request->integer('city'));
        }

        if ($allowRedirects && $selectedCountry && $selectedCity) {
            $expectedCitiesPage = $queries->pageForCity($selectedCountry, $selectedCity, $citySearch);
            if ($request->integer('cities_page', 1) !== $expectedCitiesPage) {
                return redirect()->route('geography.countries.index', array_merge(
                    $request->query(),
                    ['cities_page' => $expectedCitiesPage]
                ));
            }
        }

        $cities = $selectedCountry
            ? $queries->paginateCities($selectedCountry, $citySearch)
            : $queries->emptyPaginator('cities_page');

        if ($selectedCountry && $selectedCity === null && $cities->isNotEmpty()) {
            $selectedCity = $cities->first();
        }

        if ($allowRedirects && $selectedCity && $request->filled('district')) {
            $selectedDistrict = \App\Models\District::query()
                ->where('city_id', $selectedCity->id)
                ->find($request->integer('district'));

            if ($selectedDistrict) {
                $expectedDistrictsPage = $queries->pageForDistrict($selectedCity, $selectedDistrict, $districtSearch);
                if ($request->integer('districts_page', 1) !== $expectedDistrictsPage) {
                    return redirect()->route('geography.countries.index', array_merge(
                        $request->query(),
                        ['districts_page' => $expectedDistrictsPage]
                    ));
                }
            }
        }

        $districts = $selectedCity
            ? $queries->paginateDistricts($selectedCity, $districtSearch)
            : $queries->emptyPaginator('districts_page', GeographyQueryService::DISTRICTS_PER_PAGE);

        return [
            'countries' => $countries,
            'selectedCountry' => $selectedCountry,
            'cities' => $cities,
            'selectedCity' => $selectedCity,
            'districts' => $districts,
            'countrySearch' => $countrySearch ?? '',
            'citySearch' => $citySearch ?? '',
            'districtSearch' => $districtSearch ?? '',
            'editingCountry' => $request->filled('edit_country')
                ? Country::query()->find($request->integer('edit_country'))
                : null,
            'editingCity' => $request->filled('edit_city')
                ? City::query()->find($request->integer('edit_city'))
                : null,
            'editingDistrict' => $request->filled('edit_district')
                ? \App\Models\District::query()->find($request->integer('edit_district'))
                : null,
            'openCreateCountry' => $request->boolean('create_country'),
            'openCreateCity' => $request->boolean('create_city'),
            'openCreateDistrict' => $request->boolean('create_district'),
        ];
    }

    /**
     * @param  array<string, mixed>  $viewData
     */
    private function partialResponse(string $panel, array $viewData): Response
    {
        $view = match ($panel) {
            'countries' => 'geography.partials.countries-panel-body',
            'cities' => 'geography.partials.cities-panel-body',
            'districts' => 'geography.partials.districts-panel-body',
            default => abort(404),
        };

        return response(view($view, $viewData)->render());
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
