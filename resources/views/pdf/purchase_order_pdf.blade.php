<!doctype html>
<html lang="fr">
<head><meta charset="utf-8">@include('pdf.partials.style')</head>
<body>
<div class="brand-bar"></div>
@if(config("stockflow.plans.{$order->company->subscription_plan}.pdf_watermark"))<div class="watermark">StockFlow Maroc</div>@endif
<div class="header">
    <div>
        <img class="logo" src="{{ public_path('images/stockflow-logo.svg') }}" alt="StockFlow Maroc">
        <h1>Bon d'achat</h1>
        <strong>{{ $order->company->name }}</strong><br>
        ICE: {{ $order->company->ice ?: '-' }}<br>
        {{ $order->company->address }} {{ $order->company->city }}
    </div>
    <div class="right">
        <span class="doc-meta">
            <strong>{{ $order->reference }}</strong><br>
            Date: {{ $order->order_date?->format('d/m/Y') }}<br>
            Fournisseur: {{ $order->supplier?->name ?: 'Non renseigné' }}
        </span>
    </div>
</div>
<table>
    <thead><tr><th>Produit</th><th>Qté</th><th>PU</th><th>TVA</th><th>Total TTC</th></tr></thead>
    <tbody>
    @foreach($order->lines as $line)
        <tr><td>{{ $line->product->name }}</td><td>{{ $line->quantity }}</td><td>{{ number_format($line->unit_price, 2) }}</td><td>{{ $line->tax_rate }}%</td><td>{{ number_format($line->total_ttc, 2) }}</td></tr>
    @endforeach
    </tbody>
</table>
<table class="totals">
    <tr><td>Total HT</td><td class="right">{{ number_format($order->total_ht, 2) }} MAD</td></tr>
    <tr><td>TVA</td><td class="right">{{ number_format($order->tax_amount, 2) }} MAD</td></tr>
    <tr><td><strong>Total TTC</strong></td><td class="right"><strong>{{ number_format($order->total_ttc, 2) }} MAD</strong></td></tr>
</table>
<p class="muted">{{ $order->notes }}</p>
<div class="footer">Document généré par StockFlow Maroc</div>
</body>
</html>
