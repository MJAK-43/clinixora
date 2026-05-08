<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Rôles alignés sur le périmètre Winclinic (table roles / niveaux métier)
 * + rôle technique « admin » pour l’administration système (non présent comme lvl dans l’ancien dump métier).
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrateur système',
                'slug' => 'admin',
                'description' => 'Accès technique / configuration applicative (Clinixora).',
            ],
            [
                'name' => 'Patient',
                'slug' => 'patient',
                'description' => 'Portail patient / dossier limité (legacy lvl 1).',
            ],
            [
                'name' => 'Caissière',
                'slug' => 'cashier',
                'description' => 'Encaissement, caisse, règlements (legacy lvl 2).',
            ],
            [
                'name' => 'Infirmier(ère)',
                'slug' => 'nurse',
                'description' => 'Soins, actes liés au lit / injections (legacy lvl 3).',
            ],
            [
                'name' => 'Directeur',
                'slug' => 'director',
                'description' => 'Pilotage, paramètres étendus (legacy lvl 4).',
            ],
            [
                'name' => 'Médecin',
                'slug' => 'doctor',
                'description' => 'Consultations, prescriptions (legacy lvl 5).',
            ],
            [
                'name' => 'Magasinier',
                'slug' => 'storekeeper',
                'description' => 'Stock central, commandes (legacy lvl 6).',
            ],
            [
                'name' => 'Secrétaire',
                'slug' => 'secretary',
                'description' => 'Accueil, saisie, facturation courante (legacy lvl 7).',
            ],
            [
                'name' => 'Chirurgien',
                'slug' => 'surgeon',
                'description' => 'Bloc, opérations (legacy lvl 8).',
            ],
            [
                'name' => 'Laboratoire',
                'slug' => 'lab',
                'description' => 'Examens biologiques, techniciens labo (legacy lvl 9).',
            ],
            [
                'name' => 'Pharmacien',
                'slug' => 'pharmacist',
                'description' => 'Pharmacie, délivrance (legacy lvl 10).',
            ],
            [
                'name' => 'Comptable',
                'slug' => 'accountant',
                'description' => 'Contrôle financier, bilans (legacy lvl 11).',
            ],
            [
                'name' => 'Caissière principale',
                'slug' => 'head_cashier',
                'description' => 'Supervision caisses (legacy lvl 12).',
            ],
            [
                'name' => 'Remboursement',
                'slug' => 'refund_officer',
                'description' => 'Contrôle dossiers remboursement (legacy lvl 13).',
            ],
            [
                'name' => 'Radiologue',
                'slug' => 'radiologist',
                'description' => 'Imagerie médicale (legacy lvl 14).',
            ],
            [
                'name' => 'Utilisateur',
                'slug' => 'user',
                'description' => 'Rôle par défaut à l’inscription si aucun métier assigné — à remplacer par un rôle métier en production.',
            ],
        ];

        foreach ($roles as $attrs) {
            Role::query()->firstOrCreate(
                ['slug' => $attrs['slug']],
                $attrs
            );
        }
    }
}
