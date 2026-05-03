# Architecture cible

## Couches

1. **HTTP** : `routes/web.php`, `routes/api.php` — thin controllers.
2. **Application** : services métier (`app/Domain/...`).
3. **Domaine** : modèles Eloquent, policies, événements.
4. **Données** : MySQL ; cache Redis optionnel plus tard.

## Authentification

- Session web : utilisateurs staff.
- **Laravel Sanctum** : tokens pour API mobile, WhatsApp bridge, n8n (scopes à définir).

## Agent IA

- **Orchestrateur** unique appelé depuis :
  - endpoint chat in-app ;
  - webhook WhatsApp (après résolution `user_id`).
- LLM reçoit uniquement la liste des **`action_key`** autorisées pour cet utilisateur **et** activées dans l’admin.
- Exécution : handler → service métier → audit log.

## Canaux

Même logique métier ; seul le transport change (HTTP JSON vs webhook WhatsApp).

## Documentation décisions

Décisions structurantes : fichiers `docs/adr/NNNN-titre.md` (Architecture Decision Records).
