# API REST

## Versionnement

- Préfixe : `/api/v1/`.
- Auth : Bearer token (Sanctum), sauf routes publiques explicitement listées.

## Conventions

- JSON : camelCase ou snake_case — **à figer** ; Laravel utilise souvent snake_case côté modèle.
- Pagination : `?page=` + métadonnées style Laravel.
- Erreurs : format problème JSON cohérent (validation 422, 403 policy, etc.).

## OpenAPI

Optionnel : générer ou maintenir `docs/openapi.yaml` pour Cursor et les tests contractuels.
