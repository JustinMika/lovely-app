<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Client;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesReportController extends Controller
{
	/**
	 * Afficher le rapport des ventes
	 */
	public function index()
	{
		// Données pour les graphiques
		$salesData = $this->getSalesChartData();
		$topProductsData = $this->getTopProductsData();
		$monthlyData = $this->getMonthlySalesData();

		return view('pages.reports.sales', compact('salesData', 'topProductsData', 'monthlyData'));
	}

	/**
	 * Exporter le rapport des ventes en PDF
	 */
	public function exportPdf(Request $request)
	{
		$dateFrom = $request->get('date_from') ? Carbon::parse($request->get('date_from')) : Carbon::now()->subMonths(12);
		$dateTo = $request->get('date_to') ? Carbon::parse($request->get('date_to')) : Carbon::now();

		// Données pour les graphiques
		$salesData = $this->getSalesChartDataFiltered($dateFrom, $dateTo);
		$topProductsData = $this->getTopProductsDataFiltered($dateFrom, $dateTo);
		$monthlyData = $this->getMonthlySalesDataFiltered($dateFrom, $dateTo);

		// Statistiques générales pour la période
		$generalStats = [
			'total_ventes' => Vente::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
			'chiffre_affaires' => Vente::whereBetween('created_at', [$dateFrom, $dateTo])->sum('total'),
			'montant_encaisse' => Vente::whereBetween('created_at', [$dateFrom, $dateTo])->sum('montant_paye'),
			'ventes_aujourdhui' => Vente::whereDate('created_at', today())->count(),
		];

		$beneficeTotal = DB::table('ligne_ventes')
			->join('ventes', 'ligne_ventes.vente_id', '=', 'ventes.id')
			->whereBetween('ventes.created_at', [$dateFrom, $dateTo])
			->selectRaw('SUM((ligne_ventes.prix_unitaire - ligne_ventes.prix_achat) * ligne_ventes.quantite) as benefice_total')
			->first();

		// Créer le PDF avec FPDF
		$pdf = new \FPDF('P', 'mm', 'A4');
		$pdf->AddPage();
		$pdf->SetFont('Arial', 'B', 18);

		// En-tête principal
		$appSettings = \App\Models\AppSetting::getInstance();
		$companyName = $appSettings->company_name ?? $appSettings->app_name;

		$pdf->Cell(0, 12, 'RAPPORT DES VENTES - ' . $companyName, 0, 1, 'C');
		$pdf->SetFont('Arial', '', 10);
		$periodText = 'Periode: ' . $dateFrom->format('d/m/Y') . ' au ' . $dateTo->format('d/m/Y');
		$pdf->Cell(0, 6, $periodText, 0, 1, 'C');
		$pdf->Cell(0, 6, 'Genere le ' . date('d/m/Y a H:i'), 0, 1, 'C');
		$pdf->Ln(8);

		// Section 1: Statistiques générales
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->Cell(0, 8, '1. STATISTIQUES GENERALES', 0, 1, 'L');
		$pdf->SetFont('Arial', '', 10);

		$panierMoyen = $generalStats['total_ventes'] > 0 ? $generalStats['chiffre_affaires'] / $generalStats['total_ventes'] : 0;

		$pdf->Cell(95, 6, 'Total des ventes: ' . number_format($generalStats['total_ventes']), 0, 0, 'L');
		$pdf->Cell(95, 6, 'Ventes aujourd\'hui: ' . number_format($generalStats['ventes_aujourdhui']), 0, 1, 'L');

		$pdf->Cell(95, 6, 'Chiffre d\'affaires: ' . number_format($generalStats['chiffre_affaires'], 0, ',', ' ') . ' FCFA', 0, 0, 'L');
		$pdf->Cell(95, 6, 'Montant encaisse: ' . number_format($generalStats['montant_encaisse'], 0, ',', ' ') . ' FCFA', 0, 1, 'L');

		$pdf->Cell(95, 6, 'Benefice total: ' . number_format($beneficeTotal->benefice_total ?? 0, 0, ',', ' ') . ' FCFA', 0, 0, 'L');
		$pdf->Cell(95, 6, 'Panier moyen: ' . number_format($panierMoyen, 0, ',', ' ') . ' FCFA', 0, 1, 'L');

		$pdf->Ln(8);

		// Section 2: Évolution des ventes
		if (!empty($salesData['labels'])) {
			$pdf->SetFont('Arial', 'B', 14);
			$pdf->Cell(0, 8, '2. EVOLUTION DES VENTES', 0, 1, 'L');

			$pdf->SetFont('Arial', 'B', 9);
			$pdf->SetFillColor(240, 240, 240);
			$pdf->Cell(80, 6, 'Mois', 1, 0, 'C', true);
			$pdf->Cell(50, 6, 'Chiffre d\'affaires', 1, 0, 'C', true);
			$pdf->Cell(30, 6, 'Nb Ventes', 1, 1, 'C', true);

			$pdf->SetFont('Arial', '', 8);
			for ($i = 0; $i < count($salesData['labels']); $i++) {
				$label = $salesData['labels'][$i];
				$value = $salesData['data'][$i];
				$salesCount = $salesData['sales_count'][$i] ?? 0;

				$pdf->Cell(80, 5, $label, 1, 0, 'L');
				$pdf->Cell(50, 5, number_format($value, 0, ',', ' ') . ' FCFA', 1, 0, 'R');
				$pdf->Cell(30, 5, number_format($salesCount), 1, 1, 'C');
			}
			$pdf->Ln(5);
		}

		// Section 3: Top Articles
		if (!empty($topProductsData['labels'])) {
			$pdf->SetFont('Arial', 'B', 14);
			$pdf->Cell(0, 8, '3. TOP ARTICLES LES PLUS VENDUS', 0, 1, 'L');

			$pdf->SetFont('Arial', 'B', 9);
			$pdf->SetFillColor(240, 240, 240);
			$pdf->Cell(100, 6, 'Article', 1, 0, 'C', true);
			$pdf->Cell(40, 6, 'Quantite Vendue', 1, 0, 'C', true);
			$pdf->Cell(50, 6, 'Pourcentage', 1, 1, 'C', true);

			$totalQuantity = array_sum($topProductsData['data']);
			$pdf->SetFont('Arial', '', 8);

			for ($i = 0; $i < min(count($topProductsData['labels']), 5); $i++) {
				$label = $this->truncateText($topProductsData['labels'][$i], 60);
				$value = $topProductsData['data'][$i];
				$percentage = $totalQuantity > 0 ? round(($value / $totalQuantity) * 100, 1) : 0;

				$pdf->Cell(100, 5, $label, 1, 0, 'L');
				$pdf->Cell(40, 5, number_format($value), 1, 0, 'C');
				$pdf->Cell(50, 5, $percentage . '%', 1, 1, 'C');
			}
			$pdf->Ln(5);
		}

		// Nouvelle page pour les détails supplémentaires
		$pdf->AddPage();

		// Section 4: Détail par mois
		if (!empty($monthlyData['labels'])) {
			$pdf->SetFont('Arial', 'B', 14);
			$pdf->Cell(0, 8, '4. DETAIL DES VENTES PAR PERIODE', 0, 1, 'L');

			$pdf->SetFont('Arial', 'B', 9);
			$pdf->SetFillColor(240, 240, 240);
			$pdf->Cell(80, 6, 'Periode', 1, 0, 'C', true);
			$pdf->Cell(40, 6, 'Nombre de Ventes', 1, 0, 'C', true);
			$pdf->Cell(40, 6, 'Moyenne/Jour', 1, 1, 'C', true);

			$pdf->SetFont('Arial', '', 8);
			for ($i = 0; $i < count($monthlyData['labels']); $i++) {
				$label = $monthlyData['labels'][$i];
				$value = $monthlyData['data'][$i];

				// Calcul de la moyenne par jour (approximatif)
				$moyenneParJour = $value / 7; // 7 jours par semaine

				$pdf->Cell(80, 5, $label, 1, 0, 'L');
				$pdf->Cell(40, 5, number_format($value), 1, 0, 'C');
				$pdf->Cell(40, 5, number_format($moyenneParJour, 1), 1, 1, 'C');
			}
		}

		// Pied de page final
		$pdf->Ln(10);
		$pdf->SetFont('Arial', 'I', 8);
		$pdf->Cell(0, 5, 'Rapport genere automatiquement par ' . $companyName, 0, 1, 'C');
		$pdf->Cell(0, 5, 'Date de generation: ' . date('d/m/Y H:i:s'), 0, 1, 'C');

		// Générer le nom du fichier
		$filename = 'rapport_ventes_' . $dateFrom->format('Y-m-d') . '_au_' . $dateTo->format('Y-m-d') . '.pdf';

		// Retourner le PDF
		return response($pdf->Output('S'), 200, [
			'Content-Type' => 'application/pdf',
			'Content-Disposition' => 'attachment; filename="' . $filename . '"',
		]);
	}

	/**
	 * Données pour le graphique d'évolution des ventes (12 derniers mois)
	 */
	private function getSalesChartData()
	{
		$salesByMonth = Vente::selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            SUM(total) as total_sales,
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

			$monthData = $salesByMonth->first(function ($item) use ($date) {
				return $item->year == $date->year && $item->month == $date->month;
			});

			$data[] = $monthData ? (float) $monthData->total_sales : 0;
			$salesCounts[] = $monthData ? (int) $monthData->sales_count : 0;
		}

		return [
			'labels' => $labels,
			'data' => $data,
			'sales_count' => $salesCounts
		];
	}

	/**
	 * Données pour le graphique des top produits
	 */
	private function getTopProductsData()
	{
		$topProducts = DB::table('ligne_ventes')
			->join('ventes', 'ligne_ventes.vente_id', '=', 'ventes.id')
			->join('articles', 'ligne_ventes.article_id', '=', 'articles.id')
			->where('ventes.created_at', '>=', Carbon::now()->subMonths(3))
			->groupBy('articles.id', 'articles.designation')
			->selectRaw('
                articles.designation,
                SUM(ligne_ventes.quantite) as quantite_vendue
            ')
			->orderByDesc('quantite_vendue')
			->limit(5)
			->get();

		return [
			'labels' => $topProducts->pluck('designation')->toArray(),
			'data' => $topProducts->pluck('quantite_vendue')->toArray()
		];
	}

	/**
	 * Données pour le graphique des ventes mensuelles (4 dernières semaines)
	 */
	private function getMonthlySalesData()
	{
		$weeklySales = Vente::selectRaw('
            WEEK(created_at) as week,
            COUNT(*) as sales_count
        ')
			->where('created_at', '>=', Carbon::now()->subWeeks(4))
			->groupBy('week')
			->orderBy('week')
			->get();

		$labels = [];
		$data = [];

		// Générer les 4 dernières semaines
		for ($i = 3; $i >= 0; $i--) {
			$weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
			$labels[] = 'Sem ' . $weekStart->weekOfYear;

			$weekData = $weeklySales->first(function ($item) use ($weekStart) {
				return $item->week == $weekStart->weekOfYear;
			});

			$data[] = $weekData ? (int) $weekData->sales_count : 0;
		}

		return [
			'labels' => $labels,
			'data' => $data
		];
	}

	/**
	 * Données pour le graphique d'évolution des ventes (période filtrée)
	 */
	private function getSalesChartDataFiltered($dateFrom, $dateTo)
	{
		$salesByMonth = Vente::selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            SUM(total) as total_sales,
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

			$monthData = $salesByMonth->first(function ($item) use ($month) {
				return $item->year == $month->year && $item->month == $month->month;
			});

			$data[] = $monthData ? (float) $monthData->total_sales : 0;
			$salesCounts[] = $monthData ? (int) $monthData->sales_count : 0;
		}

		return [
			'labels' => $labels,
			'data' => $data,
			'sales_count' => $salesCounts
		];
	}

	/**
	 * Données pour le graphique des top produits (période filtrée)
	 */
	private function getTopProductsDataFiltered($dateFrom, $dateTo)
	{
		$topProducts = DB::table('ligne_ventes')
			->join('ventes', 'ligne_ventes.vente_id', '=', 'ventes.id')
			->join('articles', 'ligne_ventes.article_id', '=', 'articles.id')
			->whereBetween('ventes.created_at', [$dateFrom, $dateTo])
			->groupBy('articles.id', 'articles.designation')
			->selectRaw('
                articles.designation,
                SUM(ligne_ventes.quantite) as quantite_vendue
            ')
			->orderByDesc('quantite_vendue')
			->limit(5)
			->get();

		return [
			'labels' => $topProducts->pluck('designation')->toArray(),
			'data' => $topProducts->pluck('quantite_vendue')->toArray()
		];
	}

	/**
	 * Données pour le graphique des ventes mensuelles (période filtrée)
	 */
	private function getMonthlySalesDataFiltered($dateFrom, $dateTo)
	{
		$weeklySales = Vente::selectRaw('
            WEEK(created_at) as week,
            YEAR(created_at) as year,
            COUNT(*) as sales_count
        ')
			->whereBetween('created_at', [$dateFrom, $dateTo])
			->groupBy('year', 'week')
			->orderBy('year')
			->orderBy('week')
			->get();

		$labels = [];
		$data = [];

		$period = \Carbon\CarbonPeriod::create($dateFrom->startOfWeek(), '1 week', $dateTo->endOfWeek());

		foreach ($period as $week) {
			$weekKey = $week->year . '-' . $week->weekOfYear;
			$labels[] = 'Sem ' . $week->weekOfYear;

			$weekData = $weeklySales->first(function ($item) use ($week) {
				return $item->year == $week->year && $item->week == $week->weekOfYear;
			});

			$data[] = $weekData ? (int) $weekData->sales_count : 0;
		}

		return [
			'labels' => $labels,
			'data' => $data
		];
	}

	/**
	 * Tronquer le texte si trop long
	 */
	private function truncateText($text, $maxLength)
	{
		if (strlen($text) <= $maxLength) {
			return $text;
		}
		return substr($text, 0, $maxLength - 3) . '...';
	}
}
