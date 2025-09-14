<?php

namespace App\Services;

use App\Models\Lot;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Exception;

class StockFifoService
{
	/**
	 * Obtient le stock disponible total pour un article
	 */
	public function getStockDisponible($articleId)
	{
		return Lot::where('article_id', $articleId)
			->where('quantite_restante', '>', 0)
			->sum('quantite_restante');
	}

	/**
	 * Obtient les lots disponibles pour un article triés par FIFO
	 */
	public function getLotsDisponibles($articleId)
	{
		return Lot::where('article_id', $articleId)
			->where('quantite_restante', '>', 0)
			->orderBy('date_arrivee', 'asc')
			->orderBy('id', 'asc')
			->get();
	}

	/**
	 * Vérifie si la quantité demandée est disponible en stock
	 */
	public function verifierDisponibilite($articleId, $quantiteDemandee)
	{
		$stockDisponible = $this->getStockDisponible($articleId);
		return $stockDisponible >= $quantiteDemandee;
	}

	/**
	 * Vérifie la disponibilité avec informations détaillées
	 */
	public function verifierDisponibiliteDetaille($articleId, $quantiteDemandee)
	{
		$stockDisponible = $this->getStockDisponible($articleId);

		return [
			'disponible' => $stockDisponible >= $quantiteDemandee,
			'stock_disponible' => $stockDisponible,
			'quantite_demandee' => $quantiteDemandee,
			'manquant' => max(0, $quantiteDemandee - $stockDisponible)
		];
	}

	/**
	 * Calcule la déduction de stock selon la méthode FIFO
	 * Retourne les informations des lots à utiliser sans modifier la base de données
	 */
	public function deduireStock($articleId, $quantiteDemandee)
	{
		// Vérifier la disponibilité
		$verification = $this->verifierDisponibiliteDetaille($articleId, $quantiteDemandee);

		if (!$verification['disponible']) {
			throw new Exception("Stock insuffisant pour l'article. Disponible: {$verification['stock_disponible']}, Demandé: {$quantiteDemandee}");
		}

		$lots = $this->getLotsDisponibles($articleId);
		$lotsUtilises = [];
		$quantiteRestante = $quantiteDemandee;

		foreach ($lots as $lot) {
			if ($quantiteRestante <= 0) {
				break;
			}

			$quantiteAPrendre = min($quantiteRestante, $lot->quantite_restante);

			$lotsUtilises[] = [
				'lot_id' => $lot->id,
				'numero_lot' => $lot->numero_lot,
				'quantite_deduite' => $quantiteAPrendre,
				'quantite_restante_avant' => $lot->quantite_restante,
				'quantite_restante_apres' => $lot->quantite_restante - $quantiteAPrendre,
				'prix_achat' => $lot->prix_achat,
				'prix_vente' => $lot->prix_vente,
				'date_arrivee' => $lot->date_arrivee,
				'article_id' => $articleId
			];

			$quantiteRestante -= $quantiteAPrendre;
		}

		if ($quantiteRestante > 0) {
			throw new Exception("Impossible de satisfaire la demande complète. Quantité manquante: {$quantiteRestante}");
		}

		return $lotsUtilises;
	}

	/**
	 * Applique effectivement la déduction de stock en base de données
	 */
	public function appliquerDeduction(array $lotsUtilises)
	{
		DB::transaction(function () use ($lotsUtilises) {
			foreach ($lotsUtilises as $lotUtilise) {
				$lot = Lot::findOrFail($lotUtilise['lot_id']);
				$lot->quantite_restante = $lotUtilise['quantite_restante_apres'];
				$lot->save();
			}
		});
	}

	/**
	 * Restaure le stock (utilisé lors d'annulation de vente)
	 */
	public function restaurerStock($lotId, $quantite)
	{
		$lot = Lot::findOrFail($lotId);
		$lot->quantite_restante += $quantite;
		$lot->save();
	}

	/**
	 * Obtient le prix de vente moyen pondéré pour un article selon FIFO
	 */
	public function getPrixVenteMoyenFifo($articleId, $quantite)
	{
		try {
			$lotsUtilises = $this->deduireStock($articleId, $quantite);

			$totalPrix = 0;
			$totalQuantite = 0;

			foreach ($lotsUtilises as $lot) {
				$totalPrix += $lot['prix_vente'] * $lot['quantite_deduite'];
				$totalQuantite += $lot['quantite_deduite'];
			}

			return $totalQuantite > 0 ? $totalPrix / $totalQuantite : 0;
		} catch (Exception $e) {
			return 0;
		}
	}

	/**
	 * Obtient les informations détaillées de stock pour un article
	 */
	public function getInfosStock($articleId)
	{
		$article = Article::findOrFail($articleId);
		$lots = $this->getLotsDisponibles($articleId);

		$stockTotal = $lots->sum('quantite_restante');
		$valeurStock = $lots->sum(function ($lot) {
			return $lot->quantite_restante * $lot->prix_achat;
		});

		return [
			'article' => $article,
			'stock_total' => $stockTotal,
			'valeur_stock' => $valeurStock,
			'nombre_lots' => $lots->count(),
			'lots' => $lots,
			'prix_vente_moyen' => $stockTotal > 0 ? $this->getPrixVenteMoyenFifo($articleId, $stockTotal) : 0
		];
	}

	/**
	 * Obtient les articles avec stock faible (alerte)
	 */
	public function getArticlesStockFaible($seuilAlerte = 10)
	{
		return Article::where('actif', true)
			->whereHas('lots', function ($query) use ($seuilAlerte) {
				$query->havingRaw('SUM(quantite_restante) <= ?', [$seuilAlerte]);
			})
			->with(['lots' => function ($query) {
				$query->where('quantite_restante', '>', 0);
			}])
			->get()
			->map(function ($article) {
				$stockTotal = $article->lots->sum('quantite_restante');
				$article->stock_total = $stockTotal;
				return $article;
			})
			->filter(function ($article) use ($seuilAlerte) {
				return $article->stock_total <= $seuilAlerte;
			});
	}

	/**
	 * Simule une vente pour voir l'impact sur le stock
	 */
	public function simulerVente($articleId, $quantite)
	{
		try {
			$lotsUtilises = $this->deduireStock($articleId, $quantite);

			return [
				'possible' => true,
				'lots_utilises' => $lotsUtilises,
				'cout_total' => array_sum(array_map(function ($lot) {
					return $lot['prix_achat'] * $lot['quantite_deduite'];
				}, $lotsUtilises)),
				'prix_vente_total' => array_sum(array_map(function ($lot) {
					return $lot['prix_vente'] * $lot['quantite_deduite'];
				}, $lotsUtilises)),
				'benefice_estime' => array_sum(array_map(function ($lot) {
					return ($lot['prix_vente'] - $lot['prix_achat']) * $lot['quantite_deduite'];
				}, $lotsUtilises))
			];
		} catch (Exception $e) {
			return [
				'possible' => false,
				'erreur' => $e->getMessage(),
				'stock_disponible' => $this->getStockDisponible($articleId)
			];
		}
	}
}
