<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $vente->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .client-info, .invoice-details {
            width: 48%;
        }
        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            color: #3b82f6;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-left: auto;
            width: 300px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .total-row.final {
            font-weight: bold;
            font-size: 18px;
            border-top: 2px solid #3b82f6;
            padding-top: 12px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Lovely App</div>
        <div>Système de Gestion Commerciale</div>
    </div>

    <div class="invoice-info">
        <div class="client-info">
            <div class="section-title">Informations Client</div>
            <div><strong>Nom:</strong> {{ $vente->client->nom }}</div>
            @if($vente->client->telephone)
                <div><strong>Téléphone:</strong> {{ $vente->client->telephone }}</div>
            @endif
            @if($vente->client->email)
                <div><strong>Email:</strong> {{ $vente->client->email }}</div>
            @endif
        </div>
        
        <div class="invoice-details">
            <div class="section-title">Détails Facture</div>
            <div><strong>N° Facture:</strong> #{{ $vente->id }}</div>
            <div><strong>Date:</strong> {{ $vente->created_at->format('d/m/Y H:i') }}</div>
            <div><strong>Vendeur:</strong> {{ $vente->utilisateur->name }}</div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Article</th>
                <th>N° Lot</th>
                <th class="text-right">Quantité</th>
                <th class="text-right">Prix Unitaire</th>
                <th class="text-right">Remise</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vente->ligneVentes as $ligne)
            <tr>
                <td>{{ $ligne->lot->article->designation }}</td>
                <td>{{ $ligne->lot->numero_lot }}</td>
                <td class="text-right">{{ $ligne->quantite }}</td>
                <td class="text-right">{{ number_format($ligne->prix_unitaire, 2) }} €</td>
                <td class="text-right">{{ number_format($ligne->remise, 2) }} €</td>
                <td class="text-right">{{ number_format(($ligne->prix_unitaire * $ligne->quantite) - $ligne->remise, 2) }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="total-row">
            <span>Sous-total:</span>
            <span>{{ number_format($vente->ligneVentes->sum(fn($l) => ($l->prix_unitaire * $l->quantite) - $l->remise), 2) }} €</span>
        </div>
        @if($vente->remise_totale > 0)
        <div class="total-row">
            <span>Remise supplémentaire:</span>
            <span>-{{ number_format($vente->remise_totale, 2) }} €</span>
        </div>
        @endif
        <div class="total-row final">
            <span>Total à payer:</span>
            <span>{{ number_format($vente->total, 2) }} €</span>
        </div>
        <div class="total-row">
            <span>Montant payé:</span>
            <span>{{ number_format($vente->montant_paye, 2) }} €</span>
        </div>
        @if($vente->montant_restant > 0)
        <div class="total-row" style="color: #dc2626;">
            <span>Restant dû:</span>
            <span>{{ number_format($vente->montant_restant, 2) }} €</span>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Merci pour votre confiance !</p>
        <p>Facture générée le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
