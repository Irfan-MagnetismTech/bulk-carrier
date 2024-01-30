<?php

namespace Modules\Accounts\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Request;
use Modules\Accounts\Entities\AccAccount;
use Modules\Accounts\Entities\AccBalanceAndIncomeLine;
use Modules\Accounts\Entities\AccLedgerEntry;
use Modules\Accounts\Entities\AccTransaction;

//create class
class PaymentReceiptService extends Controller
{

    /**
     * @param $request
     * @return mixed
     */
    public function handlePaymentReceiptSummary($request)
    {
        $cashId = config('accounts.balance_income_line.cash');
        $bankId = config('accounts.balance_income_line.bank');

        $bankAndCashAccounts = AccAccount::whereIn('acc_balance_and_income_line_id', [$cashId, $bankId])->pluck('id');

        $transections = AccTransaction::whereIn('voucher_type', ['Receipt', 'Payment'])
            ->where('transaction_date', '<=', request()->till_date)
            ->whereHas('ledgerEntries', function ($q) use ($cashId, $bankId)
        {
                $q->whereIn('acc_balance_and_income_line_id', [$cashId, $bankId]);
            })->get()->pluck('id');

        // return $transections;

        $allLedgers = AccLedgerEntry::with('transaction:id,voucher_type,transaction_date,instrument_type,business_unit')
            ->whereHas('transaction', function ($q)
        {
                $q->whereIn('voucher_type', ['Receipt', 'Payment'])->where('transaction_date', '<=', request()->till_date);
            })
            ->whereIn('acc_transaction_id', $transections)
            ->with('account:id,acc_balance_and_income_line_id,parent_account_id,account_name,account_code')
            ->with('accBalanceAndIncomeLine:id,line_type,line_text,value_type')
            ->get(['id', 'acc_cost_center_id', 'acc_transaction_id', 'acc_account_id', 'acc_balance_and_income_line_id', 'dr_amount', 'cr_amount']);

        // dd(count($allLedgers));

        [$prevLedgers, $currLedgers]   = $allLedgers->partition(fn($q) => $q->transaction->transaction_date < request()->from_date);
        [$currPayments, $currReceipts] = $currLedgers->partition(fn($q) => $q->transaction->voucher_type == 'Payment');

        $paymentReceiptInfo                     = [];
        $paymentReceiptInfo['opening_balances'] = $this->calculateBalance($prevLedgers, $bankAndCashAccounts);
        $paymentReceiptInfo['closing_balances'] = $this->calculateBalance($allLedgers, $bankAndCashAccounts);
        $paymentReceiptInfo['payments']         = $this->setCurrentLedgers('Payment', $currPayments, $bankAndCashAccounts);
        $paymentReceiptInfo['receipts']         = $this->setCurrentLedgers('Receipt', $currReceipts, $bankAndCashAccounts);
        $paymentReceiptInfo['ttl_payments']     = $paymentReceiptInfo['payments']->pluck('amount')->sum();
        $paymentReceiptInfo['ttl_receipts']     = $paymentReceiptInfo['receipts']->pluck('amount')->sum();

        return $paymentReceiptInfo;
    }

    /**
     * @param $ledgers
     * @param $bankAndCashAccounts
     * @return mixed
     */
    public function calculateBalance($ledgers, $bankAndCashAccounts)
    {

        $balance = $ledgers->whereIn('acc_account_id', $bankAndCashAccounts)->groupBy('acc_balance_and_income_line_id')->map(function ($q, $key)
        {
            return [$q->first()->accBalanceAndIncomeLine->line_text => $q->pluck('dr_amount')->sum() - $q->pluck('cr_amount')->sum()];
        });

        return $balance;
    }

    /**
     * @param $voucherType
     * @param $ledgers
     * @param $bankAndCashAccounts
     * @return mixed
     */
    public function setCurrentLedgers($voucherType, $ledgers, $bankAndCashAccounts)
    {
        $ledgers = $ledgers->whereNotIn('acc_account_id', $bankAndCashAccounts)->groupBy('acc_balance_and_income_line_id')->map(function ($q, $key) use ($voucherType)
        {
            return [
                'line_text' => $q->first()->accBalanceAndIncomeLine->line_text,
                'amount'    => $voucherType == 'Payment' ? $q->pluck('dr_amount')->sum() : $q->pluck('cr_amount')->sum(),
                'accounts'  => $q->groupBy('acc_account_id')->map(function ($q) use ($voucherType)
                {
                    return [
                        'account_name' => $q->first()->account->account_name,
                        'amount'       => $voucherType == 'Payment' ? $q->pluck('dr_amount')->sum() : $q->pluck('cr_amount')->sum(),
                        // "ledgers" => $q
                    ];
                }),
            ];
        });

        return $ledgers;
    }

}
