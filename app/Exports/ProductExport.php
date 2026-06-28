<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::with('category', 'stockLevels.warehouse')->get();
    }

    public function headings(): array
    {
        return ['name', 'sku', 'barcode', 'category', 'unit', 'purchase_price', 'sale_price', 'min_stock', 'current_stock'];
    }

    public function map($product): array
    {
        return [
            $product->name,
            $product->sku,
            $product->barcode,
            $product->category?->name,
            $product->unit,
            $product->purchase_price,
            $product->sale_price,
            $product->min_stock,
            $product->current_stock,
        ];
    }
}
