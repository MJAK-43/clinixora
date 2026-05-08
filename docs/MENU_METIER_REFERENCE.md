# Référence métier — correspondance des menus (legacy Winclinic → Clinixora)

Document de cadrage pour **comprendre ce que chaque entrée de menu représente** dans l’ancienne version et ce qu’il faut reproduire ou moderniser dans Clinixora.  
Les libellés peuvent être corrigés orthographiquement dans l’UI (ex. *Chirurgien*, *Vaccination*).

---

## 1. Tableau de bord

| Élément | Description métier |
|---------|-------------------|
| **Rôle** | Point d’entrée après connexion : vue synthétique de l’activité (indicateurs, raccourcis, éventuellement graphiques et assistant). |
| **Contenu typique (legacy)** | Compteurs par catégorie (spécialistes, patients, produits, services, salles, caisses, etc.) ; varie selon le profil utilisateur (`lvl`). |
| **Clinixora** | Dashboard personnalisable (KPI, graphiques, assistant) — voir `DESIGN_SYSTEM.md`. |

---

## 2. Menus « Corps médical / ressources humaines » (listes / gestion)

Ces entrées mènent en général vers des **écrans de liste** (et parfois création / fiche) pour le type d’acteur concerné. Ce ne sont **pas** des actes de facturation directs.

| Menu | Correspondance métier |
|------|------------------------|
| **Médecin** | Gestion de la liste des **médecins** (fiches, spécialité, lien utilisateur éventuel). |
| **Infirmier(ère)** | Gestion de la liste des **infirmiers / infirmières**. |
| **Chirurgien** | Gestion de la liste des **chirurgiens**. |
| **Techniciens** | Gestion des **techniciens de laboratoire** (et assimilés selon paramétrage). |
| **Personnel** | Gestion du **personnel administratif / non médical** (souvent table `personnel` côté legacy). |
| **Patients** | **Dossier patient** : liste, création, fiche (identité, historique d’actes, etc.). |
| **Fournisseurs** | Gestion des **fournisseurs** (achats, stock, commandes). |
| **Rendez-vous** | **Agenda / rendez-vous** : planification, liens patient & praticien, statuts. |

*Remarque : la visibilité de chaque menu dépend du **rôle** (ex. seuls certains profils voient Fournisseurs ou toutes les listes RH). La matrice finale est dans `RBAC_MATRIX.md` (à compléter).*

---

## 3. Facturations

| Élément | Description métier |
|---------|-------------------|
| **Objectif** | **Enregistrer ou préparer la facturation** d’un **service** que le patient souhaite réaliser : choix du **type d’acte / filière**, puis saisie métier (patient, prestations, montants selon règles). |
| **Présentation UI attendue** | **Cartes** représentant chaque **service de facturation** ; au clic, ouverture du flux métier correspondant (formulaire « nouvelle … », liste des actes du type choisi, etc.). |

### Liste des services à afficher sous forme de cartes

1. Consultation  
2. Examen  
3. Radiologie  
4. Hospitalisation  
5. Opération  
6. Anesthésie  
7. Échographie  
8. Vaccination  
9. Consultation prénatale  
10. Diagnostique  
11. Autres services  
12. Ordonnance  

*(Ordre d’affichage à figer en UX ; libellés exacts peuvent être harmonisés.)*

---

## 4. Règlements

| Élément | Description métier |
|---------|-------------------|
| **Objectif** | **Encaissement / paiement** : choisir **quel type de service / filière** le patient doit **régler**, puis accéder aux dossiers en attente de paiement (files type « checker », montants, modes de paiement). |
| **Différence avec Facturations** | Facturations ≈ **création / gestion de la prestation à facturer** ; Règlements ≈ **choix du périmètre de paiement** puis traitement des **règlements** associés. |
| **Présentation UI attendue** | **Mêmes cartes de services** que pour Facturations (liste ci‑dessous), pour orienter l’utilisateur vers la bonne **liste à payer**. |

### Liste des services (cartes — identique à la section Facturations pour cohérence UI)

