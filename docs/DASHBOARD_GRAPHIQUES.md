# Tableau de bord — graphiques & autres widgets (inventaire & personnalisation)

**Objectif** : ne rien oublier à l’implémentation. Sont recensés ici : **graphiques** (sections 1–4), puis **autres blocs** souvent utiles en complément (section 5 — listes, alertes, raccourcis). Chaque entrée doit être **activable / masquable** par l’utilisateur (voir `DESIGN_SYSTEM.md`).

**Règle produit** : l’utilisateur choisit **quels** widgets afficher ; option **« Tout afficher »** réactive tout ce qui est autorisé pour son rôle.

---

## Légende des colonnes

| Colonne | Signification |
|---------|----------------|
| **id** | Identifiant stable pour code, API et `user_dashboard_preferences` (ex. `chart_cashflow_daily`). |
| **Titre affiché** | Libellé proposé en UI (modifiable). |
| **Rôles cibles** | Indicatif : qui devrait voir ce graphique par défaut (à valider dans `RBAC_MATRIX.md`). |
| **Période par défaut** | Proposition. |
| **Config utilisateur** | Options que l’écran de personnalisation peut offrir. |

---

## 1. Financier / encaissements

| id | Titre affiché | Description | Rôles cibles | Période défaut | Config utilisateur |
|----|---------------|-------------|--------------|----------------|--------------------|
| `chart_encashments_timeline` | Encaissements dans le temps | Courbe ou barres des montants encaissés par jour | Direction, compta, caisse principale, caissier selon périmètre | 30 jours | Période (7/30/90), granularité jour/semaine, filtre caisse si plusieurs |
| `chart_encashments_by_payment_mode` | Encaissements par mode de paiement | Histogramme : espèces, mobile money, carte, etc. | Idem | Mois en cours | Période, modes inclus |

---

## 2. Activité médicale / actes

| id | Titre affiché | Description | Rôles cibles | Période défaut | Config utilisateur |
|----|---------------|-------------|--------------|----------------|--------------------|
| `chart_acts_volume_timeline` | Volume d’actes (tous types) | Courbe : nombre d’actes enregistrés par jour | Staff médical, secrétariat, direction | 30 jours | Période, granularité |
| `chart_acts_by_service_type` | Répartition par type de service | Barres : consultation, examen, hospi, etc. | Idem | Mois en cours | Filtre optionnel sur un sous-ensemble de types |
| `chart_appointments_timeline` | Rendez-vous | RDV créés / statuts si données disponibles | Secrétariat, accueil, direction | 30 jours | Période |

---

## 3. Trésorerie (vue agrégée)

| id | Titre affiché | Description | Rôles cibles | Période défaut | Config utilisateur |
|----|---------------|-------------|--------------|----------------|--------------------|
| `chart_treasury_cash_balance` | Évolution des soldes caisses | Lignes : solde total ou par caisse (si historique stocké) | Direction, compta | 30 jours | Caisse « toutes » ou une |
| `chart_expenses_by_category` | Dépenses par catégorie | Barres (données `depense_caisse` catégorisées) | Direction, compta | Mois en cours | Période |

---

## 4. Stock / pharmacie (optionnel selon rôle)

| id | Titre affiché | Description | Rôles cibles | Période défaut | Config utilisateur |
|----|---------------|-------------|--------------|----------------|--------------------|
| `chart_stock_movements` | Mouvements de stock | Entrées / sorties dans le temps | Magasinier, pharmacien, direction | 30 jours | Site magasin / pharmacie |
| `chart_top_consumed_products` | Top produits consommés | Barres horizontales top N | Idem | Mois en cours | N = 5 / 10 / 20 |

---

## 5. Autres blocs pertinents (souvent oubliés — hors graphiques)

À traiter comme **widgets personnalisables** au même titre que les cartes KPI et les graphiques (`widget_id` dans les préférences utilisateur).

| id suggéré | Type | Contenu | Intérêt |
|------------|------|---------|---------|
| `alerts_required_actions` | Liste / badges | Dossiers **en attente** : paiements à valider, actes non soldés, résultats labo à saisir (selon modules) | Réduit les oublis métier ; prioriser le travail du jour |
| `today_agenda` | Liste compacte | **RDV du jour** (heure, patient, praticien, salle) | Vue opérationnelle accueil / secrétariat |
| `today_surgeries_or_blocks` | Liste ou mini-calendrier | **Bloc opératoire / interventions** du jour | Si l’établissement opère |
| `beds_occupancy_kpi` | Cartes ou jauge | **Taux d’occupation lits** / lits libres | Hospitalisation |
| `stock_expiry_alerts` | Liste courte | Articles **proches péremption** ou **stock bas** (top 5) | Complète les graphiques stock ; lien vers liste détail |
| `quick_actions` | Boutons | Raccourcis **Nouveau patient**, **Nouveau RDV**, **Nouvelle consultation**… selon rôle | Réduit les clics vers les tâches fréquentes |
| `recent_activity_audit` | Liste filtrée | **Dernières actions** (qui a encaissé, modifié un dossier) — extrait du journal d’audit | Direction / contrôle ; optionnel RGPD (limiter champs) |
| `period_comparison_hint` | Texte ou chip | **Variation vs période précédente** (+12 % encaissements vs mois dernier) sur un ou deux KPI | Contexte sans nouveau graphique |
| `notifications_summary` | Compteur + lien | Rappel **notifications** non lues (aligné barre du haut legacy) | Cohérence avec Stock/Péremption déjà en header |

**À ne mettre que si le métier le demande** : météo, actualités externes, objectifs commerciaux chiffrés (hors culture clinique classique).

---

## 6. MVP recommandé (ne pas tout livrer jour 1)

Priorité **phase 1** du dashboard graphique :

1. `chart_encashments_timeline`  
2. `chart_acts_volume_timeline` **ou** `chart_acts_by_service_type` (au choix produit)  
3. `chart_appointments_timeline` (si module RDV prêt en parallèle)

Les autres = **phase 2+**.

**Blocs non graphiques — MVP recommandé** : `quick_actions` + `today_agenda` (ou `alerts_required_actions` si vous avez déjà les données « à traiter »).

---

## 7. Implémentation technique (rappel)

- Chaque `id` = entrée dans une table ou config `dashboard_widgets` avec **`type`** (`chart`, `list`, `kpi`, `shortcuts`, …) + `component_key` + `default_enabled` + `required_permissions`.  
- Préférences utilisateur : JSON ou table `user_dashboard_widgets (user_id, widget_id, visible, settings_json)`.  
- Données : pour les graphiques, endpoints `/api/v1/dashboard/charts/{id}?…` ; pour les listes (RDV du jour, alertes), endpoints `/api/v1/dashboard/widgets/{id}`.  
- **Masquage** : si le rôle n’a pas la permission du widget, ne pas le proposer dans la liste de personnalisation (quel que soit le type).

---

## Documents liés

- `DESIGN_SYSTEM.md` — personnalisation dashboard + assistant.  
- `MODULES_SPEC.md` — module 10 Dashboard.  
- `RBAC_MATRIX.md` — qui voit quoi.

---

*À mettre à jour lorsque de nouveaux besoins métier apparaissent (ex. indicateurs qualité, délais de paiement).*
