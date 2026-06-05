<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Page Paramètres (maquette docs/images/parametres.png) — liens placeholder jusqu’aux modules métier.
     */
    public function __invoke(): View
    {
        return view('settings.parametres', [
            'customizeWidgets' => $this->dashboardWidgetDefinitions(),
            'assistantMessages' => [],
            'quickAccess' => [
                ['title' => 'Utilisateurs', 'description' => 'Gérer les accès et rôles', 'href' => '#', 'icon' => 'users', 'iconTile' => 'bg-sky-100 text-sky-600'],
                ['title' => 'Services', 'description' => 'Gérer les services', 'href' => route('services.index'), 'icon' => 'briefcase', 'iconTile' => 'bg-emerald-100 text-emerald-600'],
                ['title' => 'Spécialités', 'description' => 'Gérer les spécialités', 'href' => route('specialties.index'), 'icon' => 'stethoscope', 'iconTile' => 'bg-violet-100 text-violet-600'],
                ['title' => 'Salle de soin', 'description' => 'Gérer les salles', 'href' => '#', 'icon' => 'bed', 'iconTile' => 'bg-orange-100 text-orange-600'],
                ['title' => 'Mode de paiement', 'description' => 'Gérer les modes', 'href' => '#', 'icon' => 'credit', 'iconTile' => 'bg-sky-100 text-sky-600'],
            ],
            'categories' => [
                [
                    'title' => 'Structure & Organisation',
                    'iconWrap' => 'bg-sky-500',
                    'icon' => 'building',
                    'links' => [
                        ['label' => 'Services', 'href' => route('services.index')],
                        ['label' => 'Spécialités', 'href' => route('specialties.index')],
                        'Bloc opératoire',
                        'Salles de soin',
                        'Salles d\'accueil',
                    ],
                ],
                [
                    'title' => 'Gestion médicale',
                    'iconWrap' => 'bg-emerald-500',
                    'icon' => 'heart-pulse',
                    'links' => ['Type d\'examens', 'Types d\'hospitalisations', 'Types d\'opérations', 'Types de consultation', 'Catégories d\'examens'],
                ],
                [
                    'title' => 'Ressources & Équipements',
                    'iconWrap' => 'bg-violet-500',
                    'icon' => 'cube',
                    'links' => ['Lits', 'Chambres', 'Types d\'échantillons', 'Banques', 'Autres services'],
                ],
                [
                    'title' => 'Administration',
                    'iconWrap' => 'bg-orange-500',
                    'icon' => 'clipboard-list',
                    'links' => [
                        'Caisses',
                        'Commissions',
                        ['label' => 'Pays', 'href' => route('geography.countries.index')],
                        ['label' => 'Villes', 'href' => route('geography.countries.index')],
                        ['label' => 'Quartiers', 'href' => route('geography.countries.index')],
                    ],
                ],
                [
                    'title' => 'Ressources humaines',
                    'iconWrap' => 'bg-emerald-500',
                    'icon' => 'user-group',
                    'links' => ['Professions', 'Maladies', 'Vaccins', 'Utilisateurs', 'Rôles & Permissions'],
                ],
                [
                    'title' => 'Paramètres système',
                    'iconWrap' => 'bg-sky-500',
                    'icon' => 'cpu',
                    'links' => [
                        ['label' => 'Assistant Clinixora', 'href' => route('parametres.assistant')],
                        'Configuration générale',
                        'Sécurité',
                        'Sauvegardes',
                        'Intégrations',
                        'Préférences',
                    ],
                ],
                [
                    'title' => 'Autres paramètres',
                    'iconWrap' => 'bg-slate-500',
                    'icon' => 'adjustments',
                    'links' => ['Listes prédéfinies', 'Type d\'échographie', 'Type d\'anesthésie', 'Type de radiologie', 'Autres paramètres'],
                ],
            ],
            'tips' => [
                ['title' => 'Utilisez la recherche', 'text' => 'Trouvez rapidement un paramètre spécifique.', 'icon' => 'search'],
                ['title' => 'Accès rapide', 'text' => 'Les paramètres fréquemment utilisés sont disponibles en accès rapide.', 'icon' => 'bolt'],
                ['title' => 'Sécurité', 'text' => 'Les modifications sont enregistrées automatiquement.', 'icon' => 'shield'],
            ],
        ]);
    }

    /**
     * @return list<array{id: string, label: string, default: bool}>
     */
    private function dashboardWidgetDefinitions(): array
    {
        return [
            ['id' => 'kpi_patients_today', 'label' => 'Patients aujourd\'hui', 'default' => true],
            ['id' => 'kpi_appointments', 'label' => 'Rendez-vous', 'default' => true],
            ['id' => 'kpi_consultations', 'label' => 'Consultations', 'default' => true],
            ['id' => 'kpi_records', 'label' => 'Dossiers créés', 'default' => true],
            ['id' => 'kpi_invoices', 'label' => 'Factures émises', 'default' => true],
            ['id' => 'kpi_cash', 'label' => 'Encaissements (KPI)', 'default' => true],
            ['id' => 'kpi_lab', 'label' => 'Analyses réalisées', 'default' => true],
            ['id' => 'kpi_rx', 'label' => 'Ordonnances', 'default' => true],
            ['id' => 'chart_acts_volume_timeline', 'label' => 'Graphique — Activité 7 jours', 'default' => true],
            ['id' => 'chart_encashments_timeline', 'label' => 'Graphique — Encaissements', 'default' => true],
            ['id' => 'assistant_panel', 'label' => 'Assistant Clinixora', 'default' => true],
        ];
    }
}
