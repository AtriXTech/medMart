<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BillingInterval;
use App\Enums\StaffRole;
use App\Enums\SubscriptionStatus;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\CustomerPharmacyLink;
use App\Models\Pharmacy;
use App\Models\PharmacyCode;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Supplier;
use App\Models\User;
use App\Services\Inventory\StockService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoPharmacySeeder extends Seeder
{
    public function run(): void
    {
        $pharmacyA = Pharmacy::factory()->create([
            'name' => 'MedMart Ikeja',
            'slug' => 'medmart-ikeja',
            'email' => 'ikeja@medmart.com',
            'phone' => '08012345678',
            'status' => 'active',
            'timezone' => 'Africa/Lagos',
            'currency' => 'NGN',
            'is_test_account' => true,
            'settings' => [],
        ]);

        $pharmacyB = Pharmacy::factory()->create([
            'name' => 'MedMart Yaba',
            'slug' => 'medmart-yaba',
            'email' => 'yaba@medmart.com',
            'phone' => '08087654321',
            'status' => 'active',
            'timezone' => 'Africa/Lagos',
            'currency' => 'NGN',
            'is_test_account' => true,
            'settings' => [],
        ]);

        $this->createSubscriptionPlans();
        $this->createBanks();
        $this->createSystemRoles($pharmacyA);
        $this->createSystemRoles($pharmacyB);
        $this->createStaff($pharmacyA, $pharmacyB);
        $this->createCustomers($pharmacyA);
        $this->createCategories($pharmacyA);
        $this->createProducts($pharmacyA);
        $this->createSuppliers($pharmacyA);
        $this->createPharmacyCodes($pharmacyA, $pharmacyB);
        $this->createSubscriptions($pharmacyA, $pharmacyB);
    }

    private function createSubscriptionPlans(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price' => 5000,
                'billing_interval' => BillingInterval::Monthly,
                'max_branches' => 1,
                'max_staff' => 5,
                'max_products' => 500,
                'is_active' => true,
                'allowed_durations' => [1, 6, 12],
            ],
            [
                'name' => 'Growth',
                'slug' => 'growth',
                'price' => 15000,
                'billing_interval' => BillingInterval::Monthly,
                'max_branches' => 3,
                'max_staff' => 20,
                'max_products' => 5000,
                'is_active' => true,
                'allowed_durations' => [1, 6, 12, 24],
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => 50000,
                'billing_interval' => BillingInterval::Monthly,
                'max_branches' => 10,
                'max_staff' => 100,
                'max_products' => 50000,
                'is_active' => true,
                'allowed_durations' => [1, 6, 12, 24],
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }

    private function createBanks(): void
    {
        $banks = [
            ['name' => 'Access Bank', 'code' => '044'],
            ['name' => 'Citibank', 'code' => '023'],
            ['name' => 'Ecobank', 'code' => '050'],
            ['name' => 'Fidelity Bank', 'code' => '070'],
            ['name' => 'First Bank', 'code' => '011'],
            ['name' => 'First City Monument Bank', 'code' => '214'],
            ['name' => 'Guaranty Trust Bank', 'code' => '058'],
            ['name' => 'Stanbic IBTC Bank', 'code' => '221'],
            ['name' => 'Union Bank', 'code' => '032'],
            ['name' => 'United Bank for Africa', 'code' => '033'],
            ['name' => 'Zenith Bank', 'code' => '057'],
        ];

        foreach ($banks as $bank) {
            Bank::create($bank);
        }
    }

    private function createSystemRoles(Pharmacy $pharmacy): void
    {
        $roles = [
            [
                'name' => 'Pharmacist',
                'slug' => 'pharmacist',
                'description' => 'Default pharmacist role',
                'permissions' => [
                    'view_dashboard',
                    'manage_products',
                    'manage_categories',
                    'manage_prescriptions',
                    'manage_orders',
                    'view_sales',
                ],
                'is_system' => true,
            ],
            [
                'name' => 'Inventory Manager',
                'slug' => 'inventory_manager',
                'description' => 'Default inventory manager role',
                'permissions' => [
                    'view_dashboard',
                    'manage_products',
                    'manage_categories',
                    'manage_suppliers',
                    'manage_purchase_orders',
                    'view_sales',
                ],
                'is_system' => true,
            ],
            [
                'name' => 'Cashier',
                'slug' => 'cashier',
                'description' => 'Default cashier role',
                'permissions' => [
                    'view_dashboard',
                    'process_sales',
                    'view_sales',
                    'manage_customers',
                ],
                'is_system' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['pharmacy_id' => $pharmacy->id, 'slug' => $roleData['slug']],
                $roleData + ['pharmacy_id' => $pharmacy->id]
            );
        }
    }

    private function createSubscriptions(Pharmacy $pharmacyA, Pharmacy $pharmacyB): void
    {
        $starterPlan = SubscriptionPlan::where('slug', 'starter')->first();

        foreach ([$pharmacyA, $pharmacyB] as $pharmacy) {
            Subscription::create([
                'pharmacy_id' => $pharmacy->id,
                'subscription_plan_id' => $starterPlan->id,
                'status' => SubscriptionStatus::Active,
                'current_period_starts_at' => now(),
                'current_period_ends_at' => now()->addYear(),
            ]);
        }
    }

    private function createStaff(Pharmacy $pharmacyA, Pharmacy $pharmacyB): void
    {
        $ownerA = User::factory()->for($pharmacyA)->create([
            'name' => 'Owner A',
            'email' => 'owner-a@medmart.com',
            'phone' => '08011111111',
            'password' => Hash::make('password123'),
            'role' => StaffRole::Owner,
            'status' => 'active',
        ]);

        $ownerB = User::factory()->for($pharmacyB)->create([
            'name' => 'Owner B',
            'email' => 'owner-b@medmart.com',
            'phone' => '08022222222',
            'password' => Hash::make('password123'),
            'role' => StaffRole::Owner,
            'status' => 'active',
        ]);

        $pharmacistRole = Role::where('pharmacy_id', $pharmacyA->id)->where('slug', 'pharmacist')->first();
        $cashierRole = Role::where('pharmacy_id', $pharmacyA->id)->where('slug', 'cashier')->first();
        $inventoryRole = Role::where('pharmacy_id', $pharmacyA->id)->where('slug', 'inventory_manager')->first();

        User::factory()->for($pharmacyA)->create([
            'name' => 'Pharmacist A',
            'email' => 'pharmacist-a@medmart.com',
            'phone' => '08033333333',
            'password' => Hash::make('password123'),
            'role' => StaffRole::Pharmacist,
            'status' => 'active',
            'staff_role_id' => $pharmacistRole->id,
        ]);

        User::factory()->for($pharmacyA)->create([
            'name' => 'Cashier A',
            'email' => 'cashier-a@medmart.com',
            'phone' => '08044444444',
            'password' => Hash::make('password123'),
            'role' => StaffRole::Cashier,
            'status' => 'active',
            'staff_role_id' => $cashierRole->id,
        ]);

        User::factory()->for($pharmacyA)->create([
            'name' => 'Inventory Manager A',
            'email' => 'inventory-a@medmart.com',
            'phone' => '08055555555',
            'password' => Hash::make('password123'),
            'role' => StaffRole::InventoryManager,
            'status' => 'active',
            'staff_role_id' => $inventoryRole->id,
        ]);
    }

    private function createCustomers(Pharmacy $pharmacy): void
    {
        $customers = [
            ['username' => 'demo_customer', 'email' => 'customer@medmart.com', 'name' => 'John Doe'],
            ['username' => 'jane_smith', 'email' => 'jane@example.com', 'name' => 'Jane Smith'],
            ['username' => 'mike_johnson', 'email' => 'mike@example.com', 'name' => 'Mike Johnson'],
            ['username' => 'sarah_williams', 'email' => 'sarah@example.com', 'name' => 'Sarah Williams'],
            ['username' => 'david_brown', 'email' => 'david@example.com', 'name' => 'David Brown'],
            ['username' => 'emma_davis', 'email' => 'emma@example.com', 'name' => 'Emma Davis'],
            ['username' => 'james_wilson', 'email' => 'james@example.com', 'name' => 'James Wilson'],
        ];

        foreach ($customers as $customerData) {
            $customer = Customer::factory()->create([
                'username' => $customerData['username'],
                'email' => $customerData['email'],
                'name' => $customerData['name'],
                'password' => Hash::make('password123'),
            ]);

            CustomerPharmacyLink::create([
                'customer_id' => $customer->id,
                'pharmacy_id' => $pharmacy->id,
                'is_active' => true,
                'is_suspended' => false,
            ]);
        }
    }

    private function createCategories(Pharmacy $pharmacy): void
    {
        $categories = [
            'Pain Relief',
            'Antibiotics',
            'Vitamins & Supplements',
            'First Aid',
            'Baby Care',
            'Skin Care',
            'Digestive Health',
            'Respiratory',
            'Cardiovascular',
            'Diabetes Care',
        ];

        foreach ($categories as $categoryName) {
            ProductCategory::factory()->for($pharmacy)->create([
                'name' => $categoryName,
            ]);
        }
    }

    private function createProducts(Pharmacy $pharmacy): void
    {
        $categories = ProductCategory::where('pharmacy_id', $pharmacy->id)->get();
        $owner = User::where('pharmacy_id', $pharmacy->id)
            ->where('role', StaffRole::Owner)
            ->first();

        $products = [
            [
                'category' => 'Pain Relief',
                'name' => 'Paracetamol 500mg',
                'generic_name' => 'Acetaminophen',
                'price' => 800,
                'cost_price' => 400,
                'quantity' => 200,
                'requires_prescription' => false,
                'barcode' => '6151234567890',
            ],
            [
                'category' => 'Pain Relief',
                'name' => 'Ibuprofen 400mg',
                'generic_name' => 'Ibuprofen',
                'price' => 1200,
                'cost_price' => 600,
                'quantity' => 150,
                'requires_prescription' => false,
                'barcode' => '6151234567891',
            ],
            [
                'category' => 'Pain Relief',
                'name' => 'Aspirin 300mg',
                'generic_name' => 'Acetylsalicylic Acid',
                'price' => 500,
                'cost_price' => 250,
                'quantity' => 180,
                'requires_prescription' => false,
                'barcode' => '6151234567892',
            ],
            [
                'category' => 'Antibiotics',
                'name' => 'Amoxicillin 500mg',
                'generic_name' => 'Amoxicillin',
                'price' => 2200,
                'cost_price' => 1400,
                'quantity' => 60,
                'requires_prescription' => true,
                'barcode' => '6151234567893',
            ],
            [
                'category' => 'Antibiotics',
                'name' => 'Ciprofloxacin 500mg',
                'generic_name' => 'Ciprofloxacin',
                'price' => 1800,
                'cost_price' => 1200,
                'quantity' => 80,
                'requires_prescription' => true,
                'barcode' => '6151234567894',
            ],
            [
                'category' => 'Vitamins & Supplements',
                'name' => 'Vitamin C 1000mg',
                'generic_name' => 'Ascorbic Acid',
                'price' => 1500,
                'cost_price' => 900,
                'quantity' => 100,
                'requires_prescription' => false,
                'barcode' => '6151234567895',
            ],
            [
                'category' => 'Vitamins & Supplements',
                'name' => 'Multivitamin Complete',
                'generic_name' => 'Multivitamin',
                'price' => 2500,
                'cost_price' => 1800,
                'quantity' => 90,
                'requires_prescription' => false,
                'barcode' => '6151234567896',
            ],
            [
                'category' => 'First Aid',
                'name' => 'Band Aid Pack',
                'generic_name' => 'Adhesive Bandages',
                'price' => 500,
                'cost_price' => 250,
                'quantity' => 300,
                'requires_prescription' => false,
                'barcode' => '6151234567897',
            ],
            [
                'category' => 'First Aid',
                'name' => 'Antiseptic Solution',
                'generic_name' => 'Chlorhexidine',
                'price' => 800,
                'cost_price' => 400,
                'quantity' => 120,
                'requires_prescription' => false,
                'barcode' => '6151234567898',
            ],
            [
                'category' => 'Baby Care',
                'name' => 'Baby Formula 400g',
                'generic_name' => 'Infant Formula',
                'price' => 3500,
                'cost_price' => 2800,
                'quantity' => 50,
                'requires_prescription' => false,
                'barcode' => '6151234567899',
            ],
            [
                'category' => 'Skin Care',
                'name' => 'Hydrocortisone Cream',
                'generic_name' => 'Hydrocortisone',
                'price' => 1800,
                'cost_price' => 1200,
                'quantity' => 80,
                'requires_prescription' => false,
                'barcode' => '6151234567900',
            ],
            [
                'category' => 'Digestive Health',
                'name' => 'Omeprazole 20mg',
                'generic_name' => 'Omeprazole',
                'price' => 2500,
                'cost_price' => 1800,
                'quantity' => 90,
                'requires_prescription' => true,
                'barcode' => '6151234567901',
            ],
        ];

        foreach ($products as $productData) {
            $category = $categories->firstWhere('name', $productData['category']);

            $product = Product::factory()->for($pharmacy)->create([
                'product_category_id' => $category->id,
                'name' => $productData['name'],
                'generic_name' => $productData['generic_name'],
                'price' => $productData['price'],
                'requires_prescription' => $productData['requires_prescription'],
                'barcode' => $productData['barcode'],
                'stock_quantity' => 0,
                'is_available' => true,
            ]);

            app(StockService::class)->receiveBatch($product, [
                'batch_number' => 'BATCH-' . strtoupper(Str::random(6)),
                'expiry_date' => now()->addYear()->toDateString(),
                'quantity' => $productData['quantity'],
                'cost_price' => $productData['cost_price'],
            ], $owner);
        }
    }

    private function createSuppliers(Pharmacy $pharmacy): void
    {
        $suppliers = [
            [
                'name' => 'Emzor Distributors',
                'contact_name' => 'Mr. Adewale',
                'phone' => '08012345678',
                'email' => 'emzor@example.com',
                'address' => '123 Lagos Road, Ikeja',
            ],
            [
                'name' => 'GlaxoSmithKline',
                'contact_name' => 'Mrs. Okonkwo',
                'phone' => '08023456789',
                'email' => 'gsk@example.com',
                'address' => '456 Victoria Island, Lagos',
            ],
            [
                'name' => 'May & Baker',
                'contact_name' => 'Mr. Ibrahim',
                'phone' => '08034567890',
                'email' => 'maybaker@example.com',
                'address' => '789 Opebi Road, Ikeja',
            ],
            [
                'name' => 'Fidson Healthcare',
                'contact_name' => 'Ms. Adebayo',
                'phone' => '08045678901',
                'email' => 'fidson@example.com',
                'address' => '321 Oregun, Ikeja',
            ],
            [
                'name' => 'Swiss Pharma',
                'contact_name' => 'Dr. Okafor',
                'phone' => '08056789012',
                'email' => 'swiss@example.com',
                'address' => '654 Apapa Road, Lagos',
            ],
            [
                'name' => 'PharmaDeko',
                'contact_name' => 'Mr. Balogun',
                'phone' => '08067890123',
                'email' => 'pharmadeko@example.com',
                'address' => '987 Surulere, Lagos',
            ],
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::factory()->for($pharmacy)->create($supplierData);
        }
    }

    private function createPharmacyCodes(Pharmacy $pharmacyA, Pharmacy $pharmacyB): void
    {
        $ownerA = User::where('pharmacy_id', $pharmacyA->id)
            ->where('role', StaffRole::Owner)
            ->first();

        $ownerB = User::where('pharmacy_id', $pharmacyB->id)
            ->where('role', StaffRole::Owner)
            ->first();

        $codes = [
            [
                'pharmacy_id' => $pharmacyA->id,
                'created_by_id' => $ownerA->id,
                'code' => 'IKEJA-DEMO',
                'is_active' => true,
            ],
            [
                'pharmacy_id' => $pharmacyA->id,
                'created_by_id' => $ownerA->id,
                'code' => 'IKEJA-STAFF',
                'is_active' => true,
            ],
            [
                'pharmacy_id' => $pharmacyA->id,
                'created_by_id' => $ownerA->id,
                'code' => 'IKEJA-PREMIUM',
                'is_active' => true,
            ],
            [
                'pharmacy_id' => $pharmacyB->id,
                'created_by_id' => $ownerB->id,
                'code' => 'YABA-DEMO',
                'is_active' => true,
            ],
            [
                'pharmacy_id' => $pharmacyB->id,
                'created_by_id' => $ownerB->id,
                'code' => 'YABA-STAFF',
                'is_active' => true,
            ],
        ];

        foreach ($codes as $codeData) {
            PharmacyCode::create($codeData);
        }
    }
}