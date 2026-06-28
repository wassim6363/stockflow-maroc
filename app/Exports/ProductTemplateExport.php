<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['name', 'sku', 'barcode', 'category', 'unit', 'purchase_price', 'sale_price', 'min_stock', 'initial_quantity', 'warehouse'];
    }

    public function array(): array
    {
        return [
            ['Shampoing Argan 250ml', 'ARG-250', '611000000001', 'Cosmetiques', 'piece', 22, 35, 10, 25, 'Depot principal'],
        ];
    }
}
