<?php

namespace App\Http\Controllers\Agent;

use App\Domain\Agent\Services\AgentCatalogService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgentCatalogController extends Controller
{
    public function __invoke(Request $request, AgentCatalogService $catalog): JsonResponse
    {
        return response()->json([
            'modules' => $catalog->catalogForUser($request->user()),
        ]);
    }
}
