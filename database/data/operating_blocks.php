<?php

/**
 * Blocs opératoires (maquette page_bloc_operatoire.png — 9 entrées).
 *
 * @return list<array{name: string, code: string, service_code: string, location: string, icon: string, is_active: bool}>
 */
return [
    ['name' => 'Bloc Central', 'code' => 'BLOC-01', 'service_code' => 'CHIR', 'location' => 'Niveau 0', 'icon' => 'scalpel', 'is_active' => true],
    ['name' => 'Bloc Cardio-Thoracique', 'code' => 'BLOC-02', 'service_code' => 'CINT', 'location' => 'Niveau 1', 'icon' => 'heart', 'is_active' => true],
    ['name' => 'Bloc Orthopédie', 'code' => 'BLOC-03', 'service_code' => 'CHAM', 'location' => 'Niveau 0', 'icon' => 'bone', 'is_active' => true],
    ['name' => 'Bloc Ophtalmologie', 'code' => 'BLOC-04', 'service_code' => 'CSPE', 'location' => 'Niveau 1', 'icon' => 'eye', 'is_active' => true],
    ['name' => 'Bloc Maternité', 'code' => 'BLOC-05', 'service_code' => 'MAT', 'location' => 'Niveau 2', 'icon' => 'baby', 'is_active' => true],
    ['name' => 'Bloc Pédiatrie', 'code' => 'BLOC-06', 'service_code' => 'PEDS', 'location' => 'Niveau 1', 'icon' => 'baby', 'is_active' => true],
    ['name' => 'Bloc Endoscopie', 'code' => 'BLOC-07', 'service_code' => 'ENDO', 'location' => 'Niveau 0', 'icon' => 'stomach', 'is_active' => true],
    ['name' => 'Bloc Urgences', 'code' => 'BLOC-08', 'service_code' => 'URG', 'location' => 'Niveau 0', 'icon' => 'syringe', 'is_active' => false],
    ['name' => 'Bloc Réanimation', 'code' => 'BLOC-09', 'service_code' => 'REA', 'location' => 'Niveau 0', 'icon' => 'heart', 'is_active' => true],
];
