# Phase 0 — Setup

## Objectif

Projet Laravel opérationnel, documentation de pilotage en place, critères de « fin de phase » clairs.

## Périmètre

- Projet dans `clinixora/` avec dépendances installées.
- Dossier `docs/` complet (index `docs/README.md`).
- Fichier `AGENTS.md` à la racine.

## Hors périmètre

- RBAC métier détaillé, agent IA, WhatsApp.

## Tâches

- [x] `composer create-project` Laravel dans le dossier clinixora
- [x] Arborescence `docs/` + fichiers listés dans `docs/README.md`
- [ ] Optionnel : ajouter `CONTRIBUTING.md`, dossier `docs/adr/`

## Critères d’acceptation (validation humaine)

- [ ] `php artisan --version` fonctionne dans le dossier projet
- [ ] `php artisan test` vert
- [ ] Lecture de `docs/README.md` et `AGENTS.md` validée par le responsable

## Tests de résultat

```bash
cd C:\wamp64\www\clinixora
php artisan test
```

## Notes

- Laravel a pu créer une base SQLite par défaut ; pour MySQL WAMP, ajuster `.env` (`DB_CONNECTION=mysql`, `DB_DATABASE=clinixora`, etc.) et créer la base si besoin.
