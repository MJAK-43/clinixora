<?php

declare(strict_types=1);

$flag = dirname(__DIR__).'/session-modified.flag';

if (! is_file($flag)) {
    exit(0);
}

unlink($flag);

echo json_encode([
    'followup_message' => 'Des fichiers Clinixora ont été modifiés. Avant de clôturer : exécute `composer qa` (clinixora:verify + tests). Si un module agent manque dans l’assistant, lance aussi `composer sync-agent`. Rapporte le résultat.',
], JSON_UNESCAPED_UNICODE);

exit(0);
