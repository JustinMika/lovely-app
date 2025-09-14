<?php

namespace App\Services;

use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Article;
use App\Models\Lot;
use App\Services\StockFifoService;

class CartService
{
	protected $stockFifoService;

	public function __construct(StockFifoService $stockFifoService)
	{
		$this->stockFifoService = $stockFifoService;
	}

	/**
	 * Ajouter un article au panier avec vérification de stock
	 */
	public function ajouterArticle(int $articleId, int $quantite, ?float $remise = 0): array
	{
		$article = Article::findOrFail($articleId);

		// Vérifier la disponibilité du stock
		if (!$this->stockFifoService->verifierDisponibilite($articleId, $quantite)) {
			$stockDisponible = $this->stockFifoService->getStockDisponible($articleId);
			throw new \Exception("Stock insuffisant pour {$article->designation}. Disponible: {$stockDisponible}");
		}

		// Calculer les prix avec FIFO
		$prixInfo = $this->stockFifoService->calculerPrixMoyenPondere($articleId, $quantite);

		$prixUnitaire = $prixInfo['prix_vente_moyen'];
		$prixTotal = $prixUnitaire * $quantite;
		$remiseTotal = ($prixTotal * $remise) / 100;
		$prixFinal = $prixTotal - $remiseTotal;

		// Vérifier si l'article existe déjà dans le panier
		$itemExistant = Cart::get($articleId);

		if ($itemExistant) {
			$nouvelleQuantite = $itemExistant->quantity + $quantite;

			// Vérifier le stock pour la nouvelle quantité
			if (!$this->stockFifoService->verifierDisponibilite($articleId, $nouvelleQuantite)) {
				$stockDisponible = $this->stockFifoService->getStockDisponible($articleId);
				throw new \Exception("Stock insuffisant pour {$article->designation}. Disponible: {$stockDisponible}, Total demandé: {$nouvelleQuantite}");
			}

			// Recalculer les prix pour la nouvelle quantité
			$nouveauxPrixInfo = $this->stockFifoService->calculerPrixMoyenPondere($articleId, $nouvelleQuantite);
			$nouveauPrixUnitaire = $nouveauxPrixInfo['prix_vente_moyen'];

			Cart::update($articleId, [
				'quantity' => $nouvelleQuantite,
				'price' => $nouveauPrixUnitaire,
				'attributes' => [
					'prix_achat_moyen' => $nouveauxPrixInfo['prix_achat_moyen'],
					'remise_pourcentage' => $remise,
					'lots_utilises' => $nouveauxPrixInfo['lots_utilises']
				]
			]);
		} else {
			// Ajouter nouvel article au panier
			Cart::add([
				'id' => $articleId,
				'name' => $article->designation,
				'price' => $prixUnitaire,
				'quantity' => $quantite,
				'attributes' => [
					'prix_achat_moyen' => $prixInfo['prix_achat_moyen'],
					'remise_pourcentage' => $remise,
					'lots_utilises' => $prixInfo['lots_utilises'],
					'description' => $article->description
				]
			]);
		}

		return [
			'success' => true,
			'message' => "Article {$article->designation} ajouté au panier",
			'item' => Cart::get($articleId),
			'total_panier' => Cart::getTotal()
		];
	}

	/**
	 * Mettre à jour la quantité d'un article dans le panier
	 */
	public function mettreAJourQuantite(int $articleId, int $nouvelleQuantite): array
	{
		$article = Article::findOrFail($articleId);

		if ($nouvelleQuantite <= 0) {
			return $this->retirerArticle($articleId);
		}

		// Vérifier la disponibilité du stock
		if (!$this->stockFifoService->verifierDisponibilite($articleId, $nouvelleQuantite)) {
			$stockDisponible = $this->stockFifoService->getStockDisponible($articleId);
			throw new \Exception("Stock insuffisant pour {$article->designation}. Disponible: {$stockDisponible}");
		}

		// Recalculer les prix avec FIFO
		$prixInfo = $this->stockFifoService->calculerPrixMoyenPondere($articleId, $nouvelleQuantite);

		Cart::update($articleId, [
			'quantity' => $nouvelleQuantite,
			'price' => $prixInfo['prix_vente_moyen'],
			'attributes' => [
				'prix_achat_moyen' => $prixInfo['prix_achat_moyen'],
				'remise_pourcentage' => Cart::get($articleId)->attributes->remise_pourcentage ?? 0,
				'lots_utilises' => $prixInfo['lots_utilises']
			]
		]);

		return [
			'success' => true,
			'message' => "Quantité mise à jour pour {$article->designation}",
			'item' => Cart::get($articleId),
			'total_panier' => Cart::getTotal()
		];
	}

