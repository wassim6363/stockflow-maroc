<section class="stockflow-dashboard-heading">
    <div>
        <p>Bonjour, {{ auth()->user()?->name ?? 'Super Admin' }}</p>
        <h1>Tableau de bord Stock</h1>
        <span>Vue consolidée de votre stock, ventes, achats et alertes.</span>
    </div>

    <nav aria-label="Actions rapides">
        <a class="stockflow-action stockflow-action-blue" href="{{ url('/admin/sales-orders') }}">
            <x-heroicon-o-plus />
            Nouvelle vente
        </a>
        <a class="stockflow-action stockflow-action-green" href="{{ url('/admin/purchase-orders') }}">
            <x-heroicon-o-plus />
            Nouvel achat
        </a>
        <a class="stockflow-action stockflow-action-purple" href="{{ url('/admin/products') }}">
            <x-heroicon-o-plus />
            Ajouter un produit
        </a>
        <a class="stockflow-action stockflow-action-light" href="{{ url('/admin/inventory-counts') }}">
            <x-heroicon-o-building-storefront />
            Inventaire
        </a>
    </nav>
</section>
