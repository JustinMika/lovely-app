<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Article;
use App\Models\LigneVente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    /**
     * Afficher le rapport financier
     */
    public function index()
    {
        // Métriques financières générales
        $financialMetrics = $this->getFinancialMetrics();

        // Données pour les graphiques
        $revenueData = $this->getRevenueChartData();
        $profitData = $this->getProfitChartData();
        $expenseData = $this->getExpenseChartData();

        return view('pages.reports.financial', compact(
            'financialMetrics',
            'revenueData',
            'profitData',
            'expenseData'
        ));
    }

    /**
     * Exporter le rapport financier en PDF
     */
    public function exportPdf(Request $request)
    {
        $dateFrom = $request->get('date_from') ? Carbon::parse($request->get('date_from')) : Carbon::now()->subMonths(12);
        $dateTo = $request->get('date_to') ? Carbon::parse($request->get('date_to')) : Carbon::now();

        // Récupérer les données financières pour la période
        $financialMetrics = $this->getFinancialMetricsFiltered($dateFrom, $dateTo);
        $revenueData = $this->getRevenueChartDataFiltered($dateFrom, $dateTo);
        $profitData = $this->getProfitChartDataFiltered($dateFrom, $dateTo);
        $expenseData = $this->getExpenseChartDataFiltered($dateFrom, $dateTo);

        // Créer le PDF avec FPDF
        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 18);

        // En-tête principal
        $appSettings = \App\Models\AppSetting::getInstance();
        $companyName = $appSettings->company_name ?? $appSettings->app_name;
        $currencySymbol = $appSettings->currency_symbol ?? 'FCFA';

        $pdf->Cell(0, 12, 'RAPPORT FINANCIER - ' . $companyName, 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $periodText = 'Periode: ' . $dateFrom->format('d/m/Y') . ' au ' . $dateTo->format('d/m/Y');
        $pdf->Cell(0, 6, $periodText, 0, 1, 'C');
        $pdf->Cell(0, 6, 'Genere le ' . date('d/m/Y a H:i'), 0, 1, 'C');
        $pdf->Ln(8);

        // Section 1: Métriques financières principales
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 8, '1. METRIQUES FINANCIERES PRINCIPALES', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);

        $pdf->Cell(95, 6, 'Chiffre d\'affaires total: ' . number_format($financialMetrics['total_revenue'], 0, ',', ' ') . ' ' . $currencySymbol, 0, 0, 'L');
        $pdf->Cell(95, 6, 'Cout total des achats: ' . number_format($financialMetrics['total_cost'], 0, ',', ' ') . ' ' . $currencySymbol, 0, 1, 'L');

        $pdf->Cell(95, 6, 'Benefice brut: ' . number_format($financialMetrics['gross_profit'], 0, ',', ' ') . ' ' . $currencySymbol, 0, 0, 'L');
        $pdf->Cell(95, 6, 'Marge beneficiaire: ' . number_format($financialMetrics['profit_margin'], 1) . '%', 0, 1, 'L');

        $pdf->Cell(95, 6, 'Panier moyen: ' . number_format($financialMetrics['average_basket'], 0, ',', ' ') . ' ' . $currencySymbol, 0, 0, 'L');
        $pdf->Cell(95, 6, 'Nombre de ventes: ' . number_format($financialMetrics['total_sales']), 0, 1, 'L');

        $pdf->Ln(8);

        // Section 2: Évolution du chiffre d'affaires
        if (!empty($revenueData['labels'])) {
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 8, '2. EVOLUTION DU CHIFFRE D\'AFFAIRES', 0, 1, 'L');

            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->Cell(80, 6, 'Mois', 1, 0, 'C', true);
            $pdf->Cell(50, 6, 'Revenus', 1, 0, 'C', true);
            $pdf->Cell(30, 6, 'Nb Ventes', 1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 8);
            for ($i = 0; $i < count($revenueData['labels']); $i++) {
                $label = $revenueData['labels'][$i];
                $revenue = $revenueData['data'][$i];
                $salesCount = $revenueData['sales_count'][$i] ?? 0;

                $pdf->Cell(80, 5, $label, 1, 0, 'L');
                $pdf->Cell(50, 5, number_format($revenue, 0, ',', ' ') . ' ' . $currencySymbol, 1, 0, 'R');
                $pdf->Cell(30, 5, number_format($salesCount), 1, 1, 'C');
            }
            $pdf->Ln(5);
        }

        // Section 3: Évolution des bénéfices
        if (!empty($profitData['labels'])) {
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 8, '3. EVOLUTION DES BENEFICES', 0, 1, 'L');

            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->Cell(80, 6, 'Mois', 1, 0, 'C', true);
            $pdf->Cell(60, 6, 'Benefice', 1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 8);
            for ($i = 0; $i < count($profitData['labels']); $i++) {
                $label = $profitData['labels'][$i];
                $profit = $profitData['data'][$i];

                $pdf->Cell(80, 5, $label, 1, 0, 'L');
                $pdf->Cell(60, 5, number_format($profit, 0, ',', ' ') . ' ' . $currencySymbol, 1, 1, 'R');
            }
            $pdf->Ln(5);
        }

        // Nouvelle page pour les détails supplémentaires
        $pdf->AddPage();

        // Section 4: Analyse des dépenses (si applicable)
        if (!empty($expenseData['labels'])) {
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 8, '4. ANALYSE DES DEPENSES', 0, 1, 'L');

            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->Cell(80, 6, 'Mois', 1, 0, 'C', true);
            $pdf->Cell(60, 6, 'Cout des achats', 1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 8);
            for ($i = 0; $i < count($expenseData['labels']); $i++) {
                $label = $expenseData['labels'][$i];
                $expense = $expenseData['data'][$i];

                $pdf->Cell(80, 5, $label, 1, 0, 'L');
                $pdf->Cell(60, 5, number_format($expense, 0, ',', ' ') . ' ' . $currencySymbol, 1, 1, 'R');
            }
        }

        // Pied de page final
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 5, 'Rapport genere automatiquement par ' . $companyName, 0, 1, 'C');
        $pdf->Cell(0, 5, 'Date de generation: ' . date('d/m/Y H:i:s'), 0, 1, 'C');

        // Générer le nom du fichier
        $filename = 'rapport_financier_' . $dateFrom->format('Y-m-d') . '_au_' . $dateTo->format('Y-m-d') . '.pdf';

        // Retourner le PDF
        return response($pdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Récupérer les métriques financières générales
     */
    private function getFinancialMetrics()
    {
        $totalRevenue = Vente::sum('total');
        $totalSales = Vente::count();

        // Calcul du coût total des achats depuis les lignes de vente
        $totalCost = LigneVente::sum(\DB::raw('quantite * prix_achat'));

        $grossProfit = $totalRevenue - $totalCost;
        $averageBasket = $totalSales > 0 ? $totalRevenue / $totalSales : 0;
        $profitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        return [
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'gross_profit' => $grossProfit,
            'profit_margin' => $profitMargin,
            'average_basket' => $averageBasket,
            'total_sales' => $totalSales,
        ];
    }

    /**
     * Récupérer les données du graphique des revenus (12 derniers mois)
     */
    private function getRevenueChartData()
    {
        $revenueByMonth = Vente::selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            SUM(total) as total_revenue,
            COUNT(*) as sales_count
        ')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];
        $salesCounts = [];

        // Générer les 12 derniers mois
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->year . '-' . $date->month;

            $labels[] = $date->format('M Y');

            $monthData = $revenueByMonth->first(function ($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });

            $data[] = $monthData ? (float) $monthData->total_revenue : 0;
            $salesCounts[] = $monthData ? (int) $monthData->sales_count : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'sales_count' => $salesCounts
        ];
    }

    /**
     * Récupérer les données du graphique des bénéfices
     */
    private function getProfitChartData()
    {
        $profitByMonth = DB::table('ligne_ventes')
            ->join('ventes', 'ligne_ventes.vente_id', '=', 'ventes.id')
            ->selectRaw('
                YEAR(ventes.created_at) as year,
                MONTH(ventes.created_at) as month,
                SUM((ligne_ventes.prix_unitaire - ligne_ventes.prix_achat) * ligne_ventes.quantite) as monthly_profit
            ')
            ->where('ventes.created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];

        // Générer les 12 derniers mois
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->year . '-' . $date->month;

            $labels[] = $date->format('M Y');

            $monthData = $profitByMonth->first(function ($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });

            $data[] = $monthData ? (float) $monthData->monthly_profit : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Récupérer les données du graphique des dépenses
     */
    private function getExpenseChartData()
    {
        $expenseByMonth = DB::table('ligne_ventes')
            ->join('ventes', 'ligne_ventes.vente_id', '=', 'ventes.id')
            ->selectRaw('
                YEAR(ventes.created_at) as year,
                MONTH(ventes.created_at) as month,
                SUM(ligne_ventes.quantite * ligne_ventes.prix_achat) as monthly_cost
            ')
            ->where('ventes.created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];

        // Générer les 12 derniers mois
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->year . '-' . $date->month;

            $labels[] = $date->format('M Y');

            $monthData = $expenseByMonth->first(function ($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });

            $data[] = $monthData ? (float) $monthData->monthly_cost : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Récupérer les métriques financières filtrées par période
     */
    private function getFinancialMetricsFiltered($dateFrom, $dateTo)
    {
        $totalRevenue = Vente::whereBetween('created_at', [$dateFrom, $dateTo])->sum('total');
        $totalSales = Vente::whereBetween('created_at', [$dateFrom, $dateTo])->count();

        // Calcul du coût total des achats depuis les lignes de vente
        $totalCost = LigneVente::join('ventes', 'ligne_ventes.vente_id', '=', 'ventes.id')
            ->whereBetween('ventes.created_at', [$dateFrom, $dateTo])
            ->sum(\DB::raw('ligne_ventes.quantite * ligne_ventes.prix_achat'));

        $grossProfit = $totalRevenue - $totalCost;
        $averageBasket = $totalSales > 0 ? $totalRevenue / $totalSales : 0;
        $profitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        return [
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'gross_profit' => $grossProfit,
            'profit_margin' => $profitMargin,
            'average_basket' => $averageBasket,
            'total_sales' => $totalSales,
        ];
    }

    /**
     * Récupérer les données du graphique des revenus (période filtrée)
     */
    private function getRevenueChartDataFiltered($dateFrom, $dateTo)
    {
        $revenueByMonth = Vente::selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            SUM(total) as total_revenue,
            COUNT(*) as sales_count
        ')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];
        $salesCounts = [];

        $period = \Carbon\CarbonPeriod::create($dateFrom->startOfMonth(), '1 month', $dateTo->endOfMonth());

        foreach ($period as $month) {
            $monthKey = $month->year . '-' . $month->month;
            $labels[] = $month->format('M Y');

            $monthData = $revenueByMonth->first(function ($item) use ($month) {
                return $item->year == $month->year && $item->month == $month->month;
            });

            $data[] = $monthData ? (float) $monthData->total_revenue : 0;
            $salesCounts[] = $monthData ? (int) $monthData->sales_count : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'sales_count' => $salesCounts
        ];
    }

    /**
     * Récupérer les données du graphique des bénéfices (période filtrée)
     */
    private function getProfitChartDataFiltered($dateFrom, $dateTo)
    {
        $profitByMonth = DB::table('ligne_ventes')
            ->join('ventes', 'ligne_ventes.vente_id', '=', 'ventes.id')
            ->selectRaw('
                YEAR(ventes.created_at) as year,
                MONTH(ventes.created_at) as month,
                SUM((ligne_ventes.prix_unitaire - ligne_ventes.prix_achat) * ligne_ventes.quantite) as monthly_profit
            ')
            ->whereBetween('ventes.created_at', [$dateFrom, $dateTo])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];

        $period = \Carbon\CarbonPeriod::create($dateFrom->startOfMonth(), '1 month', $dateTo->endOfMonth());

        foreach ($period as $month) {
            $monthKey = $month->year . '-' . $month->month;
            $labels[] = $month->format('M Y');

            $monthData = $profitByMonth->first(function ($item) use ($month) {
                return $item->year == $month->year && $item->month == $month->month;
            });

            $data[] = $monthData ? (float) $monthData->monthly_profit : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Récupérer les données du graphique des dépenses (période filtrée)
     */
    private function getExpenseChartDataFiltered($dateFrom, $dateTo)
    {
        $expenseByMonth = DB::table('ligne_ventes')
            ->join('ventes', 'ligne_ventes.vente_id', '=', 'ventes.id')
            ->selectRaw('
                YEAR(ventes.created_at) as year,
                MONTH(ventes.created_at) as month,
                SUM(ligne_ventes.quantite * ligne_ventes.prix_achat) as monthly_cost
            ')
            ->whereBetween('ventes.created_at', [$dateFrom, $dateTo])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];

        $period = \Carbon\CarbonPeriod::create($dateFrom->startOfMonth(), '1 month', $dateTo->endOfMonth());

        foreach ($period as $month) {
            $monthKey = $month->year . '-' . $month->month;
            $labels[] = $month->format('M Y');

            $monthData = $expenseByMonth->first(function ($item) use ($month) {
                return $item->year == $month->year && $item->month == $month->month;
            });

            $data[] = $monthData ? (float) $monthData->monthly_cost : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}
