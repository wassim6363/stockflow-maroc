<x-filament-panels::page>
    <div
        class="prt-wrapper"
        x-data="printerApp()"
    >
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');
            
            [x-cloak] { display: none !important; }

            .prt-wrapper {
                font-family: 'Outfit', system-ui, -apple-system, sans-serif;
                color: #0f172a;
                display: grid;
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            @media (min-width: 1024px) {
                .prt-wrapper {
                    grid-template-columns: 1fr 20rem;
                }
            }

            .prt-card {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(24px) saturate(180%);
                -webkit-backdrop-filter: blur(24px) saturate(180%);
                border: 1px solid rgba(255, 255, 255, 0.8);
                border-radius: 24px;
                padding: 1.5rem;
                box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05), inset 0 2px 4px rgba(255,255,255,0.8);
                position: relative;
            }

            .prt-card-header {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 1.5rem;
                border-bottom: 1px dashed rgba(203, 213, 225, 0.8);
                padding-bottom: 1rem;
            }

            .prt-card-header h2 {
                margin: 0;
                font-size: 1.25rem;
                font-weight: 800;
                color: #0f172a;
            }

            .prt-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 2.75rem;
                height: 2.75rem;
                border-radius: 12px;
                background: linear-gradient(135deg, #f97316, #ea580c);
                color: white;
                box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
            }
            .prt-icon svg { width: 1.4rem; }

            /* TABS */
            .prt-tabs {
                display: flex;
                background: rgba(241, 245, 249, 0.8);
                padding: 0.35rem;
                border-radius: 14px;
                gap: 0.35rem;
                margin-bottom: 1.5rem;
            }

            .prt-tab-btn {
                flex: 1;
                text-align: center;
                padding: 0.75rem;
                border-radius: 10px;
                font-weight: 700;
                font-size: 0.95rem;
                color: #64748b;
                cursor: pointer;
                transition: all 0.2s ease;
                border: none;
                background: transparent;
            }

            .prt-tab-btn.active {
                background: white;
                color: #ea580c;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            }

            .prt-subtabs {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
                margin-bottom: 1.5rem;
            }

            .prt-subtab-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.6rem 1.25rem;
                background: white;
                border: 1px solid rgba(203, 213, 225, 0.8);
                border-radius: 999px;
                font-weight: 700;
                font-size: 0.9rem;
                color: #475569;
                cursor: pointer;
                transition: all 0.2s;
            }
            .prt-subtab-btn svg { width: 1.2rem; }

            .prt-subtab-btn:hover {
                border-color: #cbd5e1;
                background: #f8fafc;
            }

            .prt-subtab-btn.active {
                background: #fff7ed;
                border-color: #fdba74;
                color: #ea580c;
            }
            
            .prt-subtab-btn.active.bt { background: #eff6ff; border-color: #93c5fd; color: #2563eb; }
            .prt-subtab-btn.active.usb { background: #f0fdf4; border-color: #86efac; color: #16a34a; }

            /* INFO BOX */
            .prt-info-box {
                background: #f0f9ff;
                border: 1px solid #bae6fd;
                border-radius: 16px;
                padding: 1.25rem;
                margin-bottom: 1.5rem;
            }
            .prt-info-box h4 { margin: 0 0 0.5rem 0; color: #0284c7; font-size: 0.95rem; display: flex; align-items: center; gap: 0.4rem; }
            .prt-info-box p { margin: 0 0 0.5rem 0; font-size: 0.85rem; color: #334155; line-height: 1.5; }
            .prt-info-box ul { margin: 0; padding-left: 1.25rem; font-size: 0.85rem; color: #334155; }

            /* FORMS */
            .prt-form-group {
                margin-bottom: 1.25rem;
            }
            .prt-form-group label {
                display: block;
                font-weight: 700;
                font-size: 0.85rem;
                color: #475569;
                margin-bottom: 0.4rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .prt-input {
                width: 100%;
                background: white;
                border: 1px solid #cbd5e1;
                border-radius: 12px;
                padding: 0.75rem 1rem;
                font-family: inherit;
                font-size: 1rem;
                font-weight: 600;
                color: #0f172a;
                transition: all 0.2s;
            }
            .prt-input:focus {
                outline: none;
                border-color: #f97316;
                box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
            }

            .prt-grid-2 {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }

            .prt-radio-group {
                display: flex;
                gap: 1rem;
            }
            .prt-radio-label {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                background: white;
                border: 1px solid #cbd5e1;
                border-radius: 12px;
                padding: 0.75rem;
                font-weight: 700;
                font-size: 0.9rem;
                cursor: pointer;
                transition: all 0.2s;
            }
            .prt-radio-label input { display: none; }
            .prt-radio-label:has(input:checked) {
                background: #fff7ed;
                border-color: #fdba74;
                color: #ea580c;
            }

            /* BUTTONS */
            .prt-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
                margin-top: 2rem;
                border-top: 1px dashed rgba(203, 213, 225, 0.8);
                padding-top: 1.5rem;
            }

            .prt-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                height: 3rem;
                padding: 0 1.5rem;
                border-radius: 12px;
                font-weight: 800;
                font-size: 0.95rem;
                cursor: pointer;
                border: none;
                transition: all 0.2s ease;
                flex: 1;
            }
            
            .prt-btn-primary {
                background: linear-gradient(135deg, #f97316, #ea580c);
                color: white;
                box-shadow: 0 4px 12px rgba(249, 115, 22, 0.25);
            }
            .prt-btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 16px rgba(249, 115, 22, 0.35);
            }

            .prt-btn-secondary {
                background: white;
                color: #475569;
                border: 1px solid #cbd5e1;
                box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            }
            .prt-btn-secondary:hover {
                background: #f8fafc;
                border-color: #94a3b8;
                color: #0f172a;
            }

            /* LIST */
            .prt-list {
                list-style: none;
                padding: 0;
                margin: 0;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
            .prt-list li {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem 1rem;
                background: white;
                border: 1px solid rgba(226, 232, 240, 0.8);
                border-radius: 12px;
                font-size: 0.9rem;
                font-weight: 600;
                color: #334155;
            }
            .prt-list li svg { width: 1.2rem; color: #10b981; }

            .prt-badge {
                font-size: 0.7rem;
                background: #fef3c7;
                color: #b45309;
                padding: 0.15rem 0.5rem;
                border-radius: 999px;
                text-transform: uppercase;
                font-weight: 800;
                margin-left: auto;
            }

            .prt-loading-overlay {
                position: absolute;
                inset: 0;
                background: rgba(255,255,255,0.7);
                backdrop-filter: blur(4px);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                z-index: 10;
                border-radius: 24px;
                font-weight: 700;
                color: #0f172a;
            }
        </style>

        <!-- MAIN CONFIG CARD -->
        <div class="prt-card">
            <div class="prt-card-header">
                <div class="prt-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0v2.796c0 1.135.845 2.098 1.976 2.192.333.028.667.05 1.006.071m8.528-.071c1.131-.094 1.976-1.057 1.976-2.192V7.224m-10.5 0v-1.5c0-.621.504-1.125 1.125-1.125h8.25c.621 0 1.125.504 1.125 1.125v1.5m-10.5 0a48.536 48.536 0 0 0 10.5 0" />
                    </svg>
                </div>
                <div>
                    <h2>Configuration Imprimante</h2>
                    <p style="margin: 0; font-size: 0.9rem; color: #64748b;">Connectez votre imprimante thermique ou A4.</p>
                </div>
            </div>

            <!-- TYPE TABS -->
            <div class="prt-tabs">
                <button class="prt-tab-btn" :class="{ 'active': printerType === 'thermal' }" @click="printerType = 'thermal'">🔥 Thermique (ticket)</button>
                <button class="prt-tab-btn" :class="{ 'active': printerType === 'a4' }" @click="printerType = 'a4'">📄 A4 Standard</button>
            </div>

            <template x-if="printerType === 'thermal'">
                <div>
                    <h3 style="font-size: 1rem; margin: 0 0 0.75rem 0; font-weight: 800;">Mode de connexion</h3>
                    <div class="prt-subtabs">
                        <button class="prt-subtab-btn" :class="{ 'active': connType === 'wifi' }" @click="connType = 'wifi'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z" />
                            </svg>
                            WiFi / LAN
                        </button>
                        <button class="prt-subtab-btn usb" :class="{ 'active': connType === 'usb' }" @click="connType = 'usb'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
                            </svg>
                            USB (câble)
                        </button>
                        <button class="prt-subtab-btn bt" :class="{ 'active': connType === 'bt' }" @click="connType = 'bt'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 17.25 10.5 21v-7.5l3.75-3.75M14.25 6.75 10.5 3v7.5l3.75 3.75M10.5 10.5l-3.75 3.75M10.5 13.5l-3.75-3.75" />
                            </svg>
                            Bluetooth
                        </button>
                    </div>

                    <template x-if="connType === 'wifi'">
                        <div>
                            <div class="prt-info-box">
                                <h4>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.2rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                    </svg>
                                    Comment trouver l'IP de votre imprimante ?
                                </h4>
                                <ul>
                                    <li>Imprimez une page de configuration depuis l'imprimante (éteignez, puis rallumez en maintenant "Feed").</li>
                                    <li>L'adresse IP y figurera (ex: 192.168.1.100).</li>
                                </ul>
                            </div>

                            <div class="prt-grid-2">
                                <div class="prt-form-group">
                                    <label>Adresse IP</label>
                                    <input type="text" class="prt-input" placeholder="192.168.1.100" x-model="wifiIP">
                                </div>
                                <div class="prt-form-group">
                                    <label>Port</label>
                                    <input type="text" class="prt-input" placeholder="9100" x-model="wifiPort">
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="connType === 'usb'">
                        <div class="prt-info-box" style="background: #f0fdf4; border-color: #bbf7d0;">
                            <h4>Connexion USB</h4>
                            <p>Assurez-vous que le câble USB est connecté et que les pilotes système de l'imprimante sont installés sur cet ordinateur.</p>
                        </div>
                    </template>

                    <template x-if="connType === 'bt'">
                        <div class="prt-info-box" style="background: #eff6ff; border-color: #bfdbfe;">
                            <h4>Connexion Bluetooth</h4>
                            <p>Associez d'abord l'imprimante dans les paramètres Bluetooth de votre appareil système.</p>
                        </div>
                    </template>

                    <div class="prt-form-group" style="margin-top: 1rem;">
                        <label>Largeur du papier</label>
                        <div class="prt-radio-group">
                            <label class="prt-radio-label">
                                <input type="radio" name="paper_width" value="58" x-model="paperWidth">
                                58mm (petit)
                            </label>
                            <label class="prt-radio-label">
                                <input type="radio" name="paper_width" value="80" x-model="paperWidth">
                                80mm (standard)
                            </label>
                        </div>
                    </div>
                </div>
            </template>
            
            <template x-if="printerType === 'a4'">
                <div class="prt-info-box" style="background: #f8fafc; border-color: #e2e8f0;">
                    <h4>Configuration A4</h4>
                    <p>Le système utilisera l'imprimante par défaut de votre navigateur pour générer les factures et devis au format A4. Assurez-vous que vos marges sont définies sur "Aucune" lors de l'impression.</p>
                </div>
            </template>

            <!-- ACTION BUTTONS -->
            <div class="prt-actions">
                <button class="prt-btn prt-btn-primary" @click="simulateSave()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.2rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
                    </svg>
                    Sauvegarder
                </button>
                
                <template x-if="printerType === 'thermal'">
                    <button class="prt-btn prt-btn-secondary" @click="simulateAction('Test de connexion réussi !')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.2rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                        </svg>
                        Tester connexion
                    </button>
                </template>
                
                <template x-if="printerType === 'thermal'">
                    <button class="prt-btn prt-btn-secondary" @click="simulateAction('Ticket de test envoyé à l\'imprimante !')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.2rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0v2.796c0 1.135.845 2.098 1.976 2.192.333.028.667.05 1.006.071m8.528-.071c1.131-.094 1.976-1.057 1.976-2.192V7.224m-10.5 0v-1.5c0-.621.504-1.125 1.125-1.125h8.25c.621 0 1.125.504 1.125 1.125v1.5m-10.5 0a48.536 48.536 0 0 0 10.5 0" />
                        </svg>
                        Imprimer test
                    </button>
                </template>
            </div>

            <!-- LOADING OVERLAY -->
            <div class="prt-loading-overlay" x-show="loading" x-cloak>
                <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 3rem; color: #f97316; margin-bottom: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Traitement en cours...
            </div>
        </div>

        <!-- SIDEBAR: COMPATIBILITY LIST -->
        <div class="prt-card" style="background: rgba(248, 250, 252, 0.9);">
            <div class="prt-card-header" style="border-bottom-color: rgba(203, 213, 225, 0.5);">
                <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800;">Imprimantes compatibles</h3>
            </div>
            
            <p style="font-size: 0.85rem; color: #64748b; margin: 0 0 1rem 0;">Le système supporte le protocole universel ESC/POS pour les imprimantes thermiques.</p>

            <ul class="prt-list">
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg> Epson TM-T <span class="prt-badge">Recommandé</span></li>
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg> Star Micronics</li>
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg> Xprinter</li>
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg> Bixolon</li>
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg> ZJ-5890 / POS-80</li>
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg> iDPRT / Rongta</li>
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg> Munbyn</li>
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg> Autre (ESC/POS)</li>
            </ul>
        </div>
    </div>

    <script>
        function printerApp() {
            return {
                printerType: 'thermal', // thermal | a4
                connType: 'wifi',       // wifi | usb | bt
                wifiIP: '192.168.1.100',
                wifiPort: '9100',
                paperWidth: '80',
                loading: false,

                simulateSave() {
                    this.loading = true;
                    setTimeout(() => {
                        this.loading = false;
                        alert('Paramètres sauvegardés avec succès.');
                    }, 800);
                },

                simulateAction(msg) {
                    this.loading = true;
                    setTimeout(() => {
                        this.loading = false;
                        alert(msg);
                    }, 1200);
                }
            }
        }
    </script>
</x-filament-panels::page>
