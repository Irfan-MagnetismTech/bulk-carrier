import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "crew";
const ROLE = USER?.role ?? null;
export default [
    /* Crew Rank Management Routes */
    // {
    //     path: `/${BASE}/users`,
    //     name: `${BASE}.users.index`,
    //     component: () => import(`../views/${BASE}/user/index.vue`),
    //     meta: { requiresAuth: true, role: ROLE, permission: 'user-show' },
    //     props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    // },
];
