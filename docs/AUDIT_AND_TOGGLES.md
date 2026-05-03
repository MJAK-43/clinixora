# Audit et toggles agent

## Toggles administrateur

- Table prévue : `agent_actions` (définition) + paramètres par tenant/site si besoin.
- Chaque action peut être **désactivée globalement** pour l’agent même si le rôle métier l’autoriserait dans l’UI.
- Interface admin : liste + interrupteur ; réservée aux rôles `admin` / `directeur` (à définir en policy).

## Journal d’audit agent

Champs minimaux recommandés :

- `user_id`, `channel` (`app` | `whatsapp`), `action_key`
- `payload_hash` ou payload réduit (attention RGPD)
- `status` (success / denied / error)
- `ip` (pour app), `whatsapp_message_id` (optionnel)
- `created_at`

Conservation : politique interne (durée, anonymisation).
