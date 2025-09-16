<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport État du Stock</title>
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
            border-bottom: 2px solid #f59e0b;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #f59e0b;
        }
        .report-title {
            font-size: 20px;
            margin-top: 10px;
            color: #374151;
        }
        .alert-section {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 30px;
        }
        .alert-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 10px;
        }
        .stock-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .stock-table th, .stock-table td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
        }
        .stock-table th {
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
        .status-alert {
            background: #fecaca;
            color: #991b1b;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .status-warning {
            background: #fed7aa;
            color: #9a3412;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .status-ok {
            background: #bbf7d0;
            color: #166534;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin: 30px 0 15px 0;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Lovely App</div>
        <div class="report-title">Rapport État du Stock</div>
        <div style="font-size: 14px; color: #6b7280; margin-top: 5px;">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>

    <!-- Alertes critiques -->
    @if($lotsEnRupture->count() > 0 || $lotsExpires->count() > 0)
    <div class="alert-section">
        <div class="alert-title">⚠️ Alertes Critiques</div>
        @if($lotsEnRupture->count() > 0)
            <p><strong>{{ $lotsEnRupture->count() }}</strong> lot(s) en rupture de stock ou sous le seuil d'alerte</p>
        @endif
        @if($lotsExpires->count() > 0)
            <p><strong>{{ $lotsExpires->count() }}</strong> lot(s) expirés</p>
        @endif
        @if($lotsProchesExpiration->count() > 0)
            <p><strong>{{ $lotsProchesExpiration->count() }}</strong> lot(s) expirent dans les 30 prochains jours</p>
        @endif
    </div>
    @endif

    <div class="section-title">Inventaire Complet</div>
    
    <table class="stock-table">
        <thead>
            <tr>
                <th>Article</th>
                <th>N° Lot</th>
                <th>Ville</th>
                <th class="text-right">Qté Restante</th>
                <th class="text-right">Seuil Alerte</th>
                <th>Date Expiration</th>
                <th class="text-center">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lots as $lot)
            <tr>
                <td>{{ $lot->article->designation }}</td>
                <td>{{ $lot->numero_lot }}</td>
                <td>{{ $lot->ville->nom ?? 'N/A' }}</td>
                <td class="text-right">{{ $lot->quantite_restante }}</td>
                <td class="text-right">{{ $lot->seuil_alerte }}</td>
                <td>{{ $lot->date_expiration ? $lot->date_expiration->format('d/m/Y') : 'N/A' }}</td>
                <td class="text-center">
                    @if($lot->date_expiration && $lot->date_expiration <= now())
                        <span class="status-alert">EXPIRÉ</span>
                    @elseif($lot->quantite_restante <= $lot->seuil_alerte)
                        <span class="status-alert">RUPTURE</span>
                    @elseif($lot->date_expiration && $lot->date_expiration <= now()->addDays(30))
                        <span class="status-warning">EXPIRE BIENTÔT</span>
                    @else
                        <span class="status-ok">OK</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($lotsEnRupture->count() > 0)
    <div class="section-title">Lots en Rupture de Stock</div>
    <table class="stock-table">
        <thead>
            <tr>
                <th>Article</th>
                <th>N° Lot</th>
                <th class="text-right">Qté Restante</th>
                <th class="text-right">Seuil Alerte</th>
                <th>Action Recommandée</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lotsEnRupture as $lot)
            <tr>
                <td>{{ $lot->article->designation }}</td>
                <td>{{ $lot->numero_lot }}</td>
                <td class="text-right">{{ $lot->quantite_restante }}</td>
                <td class="text-right">{{ $lot->seuil_alerte }}</td>
                <td>Réapprovisionnement urgent</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($lotsExpires->count() > 0 || $lotsProchesExpiration->count() > 0)
    <div class="section-title">Lots avec Problèmes d'Expiration</div>
    <table class="stock-table">
        <thead>
            <tr>
                <th>Article</th>
                <th>N° Lot</th>
                <th class="text-right">Qté Restante</th>
                <th>Date Expiration</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lotsExpires->concat($lotsProchesExpiration) as $lot)
            <tr>
                <td>{{ $lot->article->designation }}</td>
                <td>{{ $lot->numero_lot }}</td>
                <td class="text-right">{{ $lot->quantite_restante }}</td>
                <td>{{ $lot->date_expiration->format('d/m/Y') }}</td>
                <td>
                    @if($lot->date_expiration <= now())
                        <span class="status-alert">EXPIRÉ</span>
                    @else
                        <span class="status-warning">EXPIRE DANS {{ $lot->date_expiration->diffInDays(now()) }} JOURS</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Lovely App - Système de Gestion Commerciale</p>
    </div>
</body>
</html>
