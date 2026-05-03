# Roadmap technique (ordre recommandé)

Les détails de chaque phase sont dans `docs/phases/NN-*.md`.

| Phase | Nom | Sortie principale |
|-------|-----|-------------------|
| 0 | Setup | Projet Laravel, env, docs, CI locale (`php artisan test`) |
| 1 | RBAC | Users, roles, permissions, policies, seed rôles métier |
| 2 | Services + API | Premier module métier (ex. Patients) en service + `/api/v1` |
| 3 | Catalogue agent | Tables `agent_actions`, registry, refus si désactivé |
| 4 | Admin toggles + audit | UI activation actions + `agent_audit_logs` |
| 5 | Chat agent in-app | Orchestrateur LLM + tools = catalogue filtré |
| 6 | WhatsApp | Webhook + lien numéro ↔ user + même orchestrateur |
| 7+ | Parité modules | Consultations, facturation, stock, … + entrées catalogue |

**Règle** : ne pas commencer la phase N+1 tant que la phase N n’a pas critères d’acceptation cochés et tests verts.
