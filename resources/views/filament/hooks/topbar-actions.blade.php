@php
    $user = auth()->user();
    $company = $user?->company?->name ?? 'StockFlow Demo SARL';
    $plan = $user?->company?->subscription_plan ? 'Plan ' . ucfirst($user->company->subscription_plan) : 'Plan Pro';
@endphp

<div class="stockflow-topbar-actions">
    <button type="button" class="stockflow-icon-btn" aria-label="Theme">
        <x-heroicon-o-sun />
    </button>

    <div class="stockflow-company-switcher">
        <div>
            <strong>{{ $company }}</strong>
            <span>{{ $plan }}</span>
        </div>
        <x-heroicon-o-chevron-down />
    </div>

    <button type="button" class="stockflow-icon-btn stockflow-bell" aria-label="Notifications">
        <x-heroicon-o-bell />
        <span>5</span>
    </button>
</div>
