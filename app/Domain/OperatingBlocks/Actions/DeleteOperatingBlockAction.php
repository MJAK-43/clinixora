<?php

namespace App\Domain\OperatingBlocks\Actions;

use App\Models\OperatingBlock;

class DeleteOperatingBlockAction
{
    public function execute(OperatingBlock $block): void
    {
        $block->delete();
    }
}
