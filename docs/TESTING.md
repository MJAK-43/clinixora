# Tests

## Obligatoire

- `php artisan test` doit passer avant merge d’une phase.
- Nouvelle route API critique ou action agent : au moins un **Feature test** (succès + refus 403 si sans permission).

## Local

```bash
php artisan test
```

Base : SQLite en mémoire ou MySQL de test selon configuration `phpunit.xml`.

## Contrôle visuel dashboard (desktop + mobile)

Avant validation UI du dashboard, générer systématiquement les captures :

```bash
npm run qa:screenshot:dashboard
```

Sorties attendues :

- `docs/screenshots/dashboard/dashboard-desktop.png`
- `docs/screenshots/dashboard/dashboard-mobile.png`
