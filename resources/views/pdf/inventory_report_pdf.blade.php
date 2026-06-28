<!doctype html>
<html lang="fr">
<head><meta charset="utf-8">@include('pdf.partials.style')</head>
<body>
<div class="brand-bar"></div>
<div class="header">
    <div>
        <img class="logo" src="{{ public_path('images/stockflow-logo.svg') }}" alt="StockFlow Maroc">
        <h1>Rapport inventaire</h1>
        <strong>{{ $count->company->name }}</strong><br>
        Entrepot: {{ $count->warehouse->name }}
    </div>
    <div class="right">
        <span class="doc-meta">
            <strong>{{ $count->reference }}</strong><br>
            Date: {{ $count->count_date?->format('d/m/Y') }}<br>
            Statut: {{ $count->status }}
        </span>
    </div>
</div>
<table>
    <thead><tr><th>Produit</th><th>Stock système</th><th>Stock compté</th><th>Différence</th><th>Notes</th></tr></thead>
    <tbody>
    @foreach($count->lines as $line)
        <tr><td>{{ $line->product->name }}</td><td>{{ $line->system_quantity }}</td><td>{{ $line->counted_quantity }}</td><td>{{ $line->difference }}</td><td>{{ $line->notes }}</td></tr>
    @endforeach
    </tbody>
</table>
<p class="muted">{{ $count->notes }}</p>
<div class="footer">Document généré par StockFlow Maroc</div>
</body>
</html>
