import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "acc";
const VIEW_BASE = "accounts";
const ROLE = USER?.role ?? null;
export default [
    /* Account Cost Center Routes */


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
];
