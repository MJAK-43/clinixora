<?php

namespace App\Console\Commands;

use App\Models\AgentAction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Throwable;

class ClinixoraVerifyCommand extends Command
{
    protected $signature = 'clinixora:verify {--fix-agent : Synchronise le catalogue agent (AgentActionSeeder)}';

    protected $description = 'Vérifie la cohérence du projet (catalogue agent, doublons, handlers).';

    public function handle(): int
    {
        $errors = 0;

        $errors += $this->verifyAgentCatalogStatic();
        $errors += $this->verifyAgentCatalogDatabase();
        $errors += $this->verifyHandlerClasses();

        if ($this->option('fix-agent') && $errors > 0) {
            $this->call('db:seed', ['--class' => 'Database\\Seeders\\AgentActionSeeder', '--force' => true]);
            $this->newLine();
            $this->info('Catalogue agent resynchronisé. Relancez clinixora:verify.');
        }

        if ($errors > 0 && ! $this->option('fix-agent')) {
            $this->newLine();
            $this->comment('Astuce : php artisan clinixora:verify --fix-agent');
        }

        return $errors === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function verifyAgentCatalogStatic(): int
    {
        $errors = 0;
        $configModules = array_keys(config('agent.modules', []));
        $seederPath = database_path('seeders/AgentActionSeeder.php');
        $contents = File::get($seederPath);

        preg_match_all("/'module'\s*=>\s*'([^']+)'/", $contents, $matches);
        $seederModules = array_values(array_unique($matches[1] ?? []));

        foreach ($configModules as $module) {
            if (! in_array($module, $seederModules, true)) {
                $this->error("Module « {$module} » présent dans config/agent.php mais absent de AgentActionSeeder.");
                $errors++;
            }
        }

        foreach ($seederModules as $module) {
            if (! array_key_exists($module, config('agent.modules', []))) {
                $this->error("Module « {$module} » seedé mais absent de config/agent.php.");
                $errors++;
            }
        }

        preg_match_all("/'action_key'\s*=>\s*[^,]+::ACTION_KEY/", $contents, $keys);
        preg_match_all("/ACTION_KEY\s*=\s*'([^']+)'/", $contents, $constants);
        $actionKeys = $constants[1] ?? [];
        $duplicates = array_diff_assoc($actionKeys, array_unique($actionKeys));

        if ($duplicates !== []) {
            $this->error('Doublons action_key dans AgentActionSeeder : '.implode(', ', array_unique($duplicates)));
            $errors++;
        }

        if ($errors === 0) {
            $this->info('Catalogue agent (statique) : OK ('.count($configModules).' module(s)).');
        }

        return $errors;
    }

    private function verifyAgentCatalogDatabase(): int
    {
        try {
            DB::connection()->getPdo();
        } catch (Throwable) {
            $this->warn('Base indisponible — vérification agent_actions ignorée.');

            return 0;
        }

        $errors = 0;

        foreach (array_keys(config('agent.modules', [])) as $module) {
            $count = AgentAction::query()->where('module', $module)->where('is_enabled', true)->count();

            if ($count === 0) {
                $this->error("Aucune action agent en base pour le module « {$module} ».");
                $errors++;
            }
        }

        if ($errors === 0) {
            $this->info('Catalogue agent (base de données) : OK.');
        }

        return $errors;
    }

    private function verifyHandlerClasses(): int
    {
        $errors = 0;
        $contents = str_replace("\r\n", "\n", File::get(database_path('seeders/AgentActionSeeder.php')));

        preg_match_all('/^use (App\\\\Domain\\\\Agent\\\\Handlers\\\\[^;]+);$/m', $contents, $useMatches);
        $classes = array_unique($useMatches[1] ?? []);

        foreach ($classes as $fqcn) {
            if (! class_exists($fqcn)) {
                $this->error("Handler introuvable : {$fqcn}");
                $errors++;
            }
        }

        if ($errors === 0) {
            $this->info('Handlers agent : OK ('.count($classes).' classe(s)).');
        }

        return $errors;
    }
}
