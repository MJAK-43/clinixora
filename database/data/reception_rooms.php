<?php

/**
 * Salles d'accueil (6 entrées de référence).
 *
 * @return list<array{name: string, code: string, location: string, icon: string, is_active: bool}>
 */
return [
    ['name' => 'Accueil principal', 'code' => 'ACC-01', 'location' => 'Hall d\'entrée', 'icon' => 'briefcase', 'is_active' => true],
    ['name' => 'Accueil urgences', 'code' => 'ACC-02', 'location' => 'Service urgences', 'icon' => 'syringe', 'is_active' => true],
    ['name' => 'Accueil maternité', 'code' => 'ACC-03', 'location' => 'Maternité', 'icon' => 'baby', 'is_active' => true],
    ['name' => 'Accueil pédiatrie', 'code' => 'ACC-04', 'location' => 'Pédiatrie', 'icon' => 'baby', 'is_active' => true],
    ['name' => 'Accueil consultations', 'code' => 'ACC-05', 'location' => 'Hall consultations', 'icon' => 'stethoscope', 'is_active' => true],
    ['name' => 'Accueil administratif', 'code' => 'ACC-06', 'location' => 'Bureau administratif', 'icon' => 'briefcase', 'is_active' => false],
];
