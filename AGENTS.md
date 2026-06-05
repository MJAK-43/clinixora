# Règles pour les agents IA (Cursor / Claude)

## Projet

- **Nom** : Clinixora — refonte application de gestion clinique (ex. Winclinic).
- **Stack** : Laravel 12, PHP 8.2+, MySQL (WAMP local). Front prévu : Blade + Tailwind + Alpine ; API JSON pour mobile / agent / n8n.

## Architecture obligatoire

- Logique métier dans **`app/Domain/<Module>/`** (services, actions) ou équivalent — **pas** de règles métier lourdes dans les contrôleurs.
- **Policies / Gates** pour chaque action sensible ; remplacer la logique historique du type `if ($lvl == …)`.
- **Agent IA** : uniquement via un **catalogue d’actions** (`action_key` → handler) ; chaque handler vérifie **permission utilisateur** + **toggle admin** avant exécution ; journaliser dans `agent_audit_logs`.
- **Duplication** : si une fonction existe pour le web et pour l’agent, une seule implémentation métier.

## Tests

- Nouvelle fonctionnalité API ou action agent → **test Feature** minimal (happy path + refus si non autorisé).
- Commande à faire passer avant de considérer une étape terminée : `php artisan test` (ou tests ciblés du module).

## Sécurité

- Pas de secrets dans le dépôt ; utiliser `.env`.
- Pas de concaténation SQL brute avec entrées utilisateur ; Eloquent / Query Builder + validation.

## Commits

- Messages clairs ; une étape `docs/phases/NN-*.md` = une branche / une PR si possible.

## Langue

- Documentation projet et commentaires utilisateur : **français** si utile à l’équipe.

## Automatisation qualité (obligatoire avant de terminer)

| Outil | Rôle |
|-------|------|
| `.cursor/rules/clinixora-workflow.mdc` | Protocole agent Cursor (toujours actif) |
| `.cursor/rules/clinixora-new-module.mdc` | Checklist module métier + agent |
| `.cursor/hooks.json` | Rappel `composer qa` après modifications |
| `composer qa` | `clinixora:verify` + tests PHPUnit |
| `composer sync-agent` | Resync `agent_actions` (modules assistant) |
| `composer dev:win` | Serveur Laravel + Vite (Windows, sans Pail) |
| `php artisan clinixora:verify --fix-agent` | Corrige un catalogue agent incomplet |
| `.githooks/pre-commit` | QA au commit (`git config core.hooksPath .githooks`) |
| `.github/workflows/qa.yml` | CI GitHub (verify + tests) |

Commande unique après chaque session de dev :

```bash
composer qa
```