1. Consultation  
2. Examen  
3. Radiologie  
4. Hospitalisation  
5. Opération  
6. Anesthésie  
7. Échographie  
8. Vaccination  
9. Consultation prénatale  
10. Diagnostique  
11. Autres services  
12. Ordonnance  

---

## 5. Archives

| Élément | Description métier |
|---------|-------------------|
| **Objectif** | Consulter les **actes déjà payés** par les patients dont la donnée est **dans le système depuis plus de 24 h** (filtre temporel sur la présence en base ou sur la date de paiement — **à trancher en implémentation** avec la règle métier exacte du legacy). |
| **Présentation UI attendue** | **Mêmes cartes de services** que Facturations / Règlements ; au clic, listes **archivées** pour ce type d’acte (consultations archivées, examens archivés, etc.). |

### Liste des services (cartes)

Même liste que ci‑dessus (12 types).

---

## 6. Stock & produits (magasin / pharmacie)

| Menu | Correspondance métier |
|------|------------------------|
| **Produits** | Référentiel **articles** (médicaments / consommables selon paramétrage). |
| **Catégories** | **Catégories** de produits pour classification et filtres. |
| **Magasin** | **Stock magasin** principal (quantités, mouvements côté magasin central). |
| **Pharmacie** | **Stock pharmacie** (souvent péremption, alertes). |
| **Commande** | **Commandes** fournisseurs / réapprovisionnement. |
| **Demande-produit** | **Demandes internes** de produits (services vers magasin / pharmacie). |
| **Stock** | Vue **synthèse / menus stock** (alertes, transferts — selon legacy regroupement sous « Stock »). |
| **Fournitures** | **Fournitures et outillage** (souvent parallèle aux produits médicaux : outils, petit matériel, demandes associées). |

---

## 7. Trésorerie

| Élément | Description métier |
|---------|-------------------|
| **Rôle** | **Argent** : caisses, transferts, dépenses, cautions, retraits, soldes par caisse / banque selon modules legacy (`liste_caisse`, `nouvelle_depense_caisse`, etc.). |
| **Clinixora** | À mapper vers écrans dédiés + agrégats pour dashboard (soldes, bandeaux bleus « total caisses / banques » comme sur les captures). |

---

## 8. Rapport

| Élément | Description métier |
|---------|-------------------|
| **Rôle** | **Rapports opérationnels et financiers** : billetage caisse, remises, ventes, exports pour contrôle de gestion (selon anciens `liste_rapport_*`, `liste_bilan_*` partiels dans le menu). |

---

## 9. Bilan

| Élément | Description métier |
|---------|-------------------|
| **Rôle** | **Synthèses périodiques** : mensuel, annuel, ventes agrégées — reporting pour direction / comptabilité. |

---

## 10. Configuration

| Élément | Description métier |
|---------|-------------------|
| **Rôle** | **Paramètres applicatifs** : listes prédéfinies (services, types d’examens, modes de paiement, salles, utilisateurs, etc.), référentiels nécessaires aux autres modules. |

---

## Synthèse visuelle des trois blocs « cartes services »

Pour **Facturations**, **Règlements** et **Archives**, l’UI Clinixora peut **réutiliser le même composant** « grille de 12 cartes » avec :

- **facturations.** → navigation vers création / liste **non soldée** ou en cours de facturation selon règle métier ;
- **reglements.** → navigation vers **paiement** pour ce type de service ;
- **archives.** → navigation vers **actes payés** avec critère **> 24 h** en système (à préciser techniquement : date de création d’enregistrement vs date de paiement).

---

## Fichiers connexes dans le dépôt Clinixora

- `docs/MODULES_SPEC.md` — macro-modules à implémenter.  
- `docs/RBAC_MATRIX.md` — qui voit quel menu.  
- Ancienne app : `winclinic/php/navbar_links.php`, `php/main_side_navbar.php` pour les liens PHP exacts.

---

*Document établi pour faciliter la génération des écrans Laravel / Blade et la configuration du dashboard personnalisable.*
