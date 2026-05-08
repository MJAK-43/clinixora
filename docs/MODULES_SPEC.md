# Modules et informations à implémenter — Clinixora

## Objectif

Inventaire des **domaines métier** issus du périmètre Winclinic / menu historique. Pour chaque module : **objets métier** (données), **fonctions** typiques, **intégrations** (PDF, agent).  
Statut : **brouillon de cadrage** — à affiner avant développement module par module.

---

## Légende statut

| Statut | Signification |
|--------|----------------|
| À spécifier | Atelier métier requis |
| Reprise legacy | Existence dans winclinic PHP à mapper |
| MVP | Priorité début de roadmap |

---

## 1. Identité & accès

| Élément | Description |
|---------|-------------|
| Données | Utilisateur, rôle(s), permissions, session, liaison patient/staff |
| Fonctions | Connexion, déconnexion, mot de passe, 2FA (option), gestion profils |
| PDF | — |
| Agent | `auth.*` limité ; pas de contournement des policies |

---

## 2. Patients & dossier

| Élément | Description |
|---------|-------------|
| Données | Identité, coordonnées, pièces jointes, historique de passage |
| Fonctions | CRUD, recherche, fusion doublons (si métier), impression étiquette / fiche |
| PDF | Fiche patient, attestations selon référentiel |
| Agent | CRUD soumis à permissions + brouillon si PJ WhatsApp |

---

## 3. Agenda / rendez-vous

| Élément | Description |
|---------|-------------|
| Données | Créneaux, ressource (praticien, salle), statut |
| Fonctions | CRUD, filtres calendrier, rappels (lien automation) |
| PDF | Feuille RDV (option) |
| Agent | Création / déplacement selon policy |

---

## 4. Prestations cliniques (par type d’acte)

Modules souvent homogènes : **consultation, examen labo, radiologie, échographie, hospitalisation, bloc / opération, anesthésie, autres services, vaccination, prénatale**.

| Élément | Description |
|---------|-------------|
| Données | Acte, patient, prestations lignes, statut (brouillon / validé / payé), liens référentiels |
| Fonctions | Création, modification, annulation, passage en « à payer », liaison diagnostics |
| PDF | Facture / compte rendu selon type |
| Agent | Création / modification selon rôle ; annulation avec confirmation |

*À détailler en sous-sections par type si les champs diffèrent fortement.*

---

## 5. Facturation & caisse

| Élément | Description |
|---------|-------------|
| Données | Paiements, modes, caisses, remises, tickets |
| Fonctions | Encaissement, correction encadrée, impayés, listes « checker » |
| PDF | Reçus, journaux |
| Agent | Paiement = confirmation renforcée ; lecture statuts |

---

## 6. Stock & pharmacie & commandes

| Élément | Description |
|---------|-------------|
| Données | Produits, lots, péremption, magasin, pharmacie, commandes fournisseurs |
| Fonctions | Mouvements, inventaires, alertes stock |
| PDF | Bons de commande, inventaires |
| Agent | Mouvements selon magasinier / admin |

---

## 7. Trésorerie & rapports financiers

| Élément | Description |
|---------|-------------|
| Données | Transferts caisse, dépenses, cautions, retraits |
| Fonctions | Saisie, validation, rapprochement |
| PDF | Billetterie, bilans |
| Agent | Lecture agrégée ; écriture selon rôle |

---

## 8. Référentiels & configuration

| Élément | Description |
|---------|-------------|
| Données | Types d’examens, modes paiement, services, salles, tarifs, banques, … |
| Fonctions | CRUD référentiels ; impact sur nouveaux actes |
| PDF | Export listes (option) |
| Agent | Souvent désactivé par défaut (toggle admin) |

---

## 9. Administration agent IA

| Élément | Description |
|---------|-------------|
| Données | `agent_actions`, toggles, `agent_audit_logs` |
| Fonctions | Activer/désactiver action par action ; consulter journaux |
| PDF | Export audit |
| Agent | Méta — réservé super-admin / direction |

---

## 10. Dashboard & indicateurs

| Élément | Description |
|---------|-------------|
| Données | Agrégats du jour / période (KPI) |
| Fonctions | Cartes, graphiques (Chart.js), filtres période ; **personnalisation** : l’utilisateur choisit les blocs visibles (modules qui l’intéressent) ; bouton **« Tout afficher »** pour rétablir la vue complète ; préférences persistées par utilisateur ; inventaire des graphiques et ids : **`DASHBOARD_GRAPHIQUES.md`** |
| PDF | Export synthèse (option) |
| Agent | Panneau **Assistant Clinixora** (saisie + historique) sur le dashboard ; réponses sur agrégats autorisés ; modules Assistant / Graphiques activables comme les autres widgets |

---

## Prochaines étapes

1. Cocher **MVP** par module dans `PRODUCT.md`.
2. Pour chaque module MVP, créer une issue ou un fichier `docs/modules/<nom>.md` avec **champs SQL** et **règles de calcul**.
3. Aligner `RBAC_MATRIX.md` et `AGENT_ACTIONS_CATALOG.md` sur ces modules.

---

## Source legacy

Menus et liens types : `winclinic/php/navbar_links.php`, `main_side_navbar.php`.
