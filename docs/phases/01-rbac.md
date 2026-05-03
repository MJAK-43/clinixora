# Phase 1 — RBAC (modèle à implémenter)

## Objectif

Remplacer la notion de niveaux `lvl` par **rôles** et **permissions** Laravel exploitables par l’UI, l’API et l’agent.

## Périmètre

- Tables `roles`, `permissions`, pivots ; colonne `role_id` sur `users` (ou équivalent).
- Seed des rôles alignés sur le métier (Patient, Caissière, Directeur, …).
- Exemple de Policy sur une ressource de démo.

## Hors périmètre

- Migration complète des données depuis Winclinic legacy (phase ultérieure).

## Critères d’acceptation

- [ ] Un utilisateur test peut se connecter avec un rôle seedé
- [ ] Une action protégée renvoie 403 sans permission
- [ ] Tests Feature sur au moins une route protégée

## Tests

```bash
php artisan test --filter=Auth   # ou nom du test créé
```

*Statut : à démarrer après validation de la phase 0.*
