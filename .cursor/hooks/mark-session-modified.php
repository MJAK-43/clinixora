<?php

declare(strict_types=1);

$input = json_decode(stream_get_contents(STDIN) ?: '{}', true) ?? [];
$path = (string) ($input['file_path'] ?? $input['path'] ?? '');

$needles = [
    'app/Domain/',
    'app/Http/',
    'app/Models/',
    'app/Policies/',
    'database/migrations/',
    'database/seeders/',
    'routes/web.php',
    'config/agent.php',
    'resources/views/',
    'resources/js/',
    'tests/Feature/',
];

foreach ($needles as $needle) {
    if ($path !== '' && str_contains(str_replace('\\', '/', $path), $needle)) {
        $flag = dirname(__DIR__).'/session-modified.flag';
        file_put_contents($flag, date('c'));

        break;
    }
}

exit(0);
