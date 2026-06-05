<?php

/**
 * Services cliniques de référence (28 entrées — même structure que spécialités).
 *
 * @return list<array{name: string, code: string, description: string, icon: string, is_active: bool}>
 */
return [
    ['name' => 'Consultation générale', 'code' => 'CONS', 'description' => 'Consultation médicale de premier recours', 'icon' => 'stethoscope', 'is_active' => true],
    ['name' => 'Consultation spécialisée', 'code' => 'CSPE', 'description' => 'Consultation avec un praticien spécialiste', 'icon' => 'briefcase', 'is_active' => true],
    ['name' => 'Hospitalisation', 'code' => 'HOSP', 'description' => 'Prise en charge en service d’hospitalisation', 'icon' => 'bed', 'is_active' => true],
    ['name' => 'Urgences', 'code' => 'URG', 'description' => 'Accueil et soins en service d’urgences', 'icon' => 'syringe', 'is_active' => true],
    ['name' => 'Chirurgie ambulatoire', 'code' => 'CHAM', 'description' => 'Interventions chirurgicales sans hospitalisation prolongée', 'icon' => 'scalpel', 'is_active' => true],
    ['name' => 'Bloc opératoire', 'code' => 'BLOC', 'description' => 'Interventions en salle d’opération', 'icon' => 'scalpel', 'is_active' => true],
    ['name' => 'Réanimation', 'code' => 'REA', 'description' => 'Soins intensifs et surveillance continue', 'icon' => 'heart', 'is_active' => true],
    ['name' => 'Maternité', 'code' => 'MAT', 'description' => 'Suivi de grossesse et accouchement', 'icon' => 'baby', 'is_active' => true],
    ['name' => 'Pédiatrie', 'code' => 'PEDS', 'description' => 'Soins médicaux aux enfants hospitalisés', 'icon' => 'baby', 'is_active' => true],
    ['name' => 'Laboratoire', 'code' => 'LABO', 'description' => 'Analyses biologiques et prélèvements', 'icon' => 'blood', 'is_active' => true],
    ['name' => 'Imagerie médicale', 'code' => 'IMG', 'description' => 'Radiologie, échographie et scanner', 'icon' => 'scan', 'is_active' => true],
    ['name' => 'Pharmacie', 'code' => 'PHAR', 'description' => 'Dispensation et gestion des médicaments', 'icon' => 'syringe', 'is_active' => true],
    ['name' => 'Kinésithérapie', 'code' => 'KINE', 'description' => 'Rééducation fonctionnelle et réadaptation', 'icon' => 'running', 'is_active' => true],
    ['name' => 'Dialyse', 'code' => 'DIAL', 'description' => 'Séances d’épuration rénale', 'icon' => 'kidney', 'is_active' => true],
    ['name' => 'Oncologie', 'code' => 'ONCS', 'description' => 'Prise en charge des patients cancéreux', 'icon' => 'ribbon', 'is_active' => true],
    ['name' => 'Cardiologie interventionnelle', 'code' => 'CINT', 'description' => 'Explorations et actes cardiaques invasifs', 'icon' => 'heart', 'is_active' => true],
    ['name' => 'Endoscopie', 'code' => 'ENDO', 'description' => 'Explorations endoscopiques digestives', 'icon' => 'stomach', 'is_active' => true],
    ['name' => 'Vaccination', 'code' => 'VACC', 'description' => 'Campagnes et consultations vaccinales', 'icon' => 'syringe', 'is_active' => true],
    ['name' => 'Médecine du travail', 'code' => 'MTRA', 'description' => 'Visites médicales et prévention professionnelle', 'icon' => 'hardhat', 'is_active' => true],
    ['name' => 'Nutrition clinique', 'code' => 'NUTR', 'description' => 'Accompagnement diététique et nutritionnel', 'icon' => 'apple', 'is_active' => true],
    ['name' => 'Psychiatrie', 'code' => 'PSYS', 'description' => 'Consultations et hospitalisations psychiatriques', 'icon' => 'mind', 'is_active' => true],
    ['name' => 'Accueil', 'code' => 'ACC', 'description' => 'Orientation et enregistrement des patients', 'icon' => 'briefcase', 'is_active' => true],
    ['name' => 'Facturation', 'code' => 'FACT', 'description' => 'Établissement des factures et dossiers administratifs', 'icon' => 'briefcase', 'is_active' => true],
    ['name' => 'Encaissement', 'code' => 'CAIS', 'description' => 'Paiements et recouvrement des honoraires', 'icon' => 'briefcase', 'is_active' => true],
    ['name' => 'Ambulance', 'code' => 'AMBU', 'description' => 'Transport sanitaire et évacuations', 'icon' => 'running', 'is_active' => true],
    ['name' => 'Stérilisation', 'code' => 'STER', 'description' => 'Stérilisation du matériel médical', 'icon' => 'syringe', 'is_active' => true],
    ['name' => 'Archives médicales', 'code' => 'ARCH', 'description' => 'Conservation et gestion des dossiers', 'icon' => 'briefcase', 'is_active' => false],
    ['name' => 'Téléconsultation', 'code' => 'TELE', 'description' => 'Consultations médicales à distance', 'icon' => 'scan', 'is_active' => true],
];
