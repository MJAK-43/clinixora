# Rôles et permissions — matrice (Clinixora)

## Objectif de ce fichier

Remplacer progressivement les `if ($lvl == …)` du legacy par des **permissions nommées** Laravel (`permission.key`).  
Ce document est la **référence métier** à faire valider ; les cases sont à compléter lors de l’atelier avec le client / chef de projet.

### Légende des colonnes de droits

Pour chaque permission : **Y** = oui, **N** = non, **P** = partiel / selon contexte (à préciser en note), **—** = non applicable.

Les permissions ci-dessous sont des **exemples de granularité** ; la liste finale sera alignée sur les policies implémentées (`patients.view`, `patients.update`, `billing.payment.create`, etc.).

---

## Rôles métier (hérités du système actuel, libellés normalisés)

| Code suggéré | Libellé affichage | Notes legacy |
|--------------|-------------------|--------------|
| `patient` | Patient | ex. lvl 1 |
| `cashier` | Caissière | lvl 2 |
| `nurse` | Infirmière | lvl 3 |
| `director` | Directeur | lvl 4 |
| `doctor` | Médecin | lvl 5 |
| `storekeeper` | Magasinier | lvl 6 |
| `secretary` | Secrétaire | lvl 7 |
| `surgeon` | Chirurgien | lvl 8 |
| `lab` | Laboratoire | lvl 9 |
| `pharmacist` | Pharmacien | lvl 10 |
| `accountant` | Comptable | lvl 11 |
| `head_cashier` | Caissière principale | lvl 12 |
| `refund_officer` | Remboursement | lvl 13 |
| `radiologist` | Radiologue | lvl 14 |

*Rôle technique additionnel : `super_admin` ou `admin` pour la configuration système et les toggles agent (à définir).*

---

## Matrice provisoire — domaines fonctionnels

Une ligne = famille de permissions ; une colonne = rôle. **À remplir** avec le métier (ce tableau est un canevas, pas la vérité finale).

Permissions exemples (à découper finement au moment de l’implémentation) :

| Permission (exemple) | patient | cashier | nurse | director | doctor | storekeeper | secretary | surgeon | lab | pharmacist | accountant | head_cashier | refund_officer | radiologist |
|------------------------|---------|---------|-------|----------|--------|-------------|-----------|---------|-----|------------|--------------|--------------|----------------|-------------|
| `dashboard.view` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `patients.view` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `patients.create` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `patients.update` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `patients.delete` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `appointments.*` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `acts.consultation.*` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `acts.exam.*` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `billing.payment.*` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `billing.invoice_pdf` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `stock.*` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `treasury.*` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `reports.*` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `settings.referentials.*` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `users.manage` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| `agent.actions.toggle` | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? | ? |

**Méthode de travail recommandée**

1. Valider les rôles et orthographe des libellés affichés.
2. Par module (voir `MODULES_SPEC.md`), lister les **actions** (consulter, créer, modifier, annuler, exporter PDF…).
3. Traduire chaque action en `permission.key` et cocher la matrice.
4. Implémenter les policies + seed des permissions ; les tests Feature vérifient les refus 403.

---

## Références legacy

- `winclinic/ROLES_UTILISATEURS.txt` — synthèse des menus par niveau.
- Code PHP : `php/main_side_navbar.php`, `liste_*`, `save_*`, `update_*_paye.php` pour les exceptions menu vs page.

*Dernière mise à jour : création du fichier — matrice à compléter.*