	/**
	 * Retirer un article du panier
	 */
	public function retirerArticle(int $articleId): array
	{
		$item = Cart::get($articleId);
		$nomArticle = $item ? $item->name : "Article";

		Cart::remove($articleId);

		return [
			'success' => true,
			'message' => "{$nomArticle} retiré du panier",
			'total_panier' => Cart::getTotal()
		];
	}

	/**
	 * Vider complètement le panier
	 */
	public function viderPanier(): array
	{
		Cart::clear();

		return [
			'success' => true,
			'message' => "Panier vidé",
			'total_panier' => 0
		];
	}

	/**
	 * Obtenir le contenu du panier
	 */
	public function getContenuPanier(): array
	{
		$items = Cart::getContent();
		$total = Cart::getTotal();
		$totalQuantite = Cart::getTotalQuantity();

		return [
			'items' => $items,
			'total' => $total,
			'total_quantite' => $totalQuantite,
			'nombre_articles' => $items->count()
		];
	}

	/**
	 * Appliquer une remise à un article du panier
	 */
	public function appliquerRemise(int $articleId, float $remisePourcentage): array
	{
		$item = Cart::get($articleId);
		if (!$item) {
			throw new \Exception("Article non trouvé dans le panier");
		}

		$prixOriginal = $item->price;
		$remiseDecimale = $remisePourcentage / 100;
		$nouveauPrix = $prixOriginal * (1 - $remiseDecimale);

		Cart::update($articleId, [
			'price' => $nouveauPrix,
			'attributes' => array_merge($item->attributes->toArray(), [
				'remise_pourcentage' => $remisePourcentage,
				'prix_original' => $prixOriginal
			])
		]);

		return [
			'success' => true,
			'message' => "Remise de {$remisePourcentage}% appliquée",
			'item' => Cart::get($articleId),
			'total_panier' => Cart::getTotal()
		];
	}

	/**
	 * Valider le panier avant finalisation de vente
	 */
	public function validerPanier(): array
	{
		$items = Cart::getContent();
		$erreurs = [];

		foreach ($items as $item) {
			$articleId = $item->id;
			$quantite = $item->quantity;

			if (!$this->stockFifoService->verifierDisponibilite($articleId, $quantite)) {
				$stockDisponible = $this->stockFifoService->getStockDisponible($articleId);
				$erreurs[] = "Stock insuffisant pour {$item->name}. Disponible: {$stockDisponible}, Demandé: {$quantite}";
			}
		}

		return [
			'valide' => empty($erreurs),
			'erreurs' => $erreurs,
			'total_panier' => Cart::getTotal()
		];
	}

	/**
	 * Obtenir les informations détaillées pour la facturation
	 */
	public function getInfosFacturation(): array
	{
		$items = Cart::getContent();
		$lignesVente = [];
		$totalGeneral = 0;
		$totalRemises = 0;

		foreach ($items as $item) {
			$prixUnitaire = $item->attributes->prix_original ?? $item->price;
			$remise = ($item->attributes->remise_pourcentage ?? 0);
			$montantRemise = ($prixUnitaire * $item->quantity * $remise) / 100;
			$sousTotal = ($prixUnitaire * $item->quantity) - $montantRemise;

			$lignesVente[] = [
				'article_id' => $item->id,
				'designation' => $item->name,
				'quantite' => $item->quantity,
				'prix_unitaire' => $prixUnitaire,
				'prix_achat_moyen' => $item->attributes->prix_achat_moyen ?? 0,
				'remise_pourcentage' => $remise,
				'montant_remise' => $montantRemise,
				'sous_total' => $sousTotal,
				'lots_utilises' => $item->attributes->lots_utilises ?? []
			];

			$totalGeneral += $sousTotal;
			$totalRemises += $montantRemise;
		}

		return [
			'lignes_vente' => $lignesVente,
			'total_general' => $totalGeneral,
			'total_remises' => $totalRemises,
			'nombre_articles' => count($lignesVente)
		];
	}
}
