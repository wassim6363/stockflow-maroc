<x-filament-panels::page>
    <div
        class="wa-wrapper"
        x-data="whatsappApp()"
        x-init="startTimer()"
    >
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');
            
            [x-cloak] { display: none !important; }

            .wa-wrapper {
                font-family: 'Outfit', system-ui, -apple-system, sans-serif;
                color: #0f172a;
                display: grid;
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            @media (min-width: 1024px) {
                .wa-wrapper {
                    grid-template-columns: 22rem 1fr;
                }
            }

            .wa-card {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(24px) saturate(180%);
                -webkit-backdrop-filter: blur(24px) saturate(180%);
                border: 1px solid rgba(255, 255, 255, 0.8);
                border-radius: 24px;
                padding: 1.5rem;
                box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05), inset 0 2px 4px rgba(255,255,255,0.8);
                overflow: hidden;
                position: relative;
            }

            .wa-card::before {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0;
                height: 4px;
                background: linear-gradient(90deg, #25d366, #128c7e);
                opacity: 0;
                transition: opacity 0.3s;
            }

            .wa-card:hover::before {
                opacity: 1;
            }

            .wa-card-header {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 1.25rem;
                border-bottom: 1px dashed rgba(203, 213, 225, 0.8);
                padding-bottom: 1rem;
            }

            .wa-card-header h2 {
                margin: 0;
                font-size: 1.25rem;
                font-weight: 800;
                color: #0f172a;
            }

            .wa-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 2.75rem;
                height: 2.75rem;
                border-radius: 12px;
                background: linear-gradient(135deg, #25d366, #128c7e);
                color: white;
                box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
            }
            .wa-icon svg { width: 1.4rem; }

            /* QR CODE SECTION */
            .wa-qr-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .wa-qr-box {
                position: relative;
                width: 14rem;
                height: 14rem;
                background: white;
                border-radius: 20px;
                padding: 1rem;
                box-shadow: 0 8px 24px rgba(0,0,0,0.06);
                margin-bottom: 1.5rem;
                border: 2px dashed #cbd5e1;
                display: grid;
                place-items: center;
            }

            .wa-qr-box img {
                width: 100%;
                height: 100%;
                object-fit: contain;
                opacity: 1;
                transition: opacity 0.3s;
            }

            .wa-qr-box.expired img {
                opacity: 0.2;
                filter: blur(2px);
            }

            .wa-qr-expired-overlay {
                position: absolute;
                inset: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background: rgba(255,255,255,0.7);
                backdrop-filter: blur(4px);
                border-radius: 18px;
            }

            .wa-instructions {
                text-align: left;
                width: 100%;
                background: rgba(241, 245, 249, 0.6);
                padding: 1rem;
                border-radius: 16px;
                margin-bottom: 1.25rem;
            }

            .wa-instructions p {
                margin: 0 0 0.5rem 0;
                font-weight: 700;
                font-size: 0.95rem;
            }

            .wa-instructions ol {
                margin: 0;
                padding-left: 1.25rem;
                font-size: 0.9rem;
                color: #475569;
                line-height: 1.5;
                font-weight: 500;
            }

            .wa-warning {
                display: flex;
                align-items: flex-start;
                gap: 0.5rem;
                background: #fffbeb;
                border: 1px solid #fef3c7;
                color: #b45309;
                padding: 0.75rem;
                border-radius: 12px;
                font-size: 0.85rem;
                font-weight: 600;
                margin-bottom: 1rem;
                width: 100%;
            }
            .wa-warning svg { width: 1.2rem; flex-shrink: 0; color: #d97706; }

            .wa-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                width: 100%;
                height: 3rem;
                border-radius: 12px;
                font-weight: 800;
                font-size: 0.95rem;
                cursor: pointer;
                border: none;
                transition: all 0.2s ease;
            }

            .wa-btn-primary {
                background: linear-gradient(135deg, #25d366, #128c7e);
                color: white;
                box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);
            }
            .wa-btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 16px rgba(37, 211, 102, 0.35);
            }

            .wa-btn-secondary {
                background: white;
                color: #475569;
                border: 1px solid #cbd5e1;
                box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            }
            .wa-btn-secondary:hover {
                background: #f8fafc;
                border-color: #94a3b8;
                color: #0f172a;
            }

            /* MESSAGE SECTION */
            .wa-message-layout {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            @media (min-width: 768px) {
                .wa-message-layout {
                    grid-template-columns: 1fr 1fr;
                }
            }

            .wa-templates {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                margin-bottom: 1rem;
            }

            .wa-template-btn {
                background: rgba(255,255,255,0.6);
                border: 1px solid rgba(203, 213, 225, 0.8);
                padding: 0.5rem 1rem;
                border-radius: 999px;
                font-size: 0.85rem;
                font-weight: 700;
                color: #475569;
                cursor: pointer;
                transition: all 0.2s;
            }

            .wa-template-btn:hover {
                background: white;
                border-color: #94a3b8;
                transform: translateY(-1px);
            }

            .wa-template-btn.active {
                background: #e0f2fe;
                border-color: #38bdf8;
                color: #0284c7;
            }

            .wa-textarea {
                width: 100%;
                height: 12rem;
                border: 1px solid rgba(203, 213, 225, 0.8);
                border-radius: 16px;
                padding: 1rem;
                background: rgba(255, 255, 255, 0.6);
                font-family: inherit;
                font-size: 0.95rem;
                font-weight: 500;
                color: #0f172a;
                resize: none;
                outline: none;
                transition: all 0.2s;
                box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
                margin-bottom: 1rem;
            }

            .wa-textarea:focus {
                background: white;
                border-color: #25d366;
                box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.15), inset 0 2px 4px rgba(0,0,0,0.02);
            }

            .wa-alert-error {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                background: #fef2f2;
                border: 1px solid #fee2e2;
                color: #b91c1c;
                padding: 0.75rem 1rem;
                border-radius: 12px;
                font-size: 0.85rem;
                font-weight: 700;
                margin-bottom: 1rem;
            }
            .wa-alert-error svg { width: 1.2rem; color: #ef4444; }

            /* HISTORY SECTION */
            .wa-history-list {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
                max-height: 25rem;
                overflow-y: auto;
                padding-right: 0.5rem;
            }
            .wa-history-list::-webkit-scrollbar { width: 6px; }
            .wa-history-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

            .wa-history-item {
                background: white;
                border: 1px solid rgba(226, 232, 240, 0.8);
                border-radius: 16px;
                padding: 1rem;
                box-shadow: 0 2px 4px rgba(0,0,0,0.02);
                display: flex;
                flex-direction: column;
                gap: 0.4rem;
            }

            .wa-history-item-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .wa-badge {
                background: #dcfce7;
                color: #166534;
                padding: 0.15rem 0.5rem;
                border-radius: 999px;
                font-size: 0.7rem;
                font-weight: 800;
                text-transform: uppercase;
            }

            .wa-history-time {
                color: #64748b;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .wa-history-text {
                font-size: 0.9rem;
                color: #334155;
                font-weight: 500;
                line-height: 1.4;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .wa-history-empty {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                min-height: 15rem;
                background: rgba(248, 250, 252, 0.6);
                border: 2px dashed rgba(203, 213, 225, 0.8);
                border-radius: 20px;
                color: #94a3b8;
                font-weight: 700;
            }
            .wa-history-empty svg { width: 3rem; margin-bottom: 0.5rem; color: #cbd5e1; }
        </style>

        <!-- LEFT COLUMN: QR CODE -->
        <div class="wa-card">
            <div class="wa-card-header">
                <div class="wa-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                    </svg>
                </div>
                <h2>Scannez le QR Code</h2>
            </div>

            <div class="wa-qr-container">
                <div class="wa-qr-box" :class="{ 'expired': isExpired }">
                    <!-- Placeholder QR code -->
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=WhatsApp-Integration-Mockup" alt="WhatsApp QR Code">
                    
                    <div class="wa-qr-expired-overlay" x-show="isExpired" x-cloak>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 2rem; color: #ef4444; margin-bottom: 0.5rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <strong style="color: #0f172a;">QR Code Expiré</strong>
                    </div>
                </div>

                <div class="wa-instructions">
                    <p>Comment connecter :</p>
                    <ol>
                        <li>Ouvrez WhatsApp sur votre téléphone</li>
                        <li>Appuyez sur ⋮ Menu (3 points) → <strong>Appareils liés</strong></li>
                        <li>Appuyez sur <strong>"Lier un appareil"</strong></li>
                        <li>Scannez le QR code affiché</li>
                    </ol>
                </div>

                <div class="wa-warning" x-show="!isExpired">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Le QR expire dans <strong x-text="timer"></strong> secondes.</span>
                </div>
                
                <div class="wa-warning" x-show="isExpired" x-cloak style="background: #fef2f2; border-color: #fee2e2; color: #b91c1c;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color: #ef4444;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Le QR a expiré. Rechargez-le.</span>
                </div>

                <button class="wa-btn wa-btn-secondary" @click="refreshQR()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.2rem;" :class="{ 'animate-spin': isRefreshing }">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Nouveau QR
                </button>
            </div>
        </div>

        <!-- RIGHT COLUMN: MESSAGES & HISTORY -->
        <div class="wa-message-layout">
            
            <!-- COMPOSE MESSAGE -->
            <div class="wa-card">
                <div class="wa-card-header">
                    <div class="wa-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                    </div>
                    <h2>Envoyer un message</h2>
                </div>

                <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Template rapide</p>
                <div class="wa-templates">
                    <button class="wa-template-btn" :class="{ 'active': template === 'rapport' }" @click="setTemplate('rapport')">Rapport journalier</button>
                    <button class="wa-template-btn" :class="{ 'active': template === 'stock' }" @click="setTemplate('stock')">Alerte stock</button>
                    <button class="wa-template-btn" :class="{ 'active': template === 'credit' }" @click="setTemplate('credit')">Crédit en retard</button>
                    <button class="wa-template-btn" :class="{ 'active': template === 'libre' }" @click="setTemplate('libre')">Message libre</button>
                </div>

                <p style="margin: 0.5rem 0 0.5rem 0; font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Message *</p>
                <textarea class="wa-textarea" x-model="message" placeholder="Saisissez votre message ici..."></textarea>

                <div class="wa-alert-error" x-show="showWarning">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>Numéro WhatsApp non configuré. Allez dans Paramètres.</span>
                </div>

                <button class="wa-btn wa-btn-primary" @click="sendMessage()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.2rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                    </svg>
                    Envoyer
                </button>
            </div>

            <!-- HISTORY -->
            <div class="wa-card">
                <div class="wa-card-header">
                    <div class="wa-icon" style="background: linear-gradient(135deg, #8b5cf6, #6366f1);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                    </div>
                    <h2>Historique</h2>
                </div>

                <template x-if="history.length === 0">
                    <div class="wa-history-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Aucun message envoyé
                    </div>
                </template>

                <div class="wa-history-list" x-show="history.length > 0">
                    <template x-for="item in history" :key="item.id">
                        <div class="wa-history-item">
                            <div class="wa-history-item-header">
                                <span class="wa-badge" x-text="item.type"></span>
                                <span class="wa-history-time" x-text="item.time"></span>
                            </div>
                            <div class="wa-history-text" x-text="item.text"></div>
                        </div>
                    </template>
                </div>
            </div>
            
        </div>
    </div>

    <script>
        function whatsappApp() {
            return {
                timer: 60,
                isExpired: false,
                isRefreshing: false,
                interval: null,
                
                template: 'credit',
                message: 'RAPPEL : Le crédit de {client} d\'un montant de {montant} DH est en retard de paiement.',
                showWarning: true,
                history: [],
                
                templatesMap: {
                    'rapport': 'Bonjour, voici le rapport journalier de la caisse:\nVentes: 1250 DH\nDépenses: 300 DH\nSolde: 950 DH',
                    'stock': 'ALERTE STOCK: Les produits suivants sont en rupture de stock:\n- Produit A\n- Produit B',
                    'credit': 'RAPPEL : Le crédit de {client} d\'un montant de {montant} DH est en retard de paiement.',
                    'libre': ''
                },

                startTimer() {
                    this.timer = 60;
                    this.isExpired = false;
                    clearInterval(this.interval);
                    this.interval = setInterval(() => {
                        if (this.timer > 0) {
                            this.timer--;
                        } else {
                            this.isExpired = true;
                            clearInterval(this.interval);
                        }
                    }, 1000);
                },

                refreshQR() {
                    if (this.isRefreshing) return;
                    this.isRefreshing = true;
                    // Simulate API delay
                    setTimeout(() => {
                        this.startTimer();
                        this.isRefreshing = false;
                    }, 800);
                },

                setTemplate(key) {
                    this.template = key;
                    this.message = this.templatesMap[key] || '';
                    this.showWarning = false;
                },

                sendMessage() {
                    if (!this.message.trim()) return;
                    
                    // Simulate error for mockup if empty template selected
                    if (this.template === 'libre' && this.message === 'erreur') {
                        this.showWarning = true;
                        return;
                    }
                    this.showWarning = false;

                    // Add to history
                    this.history.unshift({
                        id: Date.now(),
                        type: this.template.toUpperCase(),
                        text: this.message,
                        time: new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute:'2-digit' })
                    });
                    
                    // Reset if libre
                    if (this.template === 'libre') {
                        this.message = '';
                    }
                }
            }
        }
    </script>
</x-filament-panels::page>
