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
];
