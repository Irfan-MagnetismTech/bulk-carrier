import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "supply-chain";
const ROLE = USER?.role ?? null;
export default [

    /* Unit Route start */
    
    {
        path: `/${BASE}/units`,
        name: `${BASE}.unit.index`,
        component: () => import(`../views/${BASE}/unit/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'unit-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/unit/create`,
        name: `${BASE}.unit.create`,
        component: () => import(`../views/${BASE}/unit/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'unit-create' },
    },
    {
        path: `/${BASE}/unit/:unitId/edit`,
        name: `${BASE}.unit.edit`,
        component: () => import(`../views/${BASE}/unit/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'unit-edit' },
    },
    {
        path: `/${BASE}/unit/:unitId`,
        name: `${BASE}.unit.show`,
        component: () => import(`../views/${BASE}/unit/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'unit-show'  },
    },

    /* Unit Route end */
];
