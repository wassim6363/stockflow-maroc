<?php

namespace App\Services;

use App\Models\InventoryCount;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    public function salesOrder(SalesOrder $order): string
    {
        return Pdf::loadView('pdf.sales_order_pdf', ['order' => $order->load('company', 'customer', 'warehouse', 'lines.product')])->output();
    }

    public function purchaseOrder(PurchaseOrder $order): string
    {
        return Pdf::loadView('pdf.purchase_order_pdf', ['order' => $order->load('company', 'supplier', 'warehouse', 'lines.product')])->output();
    }

    public function inventoryReport(InventoryCount $count): string
    {
        return Pdf::loadView('pdf.inventory_report_pdf', ['count' => $count->load('company', 'warehouse', 'lines.product')])->output();
    }
}
