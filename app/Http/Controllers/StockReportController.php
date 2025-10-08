<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockReportController extends Controller
{
	/**
	 * Afficher le rapport de stock
	 */
	public function index()
	{
		// Métriques de stock
		$stockMetrics = $this->getStockMetrics();

		// Données pour les graphiques
		$stockByCategory = $this->getStockByCategory();
		$lowStockProducts = $this->getLowStockProducts();
		$expiringProducts = $this->getExpiringProducts();

		return view('pages.reports.stock', compact(
			'stockMetrics',
			'stockByCategory',
			'lowStockProducts',
			'expiringProducts'
		));
	}

	/**
	 * Exporter le rapport de stock en PDF
	 */
	public function exportPdf(Request $request)
	{
		// Récupérer les données de stock
		$stockMetrics = $this->getStockMetrics();
		$stockByCategory = $this->getStockByCategory();
		$lowStockProducts = $this->getLowStockProducts();
		$expiringProducts = $this->getExpiringProducts();

		// Créer le PDF avec FPDF
		$pdf = new \FPDF('P', 'mm', 'A4');
		$pdf->AddPage();
		$pdf->SetFont('Arial', 'B', 18);

		// En-tête principal
		$appSettings = \App\Models\AppSetting::getInstance();
		$companyName = $appSettings->company_name ?? $appSettings->app_name;
		$currencySymbol = $appSettings->currency_symbol ?? '$';

		$pdf->Cell(0, 12, 'RAPPORT DE STOCK - ' . $companyName, 0, 1, 'C');
		$pdf->SetFont('Arial', '', 10);
		$pdf->Cell(0, 6, 'Genere le ' . date('d/m/Y a H:i'), 0, 1, 'C');
		$pdf->Ln(8);

		// Section 1: Métriques de stock principales
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->Cell(0, 8, '1. METRIQUES DE STOCK', 0, 1, 'L');
		$pdf->SetFont('Arial', '', 10);

		$pdf->Cell(95, 6, 'Total des produits: ' . number_format($stockMetrics['total_products']), 0, 0, 'L');
		$pdf->Cell(95, 6, 'Total du stock: ' . number_format($stockMetrics['total_stock']) . ' unites', 0, 1, 'L');

		$pdf->Cell(95, 6, 'Valeur du stock: ' . number_format($stockMetrics['stock_value'], 0, ',', ' ') . ' ' . $currencySymbol, 0, 0, 'L');
		$pdf->Cell(95, 6, 'Stock moyen: ' . number_format($stockMetrics['average_stock'], 1) . ' unites', 0, 1, 'L');

		$pdf->Cell(95, 6, 'Produits en rupture: ' . number_format($stockMetrics['out_of_stock']), 0, 0, 'L');
		$pdf->Cell(95, 6, 'Produits en stock bas: ' . number_format($stockMetrics['low_stock']), 0, 1, 'L');

		$pdf->Ln(8);

		// Section 2: Produits en stock bas
		if (!empty($lowStockProducts)) {
			$pdf->SetFont('Arial', 'B', 14);
			$pdf->Cell(0, 8, '2. PRODUITS EN STOCK BAS', 0, 1, 'L');

			$pdf->SetFont('Arial', 'B', 9);
			$pdf->SetFillColor(240, 240, 240);
			$pdf->Cell(100, 6, 'Produit', 1, 0, 'C', true);
			$pdf->Cell(30, 6, 'Quantite', 1, 0, 'C', true);
			$pdf->Cell(30, 6, 'Seuil', 1, 1, 'C', true);

			$pdf->SetFont('Arial', '', 8);
			foreach ($lowStockProducts as $product) {
				$pdf->Cell(100, 5, $this->truncateText($product['designation'], 50), 1, 0, 'L');
				$pdf->Cell(30, 5, number_format($product['stock_quantity']), 1, 0, 'C');
				$pdf->Cell(30, 5, number_format($product['seuil_alerte']), 1, 1, 'C');
			}
			$pdf->Ln(5);
		}

		// Section 3: Produits proches de l'expiration
		if (!empty($expiringProducts)) {
			$pdf->SetFont('Arial', 'B', 14);
			$pdf->Cell(0, 8, '3. PRODUITS PROCHES DE L\'EXPIRATION', 0, 1, 'L');

			$pdf->SetFont('Arial', 'B', 9);
			$pdf->SetFillColor(240, 240, 240);
			$pdf->Cell(80, 6, 'Produit', 1, 0, 'C', true);
			$pdf->Cell(40, 6, 'N° Lot', 1, 0, 'C', true);
			$pdf->Cell(40, 6, 'Date Expiration', 1, 1, 'C', true);

			$pdf->SetFont('Arial', '', 8);
			foreach ($expiringProducts as $product) {
				$pdf->Cell(80, 5, $this->truncateText($product['designation'], 40), 1, 0, 'L');
				$pdf->Cell(40, 5, $product['numero_lot'], 1, 0, 'C');
				$pdf->Cell(40, 5, date('d/m/Y', strtotime($product['date_expiration'])), 1, 1, 'C');
			}
			$pdf->Ln(5);
		}

		// Nouvelle page pour les détails supplémentaires
		$pdf->AddPage();

		// Section 4: Répartition par catégorie
		if (!empty($stockByCategory)) {
			$pdf->SetFont('Arial', 'B', 14);
			$pdf->Cell(0, 8, '4. REPARTITION DU STOCK', 0, 1, 'L');

			$pdf->SetFont('Arial', 'B', 9);
			$pdf->SetFillColor(240, 240, 240);
			$pdf->Cell(80, 6, 'Categorie', 1, 0, 'C', true);
			$pdf->Cell(40, 6, 'Nb Produits', 1, 0, 'C', true);
			$pdf->Cell(40, 6, 'Stock Total', 1, 1, 'C', true);

			$pdf->SetFont('Arial', '', 8);
			foreach ($stockByCategory as $category) {
				$pdf->Cell(80, 5, $category['category'], 1, 0, 'L');
				$pdf->Cell(40, 5, number_format($category['product_count']), 1, 0, 'C');
				$pdf->Cell(40, 5, number_format($category['total_stock']), 1, 1, 'C');
			}
		}

		// Pied de page final
		$pdf->Ln(10);
		$pdf->SetFont('Arial', 'I', 8);
		$pdf->Cell(0, 5, 'Rapport genere automatiquement par ' . $companyName, 0, 1, 'C');
		$pdf->Cell(0, 5, 'Date de generation: ' . date('d/m/Y H:i:s'), 0, 1, 'C');

		// Générer le nom du fichier
		$filename = 'rapport_stock_' . date('Y-m-d') . '.pdf';

		// Retourner le PDF
		return response($pdf->Output('S'), 200, [
			'Content-Type' => 'application/pdf',
			'Content-Disposition' => 'attachment; filename="' . $filename . '"',
		]);
	}

	/**
	 * Récupérer les métriques de stock générales
	 */
	private function getStockMetrics()
	{
		$totalProducts = Article::where('actif', true)->count();

		// Total du stock (somme des quantités de tous les lots)
		$totalStock = Lot::sum('quantite');

		// Valeur du stock (quantité * prix d'achat)
		$stockValue = Lot::selectRaw('SUM(quantite * prix_achat) as value')->first()->value ?? 0;

		// Stock moyen par produit
		$averageStock = $totalProducts > 0 ? $totalStock / $totalProducts : 0;

		// Seuil d'alerte depuis les paramètres
		$appSettings = \App\Models\AppSetting::getInstance();
		$lowStockThreshold = $appSettings->low_stock_threshold ?? 10;

		// Produits en rupture de stock (stock = 0)
		$outOfStock = Article::whereDoesntHave('lots')->count();

		// Produits en stock bas (stock < seuil d'alerte)
		$lowStock = Article::whereHas('lots', function ($query) use ($lowStockThreshold) {
			$query->havingRaw('SUM(quantite) < ?', [$lowStockThreshold]);
		})->count();

		return [
			'total_products' => $totalProducts,
			'total_stock' => $totalStock,
			'stock_value' => $stockValue,
			'average_stock' => $averageStock,
			'out_of_stock' => $outOfStock,
			'low_stock' => $lowStock,
		];
	}

	/**
	 * Récupérer la répartition du stock par catégorie/statut
	 */
	private function getStockByCategory()
	{
		// Grouper par statut actif/inactif
		$categories = Article::select('actif')
			->selectRaw('COUNT(*) as product_count')
			->selectRaw('(SELECT COALESCE(SUM(quantite), 0) FROM lots WHERE lots.article_id = articles.id) as total_stock')
			->groupBy('actif')
			->get();

		$result = [];
		foreach ($categories as $cat) {
			$result[] = [
				'category' => $cat->actif ? 'Produits Actifs' : 'Produits Inactifs',
				'product_count' => $cat->product_count,
				'total_stock' => $cat->total_stock ?? 0
			];
		}

		return $result;
	}

	/**
	 * Récupérer les produits en stock bas
	 */
	private function getLowStockProducts()
	{
		$appSettings = \App\Models\AppSetting::getInstance();
		$lowStockThreshold = $appSettings->low_stock_threshold ?? 10;

		return Article::select('articles.id', 'articles.designation')
			->selectRaw('COALESCE(SUM(lots.quantite), 0) as stock_quantity')
			->selectRaw('? as seuil_alerte', [$lowStockThreshold])
			->leftJoin('lots', 'articles.id', '=', 'lots.article_id')
			->where('articles.actif', true)
			->groupBy('articles.id', 'articles.designation')
			->havingRaw('stock_quantity > 0 AND stock_quantity < ?', [$lowStockThreshold])
			->orderBy('stock_quantity', 'asc')
			->limit(20)
			->get()
			->toArray();
	}

	/**
	 * Récupérer les produits proches de l'expiration (30 jours)
	 */
	private function getExpiringProducts()
	{
		return Lot::select('lots.numero_lot', 'lots.date_expiration', 'articles.designation')
			->join('articles', 'lots.article_id', '=', 'articles.id')
			->where('lots.date_expiration', '<=', Carbon::now()->addDays(30))
			->where('lots.date_expiration', '>=', Carbon::now())
			->where('lots.quantite', '>', 0)
			->orderBy('lots.date_expiration', 'asc')
			->limit(20)
			->get()
			->toArray();
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
