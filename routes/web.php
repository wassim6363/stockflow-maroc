<?php

use Illuminate\Support\Facades\Route;
use App\Models\InventoryCount;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Services\PdfService;

Route::get('/', function () {
    return view('landing.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/pdf/sales/{order}', fn (SalesOrder $order, PdfService $pdf) => response($pdf->salesOrder($order), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $order->reference . '.pdf"',
    ]))->name('pdf.sale');

    Route::get('/pdf/purchases/{order}', fn (PurchaseOrder $order, PdfService $pdf) => response($pdf->purchaseOrder($order), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $order->reference . '.pdf"',
    ]))->name('pdf.purchase');

    Route::get('/pdf/inventories/{count}', fn (InventoryCount $count, PdfService $pdf) => response($pdf->inventoryReport($count), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $count->reference . '.pdf"',
    ]))->name('pdf.inventory');
});
