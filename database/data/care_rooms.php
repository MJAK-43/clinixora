<?php

/**
 * Salles de soin (8 entrées de référence).
 *
 * @return list<array{name: string, code: string, service_code: string, location: string, icon: string, is_active: bool}>
 */
return [
    ['name' => 'Salle de soins générale', 'code' => 'SOIN-01', 'service_code' => 'CONS', 'location' => 'RDC', 'icon' => 'stethoscope', 'is_active' => true],
    ['name' => 'Salle de soins spécialisée', 'code' => 'SOIN-02', 'service_code' => 'CSPE', 'location' => 'Niveau 1', 'icon' => 'briefcase', 'is_active' => true],
    ['name' => 'Salle de soins urgence', 'code' => 'SOIN-03', 'service_code' => 'URG', 'location' => 'Niveau 0', 'icon' => 'syringe', 'is_active' => true],
    ['name' => 'Salle de soins pédiatrique', 'code' => 'SOIN-04', 'service_code' => 'PEDS', 'location' => 'Niveau 1', 'icon' => 'baby', 'is_active' => true],
    ['name' => 'Salle de soins maternité', 'code' => 'SOIN-05', 'service_code' => 'MAT', 'location' => 'Niveau 2', 'icon' => 'baby', 'is_active' => true],
    ['name' => 'Salle de soins kinésithérapie', 'code' => 'SOIN-06', 'service_code' => 'KINE', 'location' => 'Niveau 0', 'icon' => 'running', 'is_active' => true],
    ['name' => 'Salle de soins dialyse', 'code' => 'SOIN-07', 'service_code' => 'DIAL', 'location' => 'Niveau 1', 'icon' => 'kidney', 'is_active' => true],
    ['name' => 'Salle de soins oncologie', 'code' => 'SOIN-08', 'service_code' => 'ONCS', 'location' => 'Niveau 1', 'icon' => 'ribbon', 'is_active' => false],
];
