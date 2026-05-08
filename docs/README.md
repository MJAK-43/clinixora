# Documentation Clinixora

Index des fichiers servant au **cadrage** et à l’implémentation assistée (Cursor).

| Fichier | Usage |
|---------|--------|
| [PRODUCT.md](PRODUCT.md) | Vision, périmètre MVP, hors-scope |
| [ROADMAP.md](ROADMAP.md) | Phases numérotées et ordre d’exécution |
| [GLOSSARY.md](GLOSSARY.md) | Termes métier |
| [ARCHITECTURE.md](ARCHITECTURE.md) | Couches, RBAC, agent, canaux |
| [API.md](API.md) | Conventions REST `/api/v1` |
| [AGENT_ACTIONS_CATALOG.md](AGENT_ACTIONS_CATALOG.md) | Liste vivante des `action_key` |
| [AUDIT_AND_TOGGLES.md](AUDIT_AND_TOGGLES.md) | Toggles admin agent + traçabilité |
| [SECURITY.md](SECURITY.md) | Règles de sécurité projet |
| [TESTING.md](TESTING.md) | Politique de tests |
| [DESIGN_SYSTEM.md](DESIGN_SYSTEM.md) | Stack UI (Tailwind, Alpine), principes ; charte visuelle à valider |
| [RBAC_MATRIX.md](RBAC_MATRIX.md) | Rôles + matrice permissions (à compléter avec le métier) |
| [MODULES_SPEC.md](MODULES_SPEC.md) | Modules métier, données et fonctions à implémenter |
| [MENU_METIER_REFERENCE.md](MENU_METIER_REFERENCE.md) | Correspondance détaillée des menus (facturations, règlements, archives, stock, etc.) |
| [DASHBOARD_GRAPHIQUES.md](DASHBOARD_GRAPHIQUES.md) | Tableau de bord : graphiques + widgets (alertes, RDV du jour, raccourcis, etc.) + personnalisation |
| [DEV_ACCOUNTS.md](DEV_ACCOUNTS.md) | Compte administrateur de développement (seed) et rôles en base |
| [phases/](phases/) | Une spec par phase avec critères d’acceptation |

**Workflow** : ouvrir `docs/phases/NN-*.md` → implémenter uniquement cette phase → validation humaine → tests → merge.
