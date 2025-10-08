<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $sale->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.5;
        }

        .container {
            padding: 30px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
        }

        .header-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }

        .header-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-info {
            color: #6b7280;
            font-size: 10px;
        }

        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .invoice-info {
            color: #6b7280;
            font-size: 10px;
        }

        .client-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .client-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .client-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .section-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 8px;
            color: #1f2937;
        }

        .client-name {
            font-weight: bold;
            margin-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead {
            background-color: #f9fafb;
        }

        th {
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #e5e7eb;
            font-size: 10px;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        .totals {
            margin-top: 20px;
            float: right;
            width: 300px;
        }

        .totals-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .totals-label {
            display: table-cell;
            text-align: left;
            color: #6b7280;
        }

        .totals-value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
        }

        .total-final {
            border-top: 2px solid #e5e7eb;
            padding-top: 8px;
            margin-top: 8px;
            font-size: 14px;
        }

        .status-section {
            clear: both;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .status-paid {
            display: inline-block;
            background-color: #dcfce7;
            color: #166534;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }

        .status-partial {
            display: inline-block;
            background-color: #fef3c7;
            color: #92400e;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #6b7280;
            font-size: 9px;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <div class="company-name">{{ $appSettings->company_name ?? $appSettings->app_name }}</div>
                <div class="company-info">
                    {{ $appSettings->app_description ?? 'Système de Gestion Commerciale' }}<br>
                    @if ($appSettings->company_address)
                        {{ $appSettings->company_address }}<br>
                    @endif
                    @if ($appSettings->company_phone)
                        Tél: {{ $appSettings->company_phone }}<br>
                    @endif
                    @if ($appSettings->company_email)
                        Email: {{ $appSettings->company_email }}
                    @endif
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-title">FACTURE</div>
                <div class="invoice-info">
                    N° {{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}<br>
                    Date: {{ date('d/m/Y à H:i', strtotime($sale->created_at)) }}
                </div>
            </div>
        </div>

        <!-- Client Information -->
        <div class="client-section">
            <div class="client-left">
                <div class="section-title">FACTURÉ À:</div>
                @if ($sale->client)
                    <div class="client-name">{{ $sale->client->nom }} {{ $sale->client->prenom }}</div>
                    @if ($sale->client->telephone)
                        Tél: {{ $sale->client->telephone }}<br>
                    @endif
                    @if ($sale->client->email)
                        Email: {{ $sale->client->email }}<br>
                    @endif
                    @if ($sale->client->adresse)
                        {{ $sale->client->adresse }}
                    @endif
                @else
                    <div class="client-name">Client non spécifié</div>
                @endif
            </div>
            <div class="client-right">
                <div class="section-title">VENDEUR:</div>
                <div class="client-name">{{ $sale->utilisateur->name ?? 'N/A' }}</div>
                Date de vente: {{ date('d/m/Y à H:i', strtotime($sale->created_at)) }}
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Lot</th>
                    <th class="text-right">Qté</th>
                    <th class="text-right">Prix unit.</th>
                    <th class="text-right">Remise</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->ligneVentes as $ligne)
                    <tr>
                        <td>{{ $ligne->article->designation }}</td>
                        <td>{{ $ligne->lot->numero_lot }}</td>
                        <td class="text-right">{{ number_format($ligne->quantite, 0, ',', ' ') }}</td>
                        <td class="text-right">{{ currency($ligne->prix_unitaire) }}</td>
                        <td class="text-right">{{ currency($ligne->remise_ligne) }}</td>
                        <td class="text-right"><strong>{{ currency($ligne->sous_total) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="totals-row">
                <div class="totals-label">Sous-total:</div>
                <div class="totals-value">{{ currency($sale->ligneVentes->sum('sous_total')) }}</div>
            </div>
            @if ($sale->remise_totale > 0)
                <div class="totals-row">
                    <div class="totals-label">Remise totale:</div>
                    <div class="totals-value" style="color: #dc2626;">-{{ currency($sale->remise_totale) }}</div>
                </div>
            @endif
            <div class="totals-row total-final">
                <div class="totals-label">TOTAL:</div>
                <div class="totals-value">{{ currency($sale->total) }}</div>
            </div>
            <div class="totals-row">
                <div class="totals-label">Montant payé:</div>
                <div class="totals-value" style="color: #16a34a;">{{ currency($sale->montant_paye) }}</div>
            </div>
            @php
                $resteAPayer = $sale->total - $sale->montant_paye;
            @endphp
            @if ($resteAPayer > 0)
                <div class="totals-row">
                    <div class="totals-label">Restant à payer:</div>
                    <div class="totals-value" style="color: #dc2626;">{{ currency($resteAPayer) }}</div>
                </div>
            @endif
        </div>

        <!-- Payment Status -->
        <div class="status-section">
            <div class="section-title">Statut du paiement:</div>
            @if ($sale->statut == 'payée')
                <span class="status-paid">✓ Payé intégralement</span>
            @elseif($sale->statut == 'partiellement_payée')
                <span class="status-partial">⚠ Paiement partiel</span>
            @else
                <span class="status-partial" style="background-color: #fee2e2; color: #991b1b;">✗ Impayée</span>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            Merci pour votre confiance ! Cette facture a été générée automatiquement par
            {{ $appSettings->app_name }}.<br>
            Pour toute question, veuillez nous
            contacter{{ $appSettings->company_phone ? ' au ' . $appSettings->company_phone : '' }}.
        </div>
    </div>
</body>

</html>
