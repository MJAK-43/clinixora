# Clinixora — produit

## Vision

Application web de gestion pour établissement de santé : patients, actes, facturation, stock, reporting, administration.

## Objectifs v2

- Interface moderne, simple, fiable (Blade + Tailwind + Alpine).
- Backend Laravel structuré (domaines, policies, API versionnée).
- **Agent IA** : même métier que l’UI, canal app + WhatsApp ; catalogue d’actions activables une par une dans le dashboard admin.
- Sécurité renforcée (RBAC explicite, audit).

## Périmètre MVP (à affiner)

À compléter lors d’un atelier : ordre des modules (ex. auth + patients + caisse en premier).

## Hors scope initial (exemple)

- Remplacement complet jour 1 de l’ancien système sans migration données validée.
- Diagnostic médical automatisé par IA.

## Référence legacy

Projet historique : dépôt **winclinic** (PHP procédural). La parité fonctionnelle se fait module par module ; pas de copier-coller aveugle.
