<?php

namespace Modules\SupplyChain\Services;

use Modules\Accounts\Entities\AccAccount;
use Modules\SupplyChain\Entities\ScmMaterialCategory;

class AccountDataCreation
{
    public function __invoke()
    {
        $businessUnits = ['PSML', 'TSLL'];
        $data = [];

        foreach ($businessUnits as $unit) {
            $data[] = $this->generateAccountData($unit);
        }

        AccAccount::insert($data);
    }

    private function generateAccountData($businessUnit)
    {
        return [
            'acc_balance_and_income_line_id' => config('accounts.balance_income_line.inventory'),
            'account_name' => 'Bunker',
            'account_code' => $this->generateAccountCode(),
            'account_type' => config('accounts.account_types.Assets'),
            'business_unit' => $businessUnit,
            'accountable_type' => ScmMaterialCategory::class,
            'accountable_id' => 1
        ];
    }

    private function generateAccountCode()
    {
        return config('accounts.account_types.Assets') . ' - ' . config('accounts.balance_income_balance_header.current_assets') . ' - ' . config('accounts.balance_income_line.inventory') . ' - ' . 1;
    }
}
