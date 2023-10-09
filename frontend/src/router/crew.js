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

    /* Crew Policy Management Routes */
    {
        path: `/${BASE}/policies`,
        name: `${BASE}.policies.index`,
        component: () => import(`../views/crew/policy/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/policies/create`,
        name: `${BASE}.policies.create`,
        component: () => import(`../views/crew/policy/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/policies/:policyId/edit`,
        name: `${BASE}.policies.edit`,
        component: () => import(`../views/crew/policy/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    /* Crew Onboard Checklist Routes */
    {
        path: `/${BASE}/checklists`,
        name: `${BASE}.checklists.index`,
        component: () => import(`../views/crew/checklist/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/checklists/create`,
        name: `${BASE}.checklists.create`,
        component: () => import(`../views/crew/checklist/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/checklists/:checkListId/edit`,
        name: `${BASE}.checklists.edit`,
        component: () => import(`../views/crew/checklist/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
];
