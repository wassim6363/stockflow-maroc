<x-filament-widgets::widget>
    <style>
        .sf-hero-widget {
            position: relative;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            border-radius: 1rem;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            padding: 1.5rem;
            color: white;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        @media (min-width: 1024px) {
            .sf-hero-widget {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
            .sf-hero-left {
                grid-column: span 2 / span 2;
                flex-direction: row !important;
                align-items: flex-start !important;
            }
        }
        .sf-hero-bg-accent {
            position: absolute;
            width: 24rem;
            height: 24rem;
            border-radius: 9999px;
            filter: blur(64px);
            pointer-events: none;
        }
        .sf-hero-bg-accent-1 {
            top: -6rem; left: -6rem; background: rgba(96, 165, 250, 0.2);
        }
        .sf-hero-bg-accent-2 {
            bottom: -6rem; right: -6rem; background: rgba(30, 58, 138, 0.2);
        }
        .sf-hero-left {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            z-index: 10;
        }
        .sf-hero-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            padding-top: 0.5rem;
        }
        .sf-hero-text h2 {
            font-size: 1.875rem;
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -0.025em;
            margin-bottom: 0.75rem;
            margin-top: 0;
            color: white;
        }
        .sf-hero-text p {
            color: #dbeafe;
            margin-bottom: 1.5rem;
            line-height: 1.625;
            max-width: 32rem;
            font-size: 1rem;
        }
        .sf-hero-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: white;
            transition: all 0.2s;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            width: max-content;
            text-decoration: none;
        }
        .sf-hero-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .sf-hero-btn svg {
            width: 1.25rem;
            height: 1.25rem;
        }
        .sf-hero-right {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.75rem;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 10;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .sf-hero-dl {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            margin: 0;
        }
        .sf-hero-dl-main {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 1rem;
            position: relative;
        }
        .sf-hero-dl dt {
            font-size: 0.75rem;
            font-weight: 600;
            color: #bfdbfe;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0;
        }
        .sf-hero-dl dd {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            margin-top: 0.375rem;
            margin-left: 0;
            margin-bottom: 0;
        }
        .sf-hero-dl-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }
        .sf-hero-dl-grid dd {
            font-size: 0.875rem;
        }
        .sf-hero-right-svg {
            position: absolute;
            top: 0;
            right: 0;
            width: 3rem;
            height: 3rem;
            color: rgba(255, 255, 255, 0.1);
        }
        /* Override massive SVG fallback */
        .sf-hero-widget svg {
            max-width: 100%;
        }
    </style>

    <section class="sf-hero-widget">
        <!-- Background accents -->
        <div class="sf-hero-bg-accent sf-hero-bg-accent-1"></div>
        <div class="sf-hero-bg-accent sf-hero-bg-accent-2"></div>

        <!-- Left Side: 3D asset alongside text -->
        <div class="sf-hero-left">
            <!-- 3D Asset -->
            <div style="position: relative; width: 12rem; height: 10rem; flex-shrink: 0;">
                <div class="stockflow-hero-art" style="width: 100%; height: 100%; position: relative;" aria-hidden="true">
                    <div class="stockflow-pallet" style="transform: scale(0.9); transform-origin: top left;">
                        <span></span><span></span><span></span><span></span><span></span>
                    </div>
                    <div class="stockflow-clipboard" style="transform: scale(0.9); transform-origin: bottom right;">
                        <i></i><b></b><b></b><b></b><em></em>
                    </div>
                </div>
            </div>
            
            <div class="sf-hero-text">
                <h2>Bienvenue sur StockFlow Maroc</h2>
                <p>Gérez votre stock, vos ventes, vos achats et vos rapports en toute simplicité.</p>
                <a href="{{ url('/admin/products') }}" class="sf-hero-btn">
                    Découvrir les nouveautés
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09l2.846.813-.813.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Right Side Inner Card: Société active -->
        <aside class="sf-hero-right">
            <dl class="sf-hero-dl">
                <div class="sf-hero-dl-main">
                    <dt>Société active</dt>
                    <dd>{{ $companyName }}</dd>
                    <svg class="sf-hero-right-svg" viewBox="0 0 120 120" fill="currentColor" aria-hidden="true">
                        <path d="M79 16l17 7-4 14 11 8-12 11 2 14-17 5-8 20-18 8-7-14-16 1 4-17-12-9 12-12-1-18 18-5 10-16z" />
                        <circle cx="78" cy="28" r="7" />
                    </svg>
                </div>
                <div class="sf-hero-dl-grid">
                    <div>
                        <dt>Entrepôt principal</dt>
                        <dd>{{ $warehouseName }}</dd>
                    </div>
                    <div>
                        <dt>Dernière connexion</dt>
                        <dd>{{ $lastLogin }}</dd>
                    </div>
                </div>
            </dl>
        </aside>
    </section>
</x-filament-widgets::widget>
