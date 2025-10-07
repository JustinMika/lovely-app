<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Client;
use App\Models\Lot;
use App\Models\User;
use App\Models\Vente;
use App\Models\Ville;
use App\Models\Approvisionnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GlobalReportController extends Controller
{
	/**
	 * Afficher le rapport des ventes
	 */
	public function sales()
	{
		// Données pour les graphiques
		$salesData = $this->getSalesChartData();
		$topProductsData = $this->getTopProductsData();
		$monthlyData = $this->getMonthlySalesData();

		return view('pages.reports.sales', compact('salesData', 'topProductsData', 'monthlyData'));
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

		// Générer les 12 derniers mois
		for ($i = 11; $i >= 0; $i--) {
			$date = Carbon::now()->subMonths($i);
			$monthKey = $date->year . '-' . $date->month;

			$labels[] = $date->format('M Y');

			$monthData = $salesByMonth->first(function ($item) use ($date) {
				return $item->year == $date->year && $item->month == $date->month;
			});

			$data[] = $monthData ? (float) $monthData->total_sales : 0;
		}

		return [
			'labels' => $labels,
			'data' => $data
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

	public function exportGlobal(Request $request)
	{
		$dateRange = $request->get('dateRange', 30);
		$selectedPeriod = $request->get('selectedPeriod', 'month');

		$dateFrom = Carbon::now()->subDays($dateRange);

		// Statistiques générales
		$generalStats = [
			'total_articles' => Article::count(),
			'articles_actifs' => Article::where('actif', true)->count(),
			'total_clients' => Client::count(),
			'total_users' => User::count(),
			'total_villes' => Ville::count(),
			'total_lots' => Lot::count(),
			'lots_en_stock' => Lot::where('quantite_restante', '>', 0)->count(),
			'total_ventes' => Vente::where('created_at', '>=', $dateFrom)->count(),
			'total_approvisionnements' => Approvisionnement::where('created_at', '>=', $dateFrom)->count(),
		];

		// Statistiques financières
		$ventesStats = Vente::where('created_at', '>=', $dateFrom)
			->selectRaw('
                COUNT(*) as nombre_ventes,
                SUM(total) as chiffre_affaires,
                SUM(montant_paye) as montant_encaisse,
                AVG(total) as panier_moyen
            ')
			->first();

		$beneficeTotal = DB::table('ligne_ventes')
			->join('ventes', 'ligne_ventes.vente_id', '=', 'ventes.id')
			->where('ventes.created_at', '>=', $dateFrom)
			->selectRaw('SUM((ligne_ventes.prix_unitaire - ligne_ventes.prix_achat) * ligne_ventes.quantite) as benefice_total')
			->first();

		$stockValue = Lot::where('quantite_restante', '>', 0)
			->selectRaw('SUM(quantite_restante * prix_achat) as valeur_stock_achat, SUM(quantite_restante * prix_vente) as valeur_stock_vente')
			->first();

		$financialStats = [
			'nombre_ventes' => $ventesStats->nombre_ventes ?? 0,
			'chiffre_affaires' => $ventesStats->chiffre_affaires ?? 0,
			'montant_encaisse' => $ventesStats->montant_encaisse ?? 0,
			'panier_moyen' => $ventesStats->panier_moyen ?? 0,
			'benefice_total' => $beneficeTotal->benefice_total ?? 0,
			'valeur_stock_achat' => $stockValue->valeur_stock_achat ?? 0,
			'valeur_stock_vente' => $stockValue->valeur_stock_vente ?? 0,
		];

		// Top articles
		$topArticles = DB::table('ligne_ventes')
			->join('ventes', 'ligne_ventes.vente_id', '=', 'ventes.id')
			->join('articles', 'ligne_ventes.article_id', '=', 'articles.id')
			->where('ventes.created_at', '>=', $dateFrom)
			->groupBy('articles.id', 'articles.designation')
			->selectRaw('
                articles.designation,
                SUM(ligne_ventes.quantite) as quantite_vendue,
                SUM(ligne_ventes.quantite * ligne_ventes.prix_unitaire) as chiffre_affaires,
                SUM((ligne_ventes.prix_unitaire - ligne_ventes.prix_achat) * ligne_ventes.quantite) as benefice
            ')
			->orderByDesc('quantite_vendue')
			->limit(10)
			->get();

		// Top clients
		$topClients = Client::withCount(['ventes' => function ($query) use ($dateFrom) {
			$query->where('created_at', '>=', $dateFrom);
		}])
			->withSum(['ventes' => function ($query) use ($dateFrom) {
				$query->where('created_at', '>=', $dateFrom);
			}], 'total')
			->having('ventes_count', '>', 0)
			->orderByDesc('ventes_sum_total')
			->limit(10)
			->get();

		// Alertes stock
		$stockFaible = Lot::with(['article', 'ville'])
			->where('quantite_restante', '>', 0)
			->where('quantite_restante', '<=', 10)
			->orderBy('quantite_restante')
			->get();

		$ruptureStock = Article::whereDoesntHave('lots', function ($query) {
			$query->where('quantite_restante', '>', 0);
		})
			->where('actif', true)
			->get();

		// Créer le PDF avec FPDF
		$pdf = new \FPDF('P', 'mm', 'A4');
		$pdf->AddPage();
		$pdf->SetFont('Arial', 'B', 18);

		// En-tête principal
		$pdf->Cell(0, 12, 'RAPPORT GLOBAL DE GESTION', 0, 1, 'C');
		$pdf->SetFont('Arial', '', 10);
		$pdf->Cell(0, 6, 'Genere le ' . date('d/m/Y a H:i'), 0, 1, 'C');
		$pdf->Cell(0, 6, 'Periode analysee: ' . $dateRange . ' derniers jours', 0, 1, 'C');
		$pdf->Ln(8);

		// Section 1: Statistiques générales
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->Cell(0, 8, '1. STATISTIQUES GENERALES', 0, 1, 'L');
		$pdf->SetFont('Arial', '', 10);

		$pdf->Cell(95, 6, 'Articles totaux: ' . number_format($generalStats['total_articles']), 0, 0, 'L');
		$pdf->Cell(95, 6, 'Articles actifs: ' . number_format($generalStats['articles_actifs']), 0, 1, 'L');

		$pdf->Cell(95, 6, 'Clients totaux: ' . number_format($generalStats['total_clients']), 0, 0, 'L');
		$pdf->Cell(95, 6, 'Utilisateurs: ' . number_format($generalStats['total_users']), 0, 1, 'L');

		$pdf->Cell(95, 6, 'Villes: ' . number_format($generalStats['total_villes']), 0, 0, 'L');
		$pdf->Cell(95, 6, 'Lots totaux: ' . number_format($generalStats['total_lots']), 0, 1, 'L');

		$pdf->Cell(95, 6, 'Lots en stock: ' . number_format($generalStats['lots_en_stock']), 0, 0, 'L');
		$pdf->Cell(95, 6, 'Ventes (' . $dateRange . 'j): ' . number_format($generalStats['total_ventes']), 0, 1, 'L');

		$pdf->Ln(8);

		// Section 2: Performance financière
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->Cell(0, 8, '2. PERFORMANCE FINANCIERE (' . $dateRange . ' derniers jours)', 0, 1, 'L');
		$pdf->SetFont('Arial', '', 10);

		$pdf->Cell(95, 6, 'Chiffre d\'affaires: ' . number_format($financialStats['chiffre_affaires'], 0, ',', ' ') . ' FCFA', 0, 0, 'L');
		$pdf->Cell(95, 6, 'Montant encaisse: ' . number_format($financialStats['montant_encaisse'], 0, ',', ' ') . ' FCFA', 0, 1, 'L');

		$pdf->Cell(95, 6, 'Benefice total: ' . number_format($financialStats['benefice_total'], 0, ',', ' ') . ' FCFA', 0, 0, 'L');
		$pdf->Cell(95, 6, 'Panier moyen: ' . number_format($financialStats['panier_moyen'], 0, ',', ' ') . ' FCFA', 0, 1, 'L');

		$pdf->Ln(5);

		$pdf->Cell(95, 6, 'Valeur stock (achat): ' . number_format($financialStats['valeur_stock_achat'], 0, ',', ' ') . ' FCFA', 0, 0, 'L');
		$pdf->Cell(95, 6, 'Valeur stock (vente): ' . number_format($financialStats['valeur_stock_vente'], 0, ',', ' ') . ' FCFA', 0, 1, 'L');

		$margePotentielle = $financialStats['valeur_stock_vente'] - $financialStats['valeur_stock_achat'];
		$pdf->Cell(95, 6, 'Marge potentielle: ' . number_format($margePotentielle, 0, ',', ' ') . ' FCFA', 0, 1, 'L');

		$pdf->Ln(8);

		// Section 3: Top Articles
		if ($topArticles->count() > 0) {
			$pdf->SetFont('Arial', 'B', 14);
			$pdf->Cell(0, 8, '3. TOP ARTICLES (' . $dateRange . ' derniers jours)', 0, 1, 'L');

			$pdf->SetFont('Arial', 'B', 9);
			$pdf->SetFillColor(230, 230, 230);
			$pdf->Cell(80, 6, 'Article', 1, 0, 'C', true);
			$pdf->Cell(30, 6, 'Qte Vendue', 1, 0, 'C', true);
			$pdf->Cell(40, 6, 'CA (FCFA)', 1, 0, 'C', true);
			$pdf->Cell(40, 6, 'Benefice (FCFA)', 1, 1, 'C', true);

			$pdf->SetFont('Arial', '', 8);
			foreach ($topArticles as $article) {
				$pdf->Cell(80, 5, $this->truncateText($article->designation, 45), 1, 0, 'L');
				$pdf->Cell(30, 5, number_format($article->quantite_vendue), 1, 0, 'C');
				$pdf->Cell(40, 5, number_format($article->chiffre_affaires, 0, ',', ' '), 1, 0, 'R');
				$pdf->Cell(40, 5, number_format($article->benefice, 0, ',', ' '), 1, 1, 'R');
			}
			$pdf->Ln(5);
		}

		// Section 4: Top Clients
		if ($topClients->count() > 0) {
			$pdf->SetFont('Arial', 'B', 14);
			$pdf->Cell(0, 8, '4. TOP CLIENTS (' . $dateRange . ' derniers jours)', 0, 1, 'L');

			$pdf->SetFont('Arial', 'B', 9);
			$pdf->SetFillColor(230, 230, 230);
			$pdf->Cell(80, 6, 'Client', 1, 0, 'C', true);
			$pdf->Cell(30, 6, 'Nb Ventes', 1, 0, 'C', true);
			$pdf->Cell(40, 6, 'Telephone', 1, 0, 'C', true);
			$pdf->Cell(40, 6, 'Total (FCFA)', 1, 1, 'C', true);

			$pdf->SetFont('Arial', '', 8);
			foreach ($topClients as $client) {
				$pdf->Cell(80, 5, $this->truncateText($client->nom, 45), 1, 0, 'L');
				$pdf->Cell(30, 5, number_format($client->ventes_count), 1, 0, 'C');
				$pdf->Cell(40, 5, $client->telephone ?: 'N/A', 1, 0, 'C');
				$pdf->Cell(40, 5, number_format($client->ventes_sum_total, 0, ',', ' '), 1, 1, 'R');
			}
			$pdf->Ln(5);
		}

		// Nouvelle page pour les alertes stock
		if ($stockFaible->count() > 0 || $ruptureStock->count() > 0) {
			$pdf->AddPage();

			$pdf->SetFont('Arial', 'B', 14);
			$pdf->Cell(0, 8, '5. ALERTES STOCK', 0, 1, 'L');

			// Stock faible
			if ($stockFaible->count() > 0) {
				$pdf->SetFont('Arial', 'B', 12);
				$pdf->Cell(0, 6, 'Stock Faible (<= 10 unites)', 0, 1, 'L');

				$pdf->SetFont('Arial', 'B', 9);
				$pdf->SetFillColor(255, 235, 59);
				$pdf->Cell(60, 6, 'Article', 1, 0, 'C', true);
				$pdf->Cell(30, 6, 'Lot', 1, 0, 'C', true);
				$pdf->Cell(40, 6, 'Ville', 1, 0, 'C', true);
				$pdf->Cell(30, 6, 'Stock', 1, 0, 'C', true);
				$pdf->Cell(30, 6, 'Prix Vente', 1, 1, 'C', true);

				$pdf->SetFont('Arial', '', 8);
				foreach ($stockFaible as $lot) {
					$pdf->Cell(60, 5, $this->truncateText($lot->article->designation, 35), 1, 0, 'L');
					$pdf->Cell(30, 5, $lot->numero_lot, 1, 0, 'C');
					$pdf->Cell(40, 5, $this->truncateText($lot->ville->nom, 25), 1, 0, 'L');
					$pdf->Cell(30, 5, $lot->quantite_restante . '/' . $lot->quantite_initiale, 1, 0, 'C');
					$pdf->Cell(30, 5, number_format($lot->prix_vente, 0, ',', ' '), 1, 1, 'R');
				}
				$pdf->Ln(5);
			}

			// Rupture de stock
			if ($ruptureStock->count() > 0) {
				$pdf->SetFont('Arial', 'B', 12);
				$pdf->Cell(0, 6, 'Rupture de Stock', 0, 1, 'L');

				$pdf->SetFont('Arial', 'B', 9);
				$pdf->SetFillColor(255, 193, 193);
				$pdf->Cell(95, 6, 'Article', 1, 0, 'C', true);
				$pdf->Cell(95, 6, 'Description', 1, 1, 'C', true);

				$pdf->SetFont('Arial', '', 8);
				foreach ($ruptureStock as $article) {
					$pdf->Cell(95, 5, $this->truncateText($article->designation, 55), 1, 0, 'L');
					$pdf->Cell(95, 5, $this->truncateText($article->description ?: 'Aucune description', 55), 1, 1, 'L');
				}
			}
		}

		// Pied de page final
		$pdf->Ln(10);
		$pdf->SetFont('Arial', 'I', 8);
		$pdf->Cell(0, 5, 'Rapport genere automatiquement par le systeme de gestion commerciale', 0, 1, 'C');
		$pdf->Cell(0, 5, 'Date de generation: ' . date('d/m/Y H:i:s'), 0, 1, 'C');

		// Générer le nom du fichier
		$filename = 'rapport_global_' . date('Y-m-d_H-i-s') . '.pdf';

		// Retourner le PDF
		return response($pdf->Output('S'), 200, [
			'Content-Type' => 'application/pdf',
			'Content-Disposition' => 'attachment; filename="' . $filename . '"',
		]);
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
