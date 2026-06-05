<?php

namespace App\Http\Controllers\OperatingBlocks;

use App\Domain\OperatingBlocks\Actions\CreateOperatingBlockAction;
use App\Domain\OperatingBlocks\Actions\DeleteOperatingBlockAction;
use App\Domain\OperatingBlocks\Actions\UpdateOperatingBlockAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OperatingBlocks\Concerns\RedirectsToOperatingBlocksIndex;
use App\Http\Requests\OperatingBlocks\StoreOperatingBlockRequest;
use App\Http\Requests\OperatingBlocks\UpdateOperatingBlockRequest;
use App\Models\OperatingBlock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OperatingBlockController extends Controller
{
    use RedirectsToOperatingBlocksIndex;

    public function store(StoreOperatingBlockRequest $request, CreateOperatingBlockAction $action): RedirectResponse
    {
        try {
            $block = $action->execute($request->validated());
        } catch (\Throwable) {
            return $this->operatingBlocksRedirect($request, error: 'Impossible de créer le bloc opératoire. Vérifiez le code (déjà utilisé).');
        }

        return $this->operatingBlocksRedirect(
            $request,
            success: "Le bloc {$block->name} a été créé."
        );
    }

    public function update(UpdateOperatingBlockRequest $request, OperatingBlock $operatingBlock, UpdateOperatingBlockAction $action): RedirectResponse
    {
        try {
            $block = $action->execute($operatingBlock, $request->validated());
        } catch (\Throwable) {
            return $this->operatingBlocksRedirect(
                $request,
                ['edit_operating_block' => $operatingBlock->id],
                error: 'Impossible de modifier le bloc opératoire.'
            );
        }

        return $this->operatingBlocksRedirect(
            $request,
            success: "Le bloc {$block->name} a été mis à jour."
        );
    }

    public function destroy(Request $request, OperatingBlock $operatingBlock, DeleteOperatingBlockAction $action): RedirectResponse
    {
        $this->authorize('delete', $operatingBlock);

        $name = $operatingBlock->name;

        try {
            $action->execute($operatingBlock);
        } catch (\Throwable) {
            return $this->operatingBlocksRedirect($request, error: 'Impossible de supprimer le bloc opératoire.');
        }

        return $this->operatingBlocksRedirect(
            $request,
            success: "Le bloc {$name} a été supprimé."
        );
    }
}
