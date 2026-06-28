<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToModel, WithHeadingRow, WithValidation
{
    public function __construct(private readonly int $companyId, private readonly ?int $userId = null) {}

    public function model(array $row)
    {
        Validator::make($row, $this->rules())->validate();

        $category = null;
        if (! empty($row['category'])) {
            $category = Category::firstOrCreate(
                ['company_id' => $this->companyId, 'name' => trim($row['category'])],
                ['is_active' => true],
            );
        }

        $warehouseName = trim($row['warehouse'] ?? '') ?: 'Depot principal';
        $warehouse = Warehouse::firstOrCreate(
            ['company_id' => $this->companyId, 'name' => $warehouseName],
            ['code' => 'MAIN', 'is_default' => true, 'is_active' => true],
        );

        $product = Product::create([
            'company_id' => $this->companyId,
            'category_id' => $category?->id,
            'name' => $row['name'],
            'sku' => $row['sku'] ?? null,
            'barcode' => $row['barcode'] ?? null,
            'unit' => $row['unit'] ?? 'piece',
            'purchase_price' => $row['purchase_price'] ?? 0,
            'sale_price' => $row['sale_price'] ?? 0,
            'min_stock' => $row['min_stock'] ?? 0,
            'is_active' => true,
        ]);

        app(StockService::class)->addInitialStock(
            $this->companyId,
            $warehouse->id,
            $product->id,
            (float) ($row['initial_quantity'] ?? 0),
            $this->userId,
        );

        return $product;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'unit' => ['nullable', 'in:piece,kg,litre,carton,pack,metre'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'initial_quantity' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
