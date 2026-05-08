<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Données génériques — à remplacer par agrégats réels / API (voir docs/DASHBOARD_GRAPHIQUES.md).
     */
    public function __invoke(): View
    {
        $chartLabels = ['Ven', 'Sam', 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu'];

        $activitySeries = [24, 18, 32, 28, 36, 30, 34];
        $cashSeries = [4200, 3800, 5100, 4900, 6200, 5800, 6400];

        return view('dashboard', [
            'kpis' => [
                ['id' => 'kpi_patients_today', 'label' => 'Patients aujourd\'hui', 'value' => '128', 'suffix' => '', 'trend' => 12, 'up' => true],
                ['id' => 'kpi_appointments', 'label' => 'Rendez-vous', 'value' => '42', 'suffix' => '', 'trend' => 8, 'up' => true],
                ['id' => 'kpi_consultations', 'label' => 'Consultations', 'value' => '36', 'suffix' => '', 'trend' => 10, 'up' => true],
                ['id' => 'kpi_records', 'label' => 'Dossiers créés', 'value' => '15', 'suffix' => '', 'trend' => 15, 'up' => true],
                ['id' => 'kpi_invoices', 'label' => 'Factures émises', 'value' => '24', 'suffix' => '', 'trend' => 20, 'up' => true],
                ['id' => 'kpi_cash', 'label' => 'Encaissements', 'value' => '18 750', 'suffix' => ' €', 'trend' => 18, 'up' => true],
                ['id' => 'kpi_lab', 'label' => 'Analyses réalisées', 'value' => '31', 'suffix' => '', 'trend' => 5, 'up' => false],
                ['id' => 'kpi_rx', 'label' => 'Ordonnances', 'value' => '27', 'suffix' => '', 'trend' => 8, 'up' => true],
            ],
            'chartLabels' => $chartLabels,
            'activitySeries' => $activitySeries,
            'cashSeries' => $cashSeries,
            'activityPolyline' => $this->seriesToPolylinePoints($activitySeries, 400, 160),
            'assistantMessages' => [
                [
                    'from' => 'assistant',
                    'html' => 'Voici un résumé des encaissements sur 7 jours : tendance à la hausse sur la fin de semaine. Les montants restent homogènes entre les caisses principales.',
                ],
                [
                    'from' => 'assistant',
                    'html' => '<span class="text-slate-500">[Graphique synthétique — données génériques]</span>',
                ],
                [
                    'from' => 'assistant',
                    'attachment' => ['name' => 'Encaissements_7jours.xlsx', 'size' => '12,4 Ko'],
                ],
            ],
            /** Personnalisation — ids alignés sur docs/DASHBOARD_GRAPHIQUES.md */
            'customizeWidgets' => [
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
            ],
        ]);
    }

    /**
     * @param  list<int|float>  $values
     */
    private function seriesToPolylinePoints(array $values, int $width, int $height): string
    {
        if ($values === []) {
            return '';
        }

        $min = min($values);
        $max = max($values);
        $range = $max - $min;
        if ($range === 0.0) {
            $range = 1;
        }

        $n = count($values);
        $parts = [];
        foreach ($values as $i => $v) {
            $x = $n === 1 ? $width / 2 : ($i / ($n - 1)) * $width;
            $y = $height - (($v - $min) / $range) * $height;
            $parts[] = round($x, 1).','.round($y, 1);
        }

        return implode(' ', $parts);
    }
}
