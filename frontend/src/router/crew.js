import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "crw";
const ROLE = USER?.role ?? null;
export default [
    /* Crew Rank Management Routes */
    {
        path: `/${BASE}/ranks`,
        name: `${BASE}.ranks.index`,
        component: () => import(`../views/crew/rank/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/ranks/create`,
        name: `${BASE}.ranks.create`,
        component: () => import(`../views/crew/rank/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/ranks/:rankId/edit`,
        name: `${BASE}.ranks.edit`,
        component: () => import(`../views/crew/rank/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
];
