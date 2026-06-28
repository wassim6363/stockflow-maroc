<?php

namespace App\Exports;

use App\Models\StockMovement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockMovementExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return StockMovement::with('product', 'warehouse')->latest()->get();
    }

    public function headings(): array
    {
        return ['date', 'product', 'warehouse', 'type', 'quantity', 'unit_cost', 'notes'];
    }

    public function map($movement): array
    {
        return [
            $movement->created_at?->format('Y-m-d H:i'),
            $movement->product?->name,
            $movement->warehouse?->name,
            $movement->type,
            $movement->quantity,
            $movement->unit_cost,
            $movement->notes,
        ];
    }
}
