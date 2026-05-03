# Catalogue d’actions agent (liste vivante)

Format par ligne (à compléter au fil du développement) :

| action_key | Module | Handler | Risque | Confirmation humaine | Statut |
|------------|--------|---------|--------|------------------------|--------|
| *ex.* `patients.search` | Patients | `SearchPatientsHandler` | bas | non | à faire |

**Règles**

- Clés en **snake_case** avec préfixe module : `module.operation`.
- Chaque ligne doit avoir une **Policy** ou permission dédiée.
- Les actions destructrices (`delete`, annulation paiement) : colonne confirmation = **oui** + flux WhatsApp dédié.

Dernière mise à jour : création projet.
