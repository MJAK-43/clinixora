<?php

/**
 * Crée la base MySQL indiquée dans .env (sans charger Laravel).
 * Usage : php scripts/create_database.php
 */
$root = dirname(__DIR__);
$envFile = $root.'/.env';
if (! is_readable($envFile)) {
    fwrite(STDERR, "Fichier .env introuvable : {$envFile}\n");
    exit(1);
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$env = [];
foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) {
        continue;
    }
    if (! str_contains($line, '=')) {
        continue;
    }
    [$k, $v] = explode('=', $line, 2);
    $k = trim($k);
    $v = trim($v);
    if (
        (str_starts_with($v, '"') && str_ends_with($v, '"'))
        || (str_starts_with($v, "'") && str_ends_with($v, "'"))
    ) {
        $v = substr($v, 1, -1);
    }
    $env[$k] = $v;
}

$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = (int) ($env['DB_PORT'] ?? 3306);
$user = $env['DB_USERNAME'] ?? 'root';
$pass = $env['DB_PASSWORD'] ?? '';
$name = $env['DB_DATABASE'] ?? 'clinixora';

$m = new mysqli($host, $user, $pass, '', $port);
if ($m->connect_error) {
    fwrite(STDERR, "Connexion MySQL échouée : {$m->connect_error}\n");
    exit(1);
}

$sql = sprintf(
    'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
    str_replace('`', '``', $name)
);
if (! $m->query($sql)) {
    fwrite(STDERR, "Erreur : {$m->error}\n");
    exit(1);
}

echo "Base « {$name} » prête.\n";
$m->close();
exit(0);
