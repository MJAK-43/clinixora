<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mot de passe du compte admin créé par AdminUserSeeder
    |--------------------------------------------------------------------------
    |
    | Définir ADMIN_SEED_PASSWORD dans .env pour la production / équipe.
    | En local, la valeur par défaut reste "password" si la variable est absente.
    |
    */

    'admin_seed_password' => env('ADMIN_SEED_PASSWORD', 'password'),

];
