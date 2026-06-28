<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Customer;
use App\Models\InventoryCount;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\StockLevel;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::create([
            'name' => 'StockFlow Demo SARL',
            'ice' => '003456789000021',
            'rc' => '123456',
            'email' => 'contact@stockflow.ma',
            'phone' => '+212 522 00 00 00',
            'address' => 'Boulevard Abdelmoumen',
            'city' => 'Casablanca',
            'subscription_plan' => 'pro',
            'invoice_footer_text' => 'Merci pour votre confiance.',
        ]);

        User::create(['name' => 'Super Admin', 'email' => 'admin@stockflow.ma', 'password' => Hash::make('password'), 'role' => 'super_admin']);
        $admin = User::create(['company_id' => $company->id, 'name' => 'Company Admin', 'email' => 'company@stockflow.ma', 'password' => Hash::make('password'), 'role' => 'company_admin']);
        User::create(['company_id' => $company->id, 'name' => 'Stock Manager', 'email' => 'stock@stockflow.ma', 'password' => Hash::make('password'), 'role' => 'stock_manager']);
        User::create(['company_id' => $company->id, 'name' => 'Cashier', 'email' => 'cashier@stockflow.ma', 'password' => Hash::make('password'), 'role' => 'cashier']);

        $warehouse = Warehouse::create(['company_id' => $company->id, 'name' => 'Depot principal', 'code' => 'MAIN', 'is_default' => true]);

        $categories = collect(['Cosmetiques', 'Pieces auto', 'Alimentation', 'Accessoires', 'E-commerce'])
            ->map(fn (string $name) => Category::create(['company_id' => $company->id, 'name' => $name]));

        $suppliers = collect(['Atlas Distribution', 'Casa Wholesale', 'Maroc Supply'])
            ->map(fn (string $name) => Supplier::create(['company_id' => $company->id, 'name' => $name, 'city' => 'Casablanca', 'phone' => '+212 600 000 000']));

        $customers = collect(['Client Comptoir', 'Boutique Noor', 'Pieces Auto Rabat', 'Epicerie Amal', 'Instagram Shop'])
            ->map(fn (string $name) => Customer::create(['company_id' => $company->id, 'name' => $name, 'city' => 'Casablanca', 'phone' => '+212 611 111 111']));

        $products = collect(range(1, 20))->map(function (int $i) use ($company, $categories) {
            return Product::create([
                'company_id' => $company->id,
                'category_id' => $categories->random()->id,
                'name' => "Produit demo {$i}",
                'sku' => sprintf('SF-%03d', $i),
                'barcode' => sprintf('61100000%04d', $i),
                'unit' => 'piece',
                'purchase_price' => 10 + $i,
                'sale_price' => 18 + $i,
                'min_stock' => $i <= 3 ? 25 : 5,
                'tax_rate' => 20,
            ]);
        });

        foreach ($products as $index => $product) {
            StockLevel::create([
                'company_id' => $company->id,
                'warehouse_id' => $warehouse->id,
                'product_id' => $product->id,
                'quantity' => $index < 3 ? 3 : 30 + $index,
            ]);
        }

        $stock = app(StockService::class);

        foreach ([0, 1] as $index) {
            $order = PurchaseOrder::create([
                'company_id' => $company->id,
                'supplier_id' => $suppliers[$index]->id,
                'warehouse_id' => $warehouse->id,
                'status' => 'confirmed',
                'order_date' => now()->subDays(10 - $index),
                'user_id' => $admin->id,
            ]);
            $order->lines()->create(['product_id' => $products[$index]->id, 'quantity' => 10, 'unit_price' => $products[$index]->purchase_price, 'tax_rate' => 20]);
            $stock->receivePurchase($order, $admin->id);
        }

        foreach ([2, 3, 4] as $index) {
            $order = SalesOrder::create([
                'company_id' => $company->id,
                'customer_id' => $customers[$index % $customers->count()]->id,
                'warehouse_id' => $warehouse->id,
                'status' => 'confirmed',
                'sale_date' => now()->subDays($index),
                'paid_amount' => 0,
                'payment_method' => 'cash',
                'user_id' => $admin->id,
            ]);
            $order->lines()->create(['product_id' => $products[$index]->id, 'quantity' => 2, 'unit_price' => $products[$index]->sale_price, 'tax_rate' => 20]);
            $stock->deliverSale($order, $admin->id);
        }

        $inventory = InventoryCount::create([
            'company_id' => $company->id,
            'warehouse_id' => $warehouse->id,
            'count_date' => now(),
            'user_id' => $admin->id,
        ]);
        $inventory->lines()->create(['product_id' => $products->last()->id, 'system_quantity' => 49, 'counted_quantity' => 52]);
        $stock->validateInventory($inventory, $admin->id);
    }
}
