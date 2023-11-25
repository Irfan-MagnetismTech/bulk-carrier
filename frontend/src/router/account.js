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
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/balance-income-lines/create`,
        name: `${BASE}.balance-income-lines.create`,
        component: () => import(`../views/${VIEW_BASE}/balance-income-line/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/balance-income-lines/:balanceIncomeLineId/edit`,
        name: `${BASE}.balance-income-lines.edit`,
        component: () => import(`../views/${VIEW_BASE}/balance-income-line/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },

    /* Chart of account Routes */
    {
        path: `/${BASE}/chart-of-accounts`,
        name: `${BASE}.chart-of-accounts.index`,
        component: () => import(`../views/${VIEW_BASE}/chart-of-account/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/chart-of-accounts/create`,
        name: `${BASE}.chart-of-accounts.create`,
        component: () => import(`../views/${VIEW_BASE}/chart-of-account/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/chart-of-accounts/:chartOfAccountId/edit`,
        name: `${BASE}.chart-of-accounts.edit`,
        component: () => import(`../views/${VIEW_BASE}/chart-of-account/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },

    /* Opening Balances Routes */
    {
        path: `/${BASE}/opening-balances`,
        name: `${BASE}.opening-balances.index`,
        component: () => import(`../views/${VIEW_BASE}/opening-balance/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/opening-balances/create`,
        name: `${BASE}.opening-balances.create`,
        component: () => import(`../views/${VIEW_BASE}/opening-balance/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/opening-balances/:openingBalanceId/edit`,
        name: `${BASE}.opening-balances.edit`,
        component: () => import(`../views/${VIEW_BASE}/opening-balance/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },

    /* Bank Accounts Routes */
    {
        path: `/${BASE}/bank-accounts`,
        name: `${BASE}.bank-accounts.index`,
        component: () => import(`../views/${VIEW_BASE}/bank-account/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/bank-accounts/create`,
        name: `${BASE}.bank-accounts.create`,
        component: () => import(`../views/${VIEW_BASE}/bank-account/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/bank-accounts/:bankAccountId/edit`,
        name: `${BASE}.bank-accounts.edit`,
        component: () => import(`../views/${VIEW_BASE}/bank-account/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/bank-accounts/:bankAccountId/show`,
        name: `${BASE}.bank-accounts.show`,
        component: () => import (`../views/${VIEW_BASE}/bank-account/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
    },

    /* Salary head Routes */
    {
        path: `/${BASE}/salary-heads`,
        name: `${BASE}.salary-heads.index`,
        component: () => import(`../views/${VIEW_BASE}/salary-head/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/salary-heads/create`,
        name: `${BASE}.salary-heads.create`,
        component: () => import(`../views/${VIEW_BASE}/salary-head/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/salary-heads/:salaryHeadId/edit`,
        name: `${BASE}.salary-heads.edit`,
        component: () => import(`../views/${VIEW_BASE}/salary-head/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },

    /* Cash Accounts Routes */
    {
        path: `/${BASE}/cash-accounts`,
        name: `${BASE}.cash-accounts.index`,
        component: () => import(`../views/${VIEW_BASE}/cash-account/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/cash-accounts/create`,
        name: `${BASE}.cash-accounts.create`,
        component: () => import(`../views/${VIEW_BASE}/cash-account/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/cash-accounts/:cashAccountId/edit`,
        name: `${BASE}.cash-accounts.edit`,
        component: () => import(`../views/${VIEW_BASE}/cash-account/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },

    /* Transaction Routes */
    {
        path: `/${BASE}/transactions`,
        name: `${BASE}.transactions.index`,
        component: () => import(`../views/${VIEW_BASE}/transaction/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/transactions/create`,
        name: `${BASE}.transactions.create`,
        component: () => import(`../views/${VIEW_BASE}/transaction/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/transactions/:transactionId/edit`,
        name: `${BASE}.transactions.edit`,
        component: () => import(`../views/${VIEW_BASE}/transaction/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/transactions/:transactionId`,
        name: `${BASE}.transactions.show`,
        component: () => import(`../views/${BASE}/transactions/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },

    
    /* Loan Routes */
    {
        path: `/${BASE}/loans`,
        name: `${BASE}.loans.index`,
        component: () => import(`../views/${VIEW_BASE}/loan/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/loans/create`,
        name: `${BASE}.loans.create`,
        component: () => import(`../views/${VIEW_BASE}/loan/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/loans/:loanId/edit`,
        name: `${BASE}.loans.edit`,
        component: () => import(`../views/${VIEW_BASE}/loan/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/loans/:loanId`,
        name: `${BASE}.loans.show`,
        component: () => import(`../views/${BASE}/loan/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    
    /* Loan Received Routes */
    {
        path: `/${BASE}/loan-received`,
        name: `${BASE}.loan-received.index`,
        component: () => import(`../views/${VIEW_BASE}/loan-received/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/loan-received/create`,
        name: `${BASE}.loan-received.create`,
        component: () => import(`../views/${VIEW_BASE}/loan-received/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/loan-received/:loanReceivedId/edit`,
        name: `${BASE}.loan-received.edit`,
        component: () => import(`../views/${VIEW_BASE}/loan-received/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/loan-received/:loanReceivedId`,
        name: `${BASE}.loan-received.show`,
        component: () => import(`../views/${BASE}/loan-received/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },

    /* Bank Reconciliation Routes */
    {
        path: `/${BASE}/bank-reconciliation`,
        name: `${BASE}.bank-reconciliation.index`,
        component: () => import(`../views/${VIEW_BASE}/bank-reconciliation/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },    

    /* AIS Report Routes */
    {
        path: `/${BASE}/ais-reports/balance-sheet`,
        name: `${BASE}.ais-reports.balance-sheet`,
        component: () => import(`../views/${VIEW_BASE}/ais-report/balance-sheet.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/ais-reports/income-statement`,
        name: `${BASE}.ais-reports.income-statement`,
        component: () => import(`../views/${VIEW_BASE}/ais-report/income-statement.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/ais-reports/ledger`,
        name: `${BASE}.ais-reports.ledger`,
        component: () => import(`../views/${VIEW_BASE}/ais-report/ledger.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/ais-reports/trial-balance`,
        name: `${BASE}.ais-reports.trial-balance`,
        component: () => import(`../views/${VIEW_BASE}/ais-report/trial-balance.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/ais-reports/day-book`,
        name: `${BASE}.ais-reports.day-book`,
        component: () => import(`../views/${VIEW_BASE}/ais-report/day-book.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
];
