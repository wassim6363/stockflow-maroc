<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockFlow Maroc - SaaS gestion stock</title>
    <meta name="description" content="StockFlow Maroc aide les PME marocaines a suivre leur stock, ventes, achats et documents PDF.">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --sf-navy: #0f172a;
            --sf-blue: #2563eb;
            --sf-green: #10b981;
            --sf-orange: #f97316;
            --sf-red: #dc2626;
            --sf-bg: #f8fafc;
            --sf-border: #e2e8f0;
            --sf-text: #1e293b;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--sf-bg);
            color: var(--sf-text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .hero-scene {
            background:
                linear-gradient(120deg, rgba(15, 23, 42, .94), rgba(37, 99, 235, .82)),
                url("data:image/svg+xml,%3Csvg width='1440' height='820' viewBox='0 0 1440 820' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cg opacity='.42'%3E%3Crect x='878' y='119' width='360' height='210' rx='22' fill='%23FFFFFF'/%3E%3Crect x='916' y='160' width='166' height='18' rx='9' fill='%2393C5FD'/%3E%3Crect x='916' y='203' width='254' height='14' rx='7' fill='%23DBEAFE'/%3E%3Crect x='916' y='238' width='218' height='14' rx='7' fill='%23DBEAFE'/%3E%3Crect x='958' y='401' width='298' height='184' rx='22' fill='%23FFFFFF'/%3E%3Crect x='995' y='444' width='64' height='78' rx='10' fill='%2310B981'/%3E%3Crect x='1076' y='420' width='64' height='102' rx='10' fill='%232563EB'/%3E%3Crect x='1157' y='474' width='64' height='48' rx='10' fill='%23F97316'/%3E%3Crect x='646' y='230' width='248' height='248' rx='32' fill='%23FFFFFF'/%3E%3Cpath d='M711 315h118l36 42v80H675v-80l36-42Z' fill='%23DBEAFE'/%3E%3Cpath d='M711 315l59 42 59-42' stroke='%232563EB' stroke-width='12' stroke-linecap='round'/%3E%3Cpath d='M711 389h118' stroke='%2310B981' stroke-width='12' stroke-linecap='round'/%3E%3C/g%3E%3C/svg%3E");
            background-position: center;
            background-size: cover;
        }

        .metric-line {
            background: linear-gradient(90deg, var(--sf-blue), var(--sf-green));
        }
    </style>
</head>
<body>
    <header class="fixed inset-x-0 top-0 z-40 border-b border-white/10 bg-[#0f172a]/88 text-white backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6">
            <a href="/" class="flex items-center gap-3">
                <img src="/images/stockflow-logo.svg" alt="StockFlow Maroc" class="h-11 w-auto rounded-md bg-white/95 px-2 py-1">
            </a>
            <nav class="hidden items-center gap-7 text-sm font-semibold text-slate-200 lg:flex">
                <a href="#problemes" class="hover:text-white">Problemes</a>
                <a href="#solution" class="hover:text-white">Solution</a>
                <a href="#fonctionnalites" class="hover:text-white">Fonctionnalites</a>
                <a href="#pricing" class="hover:text-white">Tarifs</a>
                <a href="#faq" class="hover:text-white">FAQ</a>
            </nav>
            <a href="/admin" class="rounded-md bg-white px-4 py-2 text-sm font-extrabold text-[#0f172a] shadow-sm">Acceder au panel</a>
        </div>
    </header>

    <main>
        <section class="hero-scene min-h-[92vh] overflow-hidden pt-24 text-white">
            <div class="mx-auto grid min-h-[calc(92vh-6rem)] max-w-7xl items-center gap-10 px-4 pb-10 sm:px-6 lg:grid-cols-[1.02fr_.98fr]">
                <div class="max-w-3xl py-14">
                    <p class="mb-5 inline-flex rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-bold text-blue-100 backdrop-blur">SaaS gestion stock pour PME marocaines</p>
                    <h1 class="text-4xl font-black leading-tight tracking-normal sm:text-5xl lg:text-6xl">Gerez votre stock, vos ventes et vos achats en toute simplicite</h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-blue-50">StockFlow Maroc aide les entreprises marocaines a suivre leur inventaire, eviter les ruptures et generer leurs documents PDF en quelques clics.</p>
                    <div class="mt-9 flex flex-wrap gap-3">
                        <a href="https://wa.me/212600000000" class="rounded-md bg-[#10B981] px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-emerald-950/20">Demander une demo WhatsApp</a>
                        <a href="/admin" class="rounded-md border border-white/25 bg-white/10 px-5 py-3 text-sm font-extrabold text-white backdrop-blur">Acceder au panel</a>
                    </div>
                    <div class="mt-10 grid max-w-xl grid-cols-3 gap-3 text-sm">
                        <div>
                            <strong class="block text-2xl">MAD</strong>
                            <span class="text-blue-100">Devise locale</span>
                        </div>
                        <div>
                            <strong class="block text-2xl">PDF</strong>
                            <span class="text-blue-100">Bons propres</span>
                        </div>
                        <div>
                            <strong class="block text-2xl">SaaS</strong>
                            <span class="text-blue-100">Multi-societe</span>
                        </div>
                    </div>
                </div>

                <div class="relative hidden lg:block">
                    <div class="rounded-[2rem] border border-white/20 bg-white/92 p-5 text-[#1e293b] shadow-2xl shadow-slate-950/35">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                            <div>
                                <p class="text-xs font-bold uppercase text-slate-500">Tableau de bord</p>
                                <p class="text-xl font-black text-[#0f172a]">Stock central</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-black text-emerald-700">Pro</span>
                        </div>
                        <div class="mt-5 grid grid-cols-2 gap-4">
                            <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                                <span class="text-xs font-bold text-slate-500">Valeur stock</span>
                                <strong class="mt-2 block text-2xl text-[#0f172a]">184 250 MAD</strong>
                                <div class="mt-4 h-2 rounded-full bg-slate-100"><div class="metric-line h-2 w-4/5 rounded-full"></div></div>
                            </div>
                            <div class="rounded-md border border-orange-100 bg-orange-50 p-4 shadow-sm">
                                <span class="text-xs font-bold text-orange-700">Alertes</span>
                                <strong class="mt-2 block text-2xl text-[#f97316]">7</strong>
                                <p class="mt-3 text-sm text-orange-800">Produits sous minimum</p>
                            </div>
                        </div>
                        <div class="mt-4 rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="mb-4 flex items-center justify-between">
                                <p class="font-black text-[#0f172a]">Derniers mouvements</p>
                                <span class="text-xs font-bold text-blue-700">Temps reel</span>
                            </div>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center justify-between rounded-md bg-slate-50 px-3 py-2"><span>Achat - Shampoing Argan</span><strong class="text-emerald-600">+50</strong></div>
                                <div class="flex items-center justify-between rounded-md bg-slate-50 px-3 py-2"><span>Vente - Pack The</span><strong class="text-red-600">-8</strong></div>
                                <div class="flex items-center justify-between rounded-md bg-slate-50 px-3 py-2"><span>Inventaire - Depot principal</span><strong class="text-blue-600">+3</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="problemes" class="mx-auto max-w-7xl px-4 py-16 sm:px-6">
            <div class="max-w-2xl">
                <p class="text-sm font-black uppercase text-[#2563eb]">Avant StockFlow</p>
                <h2 class="mt-2 text-3xl font-black text-[#0f172a]">Les pertes se cachent dans les fichiers disperses</h2>
            </div>
            <div class="mt-8 grid gap-4 md:grid-cols-5">
                @foreach(['Stock gere sur cahier ou Excel', 'Ruptures non detectees', 'Prix et marges mal suivis', 'Bons de vente manuels', 'Pas de visibilite mouvements'] as $problem)
                    <div class="rounded-lg border border-slate-200 bg-white p-5 text-sm font-bold shadow-sm">{{ $problem }}</div>
                @endforeach
            </div>
        </section>

        <section id="solution" class="border-y border-slate-200 bg-white py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6">
                <div class="grid gap-10 lg:grid-cols-[.8fr_1.2fr] lg:items-start">
                    <div>
                        <p class="text-sm font-black uppercase text-[#10b981]">Solution</p>
                        <h2 class="mt-2 text-3xl font-black text-[#0f172a]">Une console claire pour piloter achat, vente et inventaire</h2>
                        <p class="mt-4 text-slate-600">Le panel centralise les produits, entrepots, clients, fournisseurs, documents PDF et alertes de stock bas.</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach([['Stock en temps reel', 'Suivi par depot et mouvements automatiques.'], ['Alertes rupture', 'Produits sous minimum visibles au dashboard.'], ['Achats et ventes', 'Bons coherents avec statuts colores.'], ['PDF professionnels', 'Documents propres pour clients et fournisseurs.']] as $item)
                            <div class="rounded-lg border border-slate-200 bg-[#f8fafc] p-5">
                                <h3 class="font-black text-[#0f172a]">{{ $item[0] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-600">{{ $item[1] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section id="fonctionnalites" class="mx-auto max-w-7xl px-4 py-16 sm:px-6">
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <p class="text-sm font-black uppercase text-[#2563eb]">Fonctionnalites MVP</p>
                    <h2 class="mt-2 text-3xl font-black text-[#0f172a]">Pret pour une vraie PME</h2>
                </div>
                <a href="/admin" class="w-fit rounded-md bg-[#0f172a] px-5 py-3 text-sm font-extrabold text-white">Voir le panel</a>
            </div>
            <div class="mt-8 grid gap-4 md:grid-cols-3">
                @foreach(['Produits, categories, clients et fournisseurs', 'Achats, ventes, inventaires et mouvements', 'Exports Excel, imports produits et bons PDF', 'Multi-societe avec roles utilisateurs', 'Limites SaaS par abonnement', 'Dashboard KPI pour gerants'] as $feature)
                    <div class="rounded-lg border border-slate-200 bg-white p-6 font-bold shadow-sm">{{ $feature }}</div>
                @endforeach
            </div>
        </section>

        <section id="pricing" class="bg-[#0f172a] py-16 text-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6">
                <p class="text-sm font-black uppercase text-blue-200">Tarifs</p>
                <h2 class="mt-2 text-3xl font-black">Adaptes au marche marocain</h2>
                <div class="mt-8 grid gap-4 md:grid-cols-4">
                    @foreach([['Free', '0 DH', '50 produits'], ['Starter', '99 DH/mois', '300 produits'], ['Pro', '199 DH/mois', '3 entrepots'], ['Enterprise', '499 DH/mois', 'Illimite']] as $plan)
                        <div class="rounded-lg border border-white/15 bg-white/8 p-6 backdrop-blur">
                            <p class="text-lg font-black">{{ $plan[0] }}</p>
                            <p class="mt-3 text-3xl font-black text-blue-100">{{ $plan[1] }}</p>
                            <p class="mt-3 text-sm text-slate-300">{{ $plan[2] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="faq" class="mx-auto max-w-4xl px-4 py-16 sm:px-6">
            <p class="text-sm font-black uppercase text-[#2563eb]">FAQ</p>
            <h2 class="mt-2 text-3xl font-black text-[#0f172a]">Questions frequentes</h2>
            <div class="mt-7 space-y-4">
                <details class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <summary class="cursor-pointer font-black">Est-ce adapte aux boutiques WhatsApp/Instagram ?</summary>
                    <p class="mt-3 text-slate-600">Oui, le MVP gere ventes rapides, clients, stock et PDF simples.</p>
                </details>
                <details class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <summary class="cursor-pointer font-black">La devise est-elle en MAD ?</summary>
                    <p class="mt-3 text-slate-600">Oui, la devise par defaut est MAD.</p>
                </details>
                <details class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <summary class="cursor-pointer font-black">Peut-on ajouter Darija plus tard ?</summary>
                    <p class="mt-3 text-slate-600">Oui, l'interface est en francais avec une structure prete pour traduction future.</p>
                </details>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6">
            <div class="rounded-2xl bg-white p-8 shadow-xl shadow-slate-200/70 md:flex md:items-center md:justify-between">
                <div>
                    <h2 class="text-3xl font-black text-[#0f172a]">Pret a vendre StockFlow Maroc ?</h2>
                    <p class="mt-2 text-slate-600">Un panel propre, des PDF professionnels et une experience qui inspire confiance.</p>
                </div>
                <div class="mt-6 flex flex-wrap gap-3 md:mt-0">
                    <a href="https://wa.me/212600000000" class="rounded-md bg-[#10b981] px-5 py-3 text-sm font-extrabold text-white">Demander une demo WhatsApp</a>
                    <a href="/admin" class="rounded-md border border-slate-300 px-5 py-3 text-sm font-extrabold text-[#0f172a]">Acceder au panel</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 bg-white px-4 py-8 sm:px-6">
        <div class="mx-auto flex max-w-7xl flex-col justify-between gap-4 text-sm text-slate-500 md:flex-row">
            <span class="font-bold text-[#0f172a]">StockFlow Maroc</span>
            <span>Gestion de stock SaaS pour PME marocaines.</span>
        </div>
    </footer>
</body>
</html>
