<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Owner Account Short Name
    |--------------------------------------------------------------------------
    |
    | This value is the short name of the owner account. This value is used when
    | the owner export or import voyages on be half of itself rather than a
    | partner account.
    |
     */

    'balance_income_line' => [
        'bank'                      => 8,
        'income_from_sales'         => 49, //@ranks = Sales Accounts
        'income_from_service'       => 57, //@ranks = Income From Service
        'expense_for_sales'         => 75, //@ranks = Direct Expenses
        'expense_for_service'       => 83, //@ranks = Expenses of Service
        'fixed_assets_at_cost'      => 3, //Fixed Assets at Cost (balance_income_line_id:3)
        'inventory'                 => 11, //Fixed Assets at Cost (balance_income_line_id:3)
    ],

    'account_types'       => [
        'Assets'      => 1,
        'Liabilities' => 2,
        'Equity'      => 3,
        'Revenues'    => 4,
        'Expenses'    => 5,
    ],

];
