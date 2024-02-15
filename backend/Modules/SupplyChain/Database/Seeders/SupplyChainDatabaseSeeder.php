<?php

namespace Modules\SupplyChain\Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SupplyChainDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplyChainSeeder = [
            [
                'menu' => 'SupplyChain',
                'subject' => 'Unit',
                'name' => 'scm-unit-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Unit',
                'name' => 'scm-unit-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Unit',
                'name' => 'scm-unit-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Unit',
                'name' => 'scm-unit-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Categories',
                'name' => 'scm-material-category-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Categories',
                'name' => 'scm-material-category-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Categories',
                'name' => 'scm-material-category-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Categories',
                'name' => 'scm-material-category-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Materials',
                'name' => 'scm-material-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Materials',
                'name' => 'scm-material-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Materials',
                'name' => 'scm-material-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Materials',
                'name' => 'scm-material-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Warehouses',
                'name' => 'scm-warehouse-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Warehouses',
                'name' => 'scm-warehouse-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Warehouses',
                'name' => 'scm-warehouse-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Warehouses',
                'name' => 'scm-warehouse-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Services',
                'name' => 'scm-service-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Services',
                'name' => 'scm-service-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Services',
                'name' => 'scm-service-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Services',
                'name' => 'scm-service-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Vendors',
                'name' => 'scm-vendor-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Vendors',
                'name' => 'scm-vendor-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Vendors',
                'name' => 'scm-vendor-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Vendors',
                'name' => 'scm-vendor-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Opening Stock',
                'name' => 'scm-opening-stock-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Opening Stock',
                'name' => 'scm-opening-stock-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Opening Stock',
                'name' => 'scm-opening-stock-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Opening Stock',
                'name' => 'scm-opening-stock-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Purchase Requisitions',
                'name' => 'scm-purchase-requisition-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Purchase Requisitions',
                'name' => 'scm-purchase-requisition-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Purchase Requisitions',
                'name' => 'scm-purchase-requisition-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Purchase Requisitions',
                'name' => 'scm-purchase-requisition-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Purchase Requisitions',
                'name' => 'scm-purchase-requisition-close'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material CS',
                'name' => 'scm-material-cs-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material CS',
                'name' => 'scm-material-cs-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material CS',
                'name' => 'scm-material-cs-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material CS',
                'name' => 'scm-material-cs-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material CS',
                'name' => 'scm-material-cs-qoutation-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material CS',
                'name' => 'scm-material-cs-qoutation-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material CS',
                'name' => 'scm-material-cs-qoutation-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material CS',
                'name' => 'scm-material-cs-qoutation-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material CS',
                'name' => 'scm-material-cs-supplier-selection'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material CS',
                'name' => 'scm-material-cs-cost-projection'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Purchase Orders',
                'name' => 'scm-purchase-order-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Purchase Orders',
                'name' => 'scm-purchase-order-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Purchase Orders',
                'name' => 'scm-purchase-order-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Purchase Orders',
                'name' => 'scm-purchase-order-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Purchase Orders',
                'name' => 'scm-purchase-order-close'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'LC Records',
                'name' => 'scm-lc-records-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'LC Records',
                'name' => 'scm-lc-records-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'LC Records',
                'name' => 'scm-lc-records-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'LC Records',
                'name' => 'scm-lc-records-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'LC Records',
                'name' => 'scm-lc-records-close'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Receipt Reports',
                'name' => 'scm-material-receipt-report-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Receipt Reports',
                'name' => 'scm-material-receipt-report-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Receipt Reports',
                'name' => 'scm-material-receipt-report-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Receipt Reports',
                'name' => 'scm-material-receipt-report-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Receipt Reports',
                'name' => 'scm-material-receipt-report-close'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Vendor Bill',
                'name' => 'scm-vendor-bill-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Vendor Bill',
                'name' => 'scm-vendor-bill-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Vendor Bill',
                'name' => 'scm-vendor-bill-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Vendor Bill',
                'name' => 'scm-vendor-bill-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Costing',
                'name' => 'scm-material-costing-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Costing',
                'name' => 'scm-material-costing-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Costing',
                'name' => 'scm-material-costing-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Costing',
                'name' => 'scm-material-costing-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Store Requisition',
                'name' => 'scm-store-requisition-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Store Requisition',
                'name' => 'scm-store-requisition-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Store Requisition',
                'name' => 'scm-store-requisition-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Store Requisition',
                'name' => 'scm-store-requisition-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Store Issue',
                'name' => 'scm-store-issue-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Store Issue',
                'name' => 'scm-store-issue-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Store Issue',
                'name' => 'scm-store-issue-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Store Issue',
                'name' => 'scm-store-issue-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Store Issue Return',
                'name' => 'scm-store-issue-return-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Store Issue Return',
                'name' => 'scm-store-issue-return-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Store Issue Return',
                'name' => 'scm-store-issue-return-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Store Issue Return',
                'name' => 'scm-store-issue-return-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Movement Requisition',
                'name' => 'scm-movement-requisition-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Movement Requisition',
                'name' => 'scm-movement-requisition-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Movement Requisition',
                'name' => 'scm-movement-requisition-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Movement Requisition',
                'name' => 'scm-movement-requisition-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Movement Out',
                'name' => 'scm-movement-out-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Movement Out',
                'name' => 'scm-movement-out-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Movement Out',
                'name' => 'scm-movement-out-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Movement Out',
                'name' => 'scm-movement-out-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Movement In',
                'name' => 'scm-movement-in-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Movement In',
                'name' => 'scm-movement-in-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Movement In',
                'name' => 'scm-movement-in-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Movement In',
                'name' => 'scm-movement-in-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Adjustment',
                'name' => 'scm-material-adjustment-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Adjustment',
                'name' => 'scm-material-adjustment-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Adjustment',
                'name' => 'scm-material-adjustment-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Material Adjustment',
                'name' => 'scm-material-adjustment-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Requisition',
                'name' => 'scm-work-requisition-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Requisition',
                'name' => 'scm-work-requisition-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Requisition',
                'name' => 'scm-work-requisition-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Requisition',
                'name' => 'scm-work-requisition-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Requisition',
                'name' => 'scm-work-requisition-close'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work CS',
                'name' => 'scm-work-cs-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work CS',
                'name' => 'scm-work-cs-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work CS',
                'name' => 'scm-work-cs-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work CS',
                'name' => 'scm-work-cs-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work CS',
                'name' => 'scm-work-cs-close'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work CS',
                'name' => 'scm-work-cs-qoutation-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work CS',
                'name' => 'scm-work-cs-qoutation-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work CS',
                'name' => 'scm-work-cs-qoutation-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work CS',
                'name' => 'scm-work-cs-qoutation-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work CS',
                'name' => 'scm-work-cs-supplier-selection'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Order',
                'name' => 'scm-work-order-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Order',
                'name' => 'scm-work-order-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Order',
                'name' => 'scm-work-order-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Order',
                'name' => 'scm-work-order-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Order',
                'name' => 'scm-work-order-close'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Receipt Report',
                'name' => 'scm-work-receipt-report-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Receipt Report',
                'name' => 'scm-work-receipt-report-create'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Receipt Report',
                'name' => 'scm-work-receipt-report-edit'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Receipt Report',
                'name' => 'scm-work-receipt-report-delete'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Work Receipt Report',
                'name' => 'scm-work-receipt-report-close'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Inventory Report',
                'name' => 'scm-inventory-report-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Stock History Report',
                'name' => 'scm-stock-history-report-view'
            ],
            [
                'menu' => 'SupplyChain',
                'subject' => 'Purchase Requisition Report',
                'name' => 'scm-purchase-requisition-report-view'
            ],
        ];

        Permission::insert($supplyChainSeeder);
    }
}
