import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "acc";
const VIEW_BASE = "accounts";
const ROLE = USER?.role ?? null;
export default [
    /* Account Cost Center Routes */
    {
        path: `/${BASE}/cost-centers`,
        name: `${BASE}.cost-centers.index`,
        component: () => import(`../views/${VIEW_BASE}/cost-center/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/cost-centers/create`,
        name: `${BASE}.cost-centers.create`,
        component: () => import(`../views/${VIEW_BASE}/cost-center/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/cost-centers/:costCenterId/edit`,
        name: `${BASE}.cost-centers.edit`,
        component: () => import(`../views/${VIEW_BASE}/cost-center/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },

    /* Account Balance Income Line Routes */
    {
        path: `/${BASE}/balance-income-lines`,
        name: `${BASE}.balance-income-lines.index`,
        component: () => import(`../views/${VIEW_BASE}/balance-income-line/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-balance-income-line-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/balance-income-lines/create`,
        name: `${BASE}.balance-income-lines.create`,
        component: () => import(`../views/${VIEW_BASE}/balance-income-line/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-balance-income-line-create' },
    },
    {
        path: `/${BASE}/balance-income-lines/:balanceIncomeLineId/edit`,
        name: `${BASE}.balance-income-lines.edit`,
        component: () => import(`../views/${VIEW_BASE}/balance-income-line/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-balance-income-line-edit' },
    },

    /* Chart of account Routes */
    {
        path: `/${BASE}/chart-of-accounts`,
        name: `${BASE}.chart-of-accounts.index`,
        component: () => import(`../views/${VIEW_BASE}/chart-of-account/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-chart-of-account-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/chart-of-accounts/create`,
        name: `${BASE}.chart-of-accounts.create`,
        component: () => import(`../views/${VIEW_BASE}/chart-of-account/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-chart-of-account-create' },
    },
    {
        path: `/${BASE}/chart-of-accounts/:chartOfAccountId/edit`,
        name: `${BASE}.chart-of-accounts.edit`,
        component: () => import(`../views/${VIEW_BASE}/chart-of-account/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-chart-of-account-edit' },
    },

    /* Opening Balances Routes */
    {
        path: `/${BASE}/opening-balances`,
        name: `${BASE}.opening-balances.index`,
        component: () => import(`../views/${VIEW_BASE}/opening-balance/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-opening-balance-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/opening-balances/create`,
        name: `${BASE}.opening-balances.create`,
        component: () => import(`../views/${VIEW_BASE}/opening-balance/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-opening-balance-create' },
    },
    {
        path: `/${BASE}/opening-balances/:openingBalanceId/edit`,
        name: `${BASE}.opening-balances.edit`,
        component: () => import(`../views/${VIEW_BASE}/opening-balance/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-opening-balance-edit' },
    },

    /* Bank Accounts Routes */
    {
        path: `/${BASE}/bank-accounts`,
        name: `${BASE}.bank-accounts.index`,
        component: () => import(`../views/${VIEW_BASE}/bank-account/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-bank-account-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/bank-accounts/create`,
        name: `${BASE}.bank-accounts.create`,
        component: () => import(`../views/${VIEW_BASE}/bank-account/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-bank-account-create' },
    },
    {
        path: `/${BASE}/bank-accounts/:bankAccountId/edit`,
        name: `${BASE}.bank-accounts.edit`,
        component: () => import(`../views/${VIEW_BASE}/bank-account/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-bank-account-edit' },
    },
    {
        path: `/${BASE}/bank-accounts/:bankAccountId/show`,
        name: `${BASE}.bank-accounts.show`,
        component: () => import (`../views/${VIEW_BASE}/bank-account/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: 'acc-bank-account-view' },
    },

    /* Salary head Routes */
    {
        path: `/${BASE}/salary-heads`,
        name: `${BASE}.salary-heads.index`,
        component: () => import(`../views/${VIEW_BASE}/salary-head/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-salary-head-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/salary-heads/create`,
        name: `${BASE}.salary-heads.create`,
        component: () => import(`../views/${VIEW_BASE}/salary-head/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-salary-head-create' },
    },
    {
        path: `/${BASE}/salary-heads/:salaryHeadId/edit`,
        name: `${BASE}.salary-heads.edit`,
        component: () => import(`../views/${VIEW_BASE}/salary-head/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-salary-head-edit' },
    },

    /* Cash Accounts Routes */
    {
        path: `/${BASE}/cash-accounts`,
        name: `${BASE}.cash-accounts.index`,
        component: () => import(`../views/${VIEW_BASE}/cash-account/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-cash-account-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/cash-accounts/create`,
        name: `${BASE}.cash-accounts.create`,
        component: () => import(`../views/${VIEW_BASE}/cash-account/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-cash-account-create' },
    },
    {
        path: `/${BASE}/cash-accounts/:cashAccountId/edit`,
        name: `${BASE}.cash-accounts.edit`,
        component: () => import(`../views/${VIEW_BASE}/cash-account/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-cash-account-edit' },
    },

    /* Transaction Routes */
    {
        path: `/${BASE}/transactions`,
        name: `${BASE}.transactions.index`,
        component: () => import(`../views/${VIEW_BASE}/transaction/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-voucher-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/transactions/create`,
        name: `${BASE}.transactions.create`,
        component: () => import(`../views/${VIEW_BASE}/transaction/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-voucher-create' },
    },
    {
        path: `/${BASE}/transactions/:transactionId/edit`,
        name: `${BASE}.transactions.edit`,
        component: () => import(`../views/${VIEW_BASE}/transaction/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-voucher-edit' },
    },
    {
        path: `/${BASE}/transactions/:transactionId`,
        name: `${BASE}.transactions.show`,
        component: () => import(`../views/${VIEW_BASE}/transaction/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-voucher-view' },
    },

    /* Bank Reconciliation Routes */
    {
        path: `/${BASE}/bank-reconciliation`,
        name: `${BASE}.bank-reconciliation.index`,
        component: () => import(`../views/${VIEW_BASE}/bank-reconciliation/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-bank-reconciliation' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },

    /* Loan Routes */
    {
        path: `/${BASE}/loans`,
        name: `${BASE}.loans.index`,
        component: () => import(`../views/${VIEW_BASE}/loan/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-loan-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/loans/create`,
        name: `${BASE}.loans.create`,
        component: () => import(`../views/${VIEW_BASE}/loan/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-loan-create' },
    },
    {
        path: `/${BASE}/loans/:loanId/edit`,
        name: `${BASE}.loans.edit`,
        component: () => import(`../views/${VIEW_BASE}/loan/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-loan-edit' },
    },
    {
        path: `/${BASE}/loans/:loanId`,
        name: `${BASE}.loans.show`,
        component: () => import(`../views/${VIEW_BASE}/loan/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-loan-view' },
    },
    
    /* Loan Received Routes */
    {
        path: `/${BASE}/loan-received`,
        name: `${BASE}.loan-received.index`,
        component: () => import(`../views/${VIEW_BASE}/loan-received/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-loan-received-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/loan-received/create`,
        name: `${BASE}.loan-received.create`,
        component: () => import(`../views/${VIEW_BASE}/loan-received/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-loan-received-create' },
    },
    {
        path: `/${BASE}/loan-received/:loanReceivedId/edit`,
        name: `${BASE}.loan-received.edit`,
        component: () => import(`../views/${VIEW_BASE}/loan-received/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-loan-received-edit' },
    },
    {
        path: `/${BASE}/loan-received/:loanReceivedId`,
        name: `${BASE}.loan-received.show`,
        component: () => import(`../views/${VIEW_BASE}/loan-received/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-loan-received-view' },
    },
    
    /* Fixed Assets */
    {
        path: `/${BASE}/fixed-assets`,
        name: `${BASE}.fixed-assets.index`,
        component: () => import(`../views/${VIEW_BASE}/fixed-asset/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-fixed-asset-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/fixed-assets/create`,
        name: `${BASE}.fixed-assets.create`,
        component: () => import(`../views/${VIEW_BASE}/fixed-asset/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-fixed-asset-create' },
    },
    {
        path: `/${BASE}/fixed-assets/:fixedAssetId/edit`,
        name: `${BASE}.fixed-assets.edit`,
        component: () => import(`../views/${VIEW_BASE}/fixed-asset/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-fixed-asset-edit' },
    },
    {
        path: `/${BASE}/fixed-assets/:fixedAssetId`,
        name: `${BASE}.fixed-assets.show`,
        component: () => import(`../views/${VIEW_BASE}/fixed-asset/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-fixed-asset-view' },
    },

    /* Depreciation route */
    {
        path: `/${BASE}/depreciations`,
        name: `${BASE}.depreciations.index`,
        component: () => import(`../views/${VIEW_BASE}/depreciation/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-depreciation-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/depreciations/create`,
        name: `${BASE}.depreciations.create`,
        component: () => import(`../views/${VIEW_BASE}/depreciation/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-depreciation-create' },
    },
    {
        path: `/${BASE}/depreciations/:depreciationId/edit`,
        name: `${BASE}.depreciations.edit`,
        component: () => import(`../views/${VIEW_BASE}/depreciation/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-depreciation-edit' },
    },
    {
        path: `/${BASE}/depreciations/:depreciationId`,
        name: `${BASE}.depreciations.show`,
        component: () => import(`../views/${VIEW_BASE}/depreciation/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-depreciation-view' },
    },
    
    /* Cash Requisitions */
    {
        path: `/${BASE}/cash-requisitions`,
        name: `${BASE}.cash-requisitions.index`,
        component: () => import(`../views/${VIEW_BASE}/cash-requisition/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-cash-requisition-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/cash-requisitions/create`,
        name: `${BASE}.cash-requisitions.create`,
        component: () => import(`../views/${VIEW_BASE}/cash-requisition/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-cash-requisition-create' },
    },
    {
        path: `/${BASE}/cash-requisitions/:cashRequisitionId/edit`,
        name: `${BASE}.cash-requisitions.edit`,
        component: () => import(`../views/${VIEW_BASE}/cash-requisition/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-cash-requisition-edit' },
    },
    {
        path: `/${BASE}/cash-requisitions/:cashRequisitionId`,
        name: `${BASE}.cash-requisitions.show`,
        component: () => import(`../views/${VIEW_BASE}/cash-requisition/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-cash-requisition-view' },
    },

    /* Advance Adjustments */
    {
        path: `/${BASE}/advance-adjustments`,
        name: `${BASE}.advance-adjustments.index`,
        component: () => import(`../views/${VIEW_BASE}/advance-adjustment/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-advance-adjustment-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/advance-adjustments/create`,
        name: `${BASE}.advance-adjustments.create`,
        component: () => import(`../views/${VIEW_BASE}/advance-adjustment/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-advance-adjustment-create' },
    },
    {
        path: `/${BASE}/advance-adjustments/:advanceAdjustmentId/edit`,
        name: `${BASE}.advance-adjustments.edit`,
        component: () => import(`../views/${VIEW_BASE}/advance-adjustment/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-advance-adjustment-edit' },
    },
    {
        path: `/${BASE}/advance-adjustments/:advanceAdjustmentId`,
        name: `${BASE}.advance-adjustments.show`,
        component: () => import(`../views/${VIEW_BASE}/advance-adjustment/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-advance-adjustment-view' },
    },

    /* Administrative Salary */
    {
        path: `/${BASE}/administrative-salaries`,
        name: `${BASE}.administrative-salaries.index`,
        component: () => import(`../views/${VIEW_BASE}/administrative-salary/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-salary-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/administrative-salaries/create`,
        name: `${BASE}.administrative-salaries.create`,
        component: () => import(`../views/${VIEW_BASE}/administrative-salary/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-salary-create' },
    },
    {
        path: `/${BASE}/administrative-salaries/:administrativeSalaryId/edit`,
        name: `${BASE}.administrative-salaries.edit`,
        component: () => import(`../views/${VIEW_BASE}/administrative-salary/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-salary-edit' },
    },
    {
        path: `/${BASE}/administrative-salaries/:administrativeSalaryId`,
        name: `${BASE}.administrative-salaries.show`,
        component: () => import(`../views/${VIEW_BASE}/administrative-salary/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-salary-view' },
    },

    /* AIS Report Routes */
    {
        path: `/${BASE}/ais-reports/balance-sheet`,
        name: `${BASE}.ais-reports.balance-sheet`,
        component: () => import(`../views/${VIEW_BASE}/ais-report/balance-sheet.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-balance-sheet' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/ais-reports/income-statement`,
        name: `${BASE}.ais-reports.income-statement`,
        component: () => import(`../views/${VIEW_BASE}/ais-report/income-statement.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-income-statement' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/ais-reports/ledger`,
        name: `${BASE}.ais-reports.ledger`,
        component: () => import(`../views/${VIEW_BASE}/ais-report/ledger.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-ledger' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/ais-reports/trial-balance`,
        name: `${BASE}.ais-reports.trial-balance`,
        component: () => import(`../views/${VIEW_BASE}/ais-report/trial-balance.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-trial-balance' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/ais-reports/day-book`,
        name: `${BASE}.ais-reports.day-book`,
        component: () => import(`../views/${VIEW_BASE}/ais-report/day-book.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-day-book' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/ais-reports/fixed-asset-statement`,
        name: `${BASE}.ais-reports.fixed-asset-statement`,
        component: () => import(`../views/${VIEW_BASE}/ais-report/fixed-asset-statement.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-fixed-asset-statement' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/ais-reports/cost-center-summary`,
        name: `${BASE}.ais-reports.cost-center-summary`,
        component: () => import(`../views/${VIEW_BASE}/ais-report/cost-center-summary.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-cost-center-summary' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/ais-reports/cost-center-breakup`,
        name: `${BASE}.ais-reports.cost-center-breakup`,
        component: () => import(`../views/${VIEW_BASE}/ais-report/cost-center-breakup.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-cost-center-breakup' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/ais-reports/receipt-payment-statement`,
        name: `${BASE}.ais-reports.receipt-payment-statement`,
        component: () => import(`../views/${VIEW_BASE}/ais-report/receipt-payment-statement.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'acc-receipt-payment' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },

];
