<?php

return [
    'plans' => [
        'free' => ['price' => 0, 'warehouses' => 1, 'products' => 50, 'movements_per_month' => 100, 'pdf_watermark' => true],
        'starter' => ['price' => 99, 'warehouses' => 1, 'products' => 300, 'movements_per_month' => null, 'pdf_watermark' => false],
        'pro' => ['price' => 199, 'warehouses' => 3, 'products' => 2000, 'movements_per_month' => null, 'pdf_watermark' => false],
        'enterprise' => ['price' => 499, 'warehouses' => null, 'products' => null, 'movements_per_month' => null, 'pdf_watermark' => false],
    ],
];
