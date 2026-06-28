# StockFlow Maroc

StockFlow Maroc est un MVP SaaS Laravel pour la gestion de stock, achats, ventes, fournisseurs, clients, inventaires, mouvements, PDF et exports Excel pour PME marocaines.

## Stack

- Laravel 12, PHP 8.3
- Filament 5.6.7
- MySQL en production, SQLite possible en local/test
- DomPDF pour bons PDF
- Laravel Excel / PhpSpreadsheet pour import/export
- Tailwind via CDN pour la landing page

## Installation

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan filament:assets
php artisan test
php artisan serve
```

Si Node.js/npm est absent, le backend reste utilisable. Pour l'installer sous Windows:

```bash
winget install OpenJS.NodeJS.LTS
```

Apres installation de Node.js LTS, compiler les assets applicatifs:

```bash
npm install
npm run build
```

## Assets admin Filament

Le panel Filament charge le theme admin depuis:

- `resources/css/filament/admin/theme.css` pour le build Vite
- `public/css/filament/admin/theme.css` comme fallback direct quand Node/npm est absent

Le provider admin enregistre ce fallback avec `->theme(asset('css/filament/admin/theme.css'))`, afin que `/admin` ne depende pas de `public/build/manifest.json` sur une machine sans Node.

Publier et vider les caches apres installation:

```bash
php artisan filament:assets
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
```

Pour compiler le theme Vite sur une machine avec Node.js LTS:

```bash
npm install
npm run build
php artisan filament:assets
php artisan optimize:clear
```

Si `npm` n'est pas reconnu sous Windows, installer Node.js LTS puis relancer les commandes:

```bash
winget install OpenJS.NodeJS.LTS
```

## Configuration MySQL

`.env.example` est configure pour:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stockflow_maroc
DB_USERNAME=root
DB_PASSWORD=
```

Creer la base `stockflow_maroc`, puis lancer `php artisan migrate:fresh --seed`.

## Acces

- Landing page: `/`
- Panel Filament: `/admin`
- Login Filament: `/admin/login`

Comptes de test:

- Super admin: `admin@stockflow.ma` / `password`
- Company admin: `company@stockflow.ma` / `password`
- Stock manager: `stock@stockflow.ma` / `password`
- Cashier: `cashier@stockflow.ma` / `password`

## Modules inclus

- Dashboard KPI: valeur stock, produits actifs, stock bas, ventes/achats du mois
- Societes super admin
- Entrepots
- Categories
- Produits avec SKU, barcode, image, prix, marge estimee, stock actuel
- Fournisseurs
- Clients
- Achats avec action Receptionner et PDF
- Ventes avec action Livrer, blocage stock insuffisant et PDF
- Inventaires avec action Valider inventaire et PDF
- Mouvements de stock en lecture seule avec export
- Parametres societe: ICE, logo, devise MAD, TVA, footer facture, stock negatif prepare

## Workflows metier

- Achat recu: augmente `stock_levels` et cree un mouvement `purchase`.
- Vente livree: verifie le stock, bloque si insuffisant, diminue `stock_levels` et cree un mouvement `sale`.
- Inventaire valide: compare stock systeme et stock compte, ajuste le stock et cree un mouvement `adjustment`.
- References automatiques: `ACH-2026-0001`, `VTE-2026-0001`, `INV-2026-0001`.
- Totaux HT/TVA/TTC recalcules dans `StockService`.

## Import produits

Colonnes attendues:

`name, sku, barcode, category, unit, purchase_price, sale_price, min_stock, initial_quantity, warehouse`

Validations:

- `name` obligatoire
- `unit` parmi `piece, kg, litre, carton, pack, metre`
- prix et quantite initiale >= 0
- categorie creee si absente
- entrepot cree si absent
- quantite initiale cree un niveau de stock et un mouvement `initial`

## Plans SaaS

- Free: 1 entrepot, 50 produits, watermark PDF
- Starter: 99 DH/mois, 1 entrepot, 300 produits
- Pro: 199 DH/mois, 3 entrepots, 2000 produits
- Enterprise: 499 DH/mois, illimite

Message limite:

`Vous avez atteint la limite de votre abonnement. Veuillez passer a un plan superieur.`

## Tests

Commande:

```bash
php artisan test
```

Couverture incluse:

- `StockMovementTest`
- `MultiCompanySecurityTest`
- `SaaSLimitsTest`
- `PdfGenerationTest`
- `ProductImportTest`

Dernier resultat local: 18 tests passes, 25 assertions.

## Fichiers principaux crees

- `app/Models/*` pour le domaine StockFlow
- `app/Services/StockService.php`, `ReferenceService.php`, `PdfService.php`, `SubscriptionLimitService.php`
- `app/Filament/Resources/*` pour les modules admin
- `app/Filament/Widgets/*` pour le dashboard
- `app/Imports/ProductImport.php`
- `app/Exports/ProductExport.php`, `ProductTemplateExport.php`, `StockMovementExport.php`
- `app/Policies/*`
- `database/migrations/2026_01_01_000001_create_stockflow_tables.php`
- `database/seeders/DatabaseSeeder.php`
- `resources/views/landing/index.blade.php`
- `resources/views/pdf/*`
- `public/images/stockflow-logo.svg`
- `tests/Feature/*`

## Notes production restantes

- Brancher paiement abonnement et facturation.
- Ajouter onboarding entreprise et invitation utilisateurs.
- Ajouter traduction Darija via fichiers lang.
- Ajouter numerotation configurable par societe.
- Ajouter audit log, sauvegardes automatiques et monitoring.
- Revoir UI/UX avec vrais utilisateurs marocains avant commercialisation.
- Remplacer le numero WhatsApp placeholder de la landing page.
