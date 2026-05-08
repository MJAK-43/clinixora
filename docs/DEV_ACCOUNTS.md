# Comptes de développement — Clinixora

## Administrateur (seed)

Après `php artisan migrate:fresh --seed` :

| Champ | Valeur |
|--------|--------|
| **Email** | `admin@clinixora.local` |
| **Mot de passe** | Valeur de `ADMIN_SEED_PASSWORD` dans `.env`, ou **`password`** si non défini |

Configurer dans `.env` :

```env
ADMIN_SEED_PASSWORD=votre_mot_de_passe_securise
```

Puis relancer le seeder si nécessaire :

```bash
php artisan db:seed --class=AdminUserSeeder
```

**Ne jamais** utiliser ces identifiants en production sans mot de passe fort et email réel.

## Rôles en base

Créés par `RoleSeeder` (alignés sur le périmètre legacy Winclinic + admin système + `user` par défaut inscription) :

| slug | name |
|------|------|
| `admin` | Administrateur système |
| `patient` | Patient |
| `cashier` | Caissière |
| `nurse` | Infirmier(ère) |
| `director` | Directeur |
| `doctor` | Médecin |
| `storekeeper` | Magasinier |
| `secretary` | Secrétaire |
| `surgeon` | Chirurgien |
| `lab` | Laboratoire |
| `pharmacist` | Pharmacien |
| `accountant` | Comptable |
| `head_cashier` | Caissière principale |
| `refund_officer` | Remboursement |
| `radiologist` | Radiologue |
| `user` | Utilisateur (défaut inscription) |

Le modèle `User` expose `isAdmin()` lorsque `role.slug === 'admin'`.

Après mise à jour du seeder : `php artisan db:seed --class=RoleSeeder` (ou `migrate:fresh --seed`).
