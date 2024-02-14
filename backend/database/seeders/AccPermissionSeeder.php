<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class AccPermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $accPermissions = [
            ['menu' => 'Accounts', 'subject' => 'Cost Center', 'name' => 'acc-cost-center-view'],
            ['menu' => 'Accounts', 'subject' => 'Cost Center', 'name' => 'acc-cost-center-create'],
            ['menu' => 'Accounts', 'subject' => 'Cost Center', 'name' => 'acc-cost-center-edit'],
            ['menu' => 'Accounts', 'subject' => 'Cost Center', 'name' => 'acc-cost-center-delete'],
            ['menu' => 'Accounts', 'subject' => 'Balance Income Line', 'name' => 'acc-balance-income-line-view'],
            ['menu' => 'Accounts', 'subject' => 'Balance Income Line', 'name' => 'acc-balance-income-line-create'],
            ['menu' => 'Accounts', 'subject' => 'Balance Income Line', 'name' => 'acc-balance-income-line-edit'],
            ['menu' => 'Accounts', 'subject' => 'Balance Income Line', 'name' => 'acc-balance-income-line-delete'],
            ['menu' => 'Accounts', 'subject' => 'Chart of Account', 'name' => 'acc-chart-of-account-view'],
            ['menu' => 'Accounts', 'subject' => 'Chart of Account', 'name' => 'acc-chart-of-account-create'],
            ['menu' => 'Accounts', 'subject' => 'Chart of Account', 'name' => 'acc-chart-of-account-edit'],
            ['menu' => 'Accounts', 'subject' => 'Chart of Account', 'name' => 'acc-chart-of-account-delete'],
            ['menu' => 'Accounts', 'subject' => 'Opening Balance', 'name' => 'acc-opening-balance-view'],
            ['menu' => 'Accounts', 'subject' => 'Opening Balance', 'name' => 'acc-opening-balance-create'],
            ['menu' => 'Accounts', 'subject' => 'Opening Balance', 'name' => 'acc-opening-balance-edit'],
            ['menu' => 'Accounts', 'subject' => 'Opening Balance', 'name' => 'acc-opening-balance-delete'],
            ['menu' => 'Accounts', 'subject' => 'Bank Account', 'name' => 'acc-bank-account-view'],
            ['menu' => 'Accounts', 'subject' => 'Bank Account', 'name' => 'acc-bank-account-create'],
            ['menu' => 'Accounts', 'subject' => 'Bank Account', 'name' => 'acc-bank-account-edit'],
            ['menu' => 'Accounts', 'subject' => 'Bank Account', 'name' => 'acc-bank-account-delete'],
            ['menu' => 'Accounts', 'subject' => 'Cash Account', 'name' => 'acc-cash-account-view'],
            ['menu' => 'Accounts', 'subject' => 'Cash Account', 'name' => 'acc-cash-account-create'],
            ['menu' => 'Accounts', 'subject' => 'Cash Account', 'name' => 'acc-cash-account-edit'],
            ['menu' => 'Accounts', 'subject' => 'Cash Account', 'name' => 'acc-cash-account-delete'],
            ['menu' => 'Accounts', 'subject' => 'Salary Head', 'name' => 'acc-salary-head-view'],
            ['menu' => 'Accounts', 'subject' => 'Salary Head', 'name' => 'acc-salary-head-create'],
            ['menu' => 'Accounts', 'subject' => 'Salary Head', 'name' => 'acc-salary-head-edit'],
            ['menu' => 'Accounts', 'subject' => 'Salary Head', 'name' => 'acc-salary-head-delete'],
            ['menu' => 'Accounts', 'subject' => 'Voucher', 'name' => 'acc-voucher-view'],
            ['menu' => 'Accounts', 'subject' => 'Voucher', 'name' => 'acc-voucher-create'],
            ['menu' => 'Accounts', 'subject' => 'Voucher', 'name' => 'acc-voucher-edit'],
            ['menu' => 'Accounts', 'subject' => 'Voucher', 'name' => 'acc-voucher-delete'],
            ['menu' => 'Accounts', 'subject' => 'Bank Reconciliation', 'name' => 'acc-bank-reconciliation'],
            ['menu' => 'Accounts', 'subject' => 'Loan', 'name' => 'acc-loan-view'],
            ['menu' => 'Accounts', 'subject' => 'Loan', 'name' => 'acc-loan-create'],
            ['menu' => 'Accounts', 'subject' => 'Loan', 'name' => 'acc-loan-edit'],
            ['menu' => 'Accounts', 'subject' => 'Loan', 'name' => 'acc-loan-delete'],
            ['menu' => 'Accounts', 'subject' => 'Loan Received', 'name' => 'acc-loan-received-view'],
            ['menu' => 'Accounts', 'subject' => 'Loan Received', 'name' => 'acc-loan-received-create'],
            ['menu' => 'Accounts', 'subject' => 'Loan Received', 'name' => 'acc-loan-received-edit'],
            ['menu' => 'Accounts', 'subject' => 'Loan Received', 'name' => 'acc-loan-received-delete'],
            ['menu' => 'Accounts', 'subject' => 'Fixed Asset', 'name' => 'acc-fixed-asset-view'],
            ['menu' => 'Accounts', 'subject' => 'Fixed Asset', 'name' => 'acc-fixed-asset-create'],
            ['menu' => 'Accounts', 'subject' => 'Fixed Asset', 'name' => 'acc-fixed-asset-edit'],
            ['menu' => 'Accounts', 'subject' => 'Fixed Asset', 'name' => 'acc-fixed-asset-delete'],
            ['menu' => 'Accounts', 'subject' => 'Depreciation', 'name' => 'acc-depreciation-view'],
            ['menu' => 'Accounts', 'subject' => 'Depreciation', 'name' => 'acc-depreciation-create'],
            ['menu' => 'Accounts', 'subject' => 'Depreciation', 'name' => 'acc-depreciation-edit'],
            ['menu' => 'Accounts', 'subject' => 'Depreciation', 'name' => 'acc-depreciation-delete'],
            ['menu' => 'Accounts', 'subject' => 'Cash Requisition', 'name' => 'acc-cash-requisition-view'],
            ['menu' => 'Accounts', 'subject' => 'Cash Requisition', 'name' => 'acc-cash-requisition-create'],
            ['menu' => 'Accounts', 'subject' => 'Cash Requisition', 'name' => 'acc-cash-requisition-edit'],
            ['menu' => 'Accounts', 'subject' => 'Cash Requisition', 'name' => 'acc-cash-requisition-delete'],
            ['menu' => 'Accounts', 'subject' => 'Advance Adjustment', 'name' => 'acc-advance-adjustment-view'],
            ['menu' => 'Accounts', 'subject' => 'Advance Adjustment', 'name' => 'acc-advance-adjustment-create'],
            ['menu' => 'Accounts', 'subject' => 'Advance Adjustment', 'name' => 'acc-advance-adjustment-edit'],
            ['menu' => 'Accounts', 'subject' => 'Advance Adjustment', 'name' => 'acc-advance-adjustment-delete'],
            ['menu' => 'Accounts', 'subject' => 'Salary', 'name' => 'acc-salary-view'],
            ['menu' => 'Accounts', 'subject' => 'Salary', 'name' => 'acc-salary-create'],
            ['menu' => 'Accounts', 'subject' => 'Salary', 'name' => 'acc-salary-edit'],
            ['menu' => 'Accounts', 'subject' => 'Salary', 'name' => 'acc-salary-delete'],
            ['menu' => 'Accounts', 'subject' => 'Balance Sheet', 'name' => 'acc-balance-sheet'],
            ['menu' => 'Accounts', 'subject' => 'Income Statement', 'name' => 'acc-income-statement'],
            ['menu' => 'Accounts', 'subject' => 'Ledger', 'name' => 'acc-ledger'],
            ['menu' => 'Accounts', 'subject' => 'Trial Balance', 'name' => 'acc-trial-balance'],
            ['menu' => 'Accounts', 'subject' => 'Day Book', 'name' => 'acc-day-book'],
            ['menu' => 'Accounts', 'subject' => 'Fixed Asset Statement', 'name' => 'acc-fixed-asset-statement'],
            ['menu' => 'Accounts', 'subject' => 'Cost Center Summary', 'name' => 'acc-cost-center-summary'],
            ['menu' => 'Accounts', 'subject' => 'Cost Center Breakup', 'name' => 'acc-cost-center-breakup'],
            ['menu' => 'Accounts', 'subject' => 'Receipt Payment', 'name' => 'acc-receipt-payment'],
        ];

        Permission::insert($accPermissions);
    }
}
