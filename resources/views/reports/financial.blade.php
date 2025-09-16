<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Financier</title>
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
            border-bottom: 2px solid #10b981;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
        }
        .report-title {
            font-size: 20px;
            margin-top: 10px;
            color: #374151;
        }
        .period {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 20px;
        }
        .summary-card {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #6b7280;
            text-transform: uppercase;
        }
        .summary-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }
        .sales-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .sales-table th, .sales-table td {
            border: 1px solid #e5e7eb;
            padding: 12px;
            text-align: left;
        }
        .sales-table th {
            background-color: #f9fafb;
            font-weight: bold;
            color: #374151;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin: 30px 0 15px 0;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Lovely App</div>
        <div class="report-title">Rapport Financier</div>
        <div class="period">Période: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
    </div>

    <div class="summary-cards">
        <div class="summary-card">
            <h3>Total Revenus</h3>
            <div class="value">{{ number_format($totalRevenu, 2) }} €</div>
        </div>
        <div class="summary-card">
            <h3>Total Bénéfices</h3>
            <div class="value">{{ number_format($totalBenefice, 2) }} €</div>
        </div>
        <div class="summary-card">
            <h3>Nombre de Ventes</h3>
            <div class="value">{{ $nombreVentes }}</div>
        </div>
        <div class="summary-card">
            <h3>Panier Moyen</h3>
            <div class="value">{{ number_format($panierMoyen, 2) }} €</div>
        </div>
    </div>

    <div class="section-title">Détail des Ventes</div>
    
    @if($ventes->count() > 0)
    <table class="sales-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th>Vendeur</th>
                <th class="text-right">Total</th>
                <th class="text-right">Payé</th>
                <th class="text-right">Restant</th>
                <th class="text-right">Bénéfice</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventes as $vente)
            <tr>
                <td>{{ $vente->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $vente->client->nom }}</td>
                <td>{{ $vente->utilisateur->name }}</td>
                <td class="text-right">{{ number_format($vente->total, 2) }} €</td>
                <td class="text-right">{{ number_format($vente->montant_paye, 2) }} €</td>
                <td class="text-right">{{ number_format($vente->montant_restant, 2) }} €</td>
                <td class="text-right">{{ number_format($vente->benefice, 2) }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="text-center" style="padding: 40px; color: #6b7280;">Aucune vente trouvée pour cette période.</p>
    @endif

    <div class="footer">
        <p>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Lovely App - Système de Gestion Commerciale</p>
    </div>
</body>
</html>
