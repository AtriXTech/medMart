<?php

return [
    'permissions' => [
        'view_dashboard',
        'manage_products',
        'manage_categories',
        'manage_suppliers',
        'manage_purchase_orders',
        'process_sales',
        'view_sales',
        'manage_orders',
        'manage_prescriptions',
        'manage_customers',
        'manage_staff',
        'manage_roles',
        'manage_subscription',
        'manage_settlement',
        'generate_pharmacy_codes',
        'manage_pharmacy_settings',
    ],
    
    'role_defaults' => [
        'pharmacist' => [
            'view_dashboard',
            'manage_products',
            'manage_categories',
            'manage_prescriptions',
            'manage_orders',
            'view_sales',
        ],
        'inventory_manager' => [
            'view_dashboard',
            'manage_products',
            'manage_categories',
            'manage_suppliers',
            'manage_purchase_orders',
            'view_sales',
        ],
        'cashier' => [
            'view_dashboard',
            'process_sales',
            'view_sales',
            'manage_customers',
        ],
    ],
];