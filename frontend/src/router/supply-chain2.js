import Store from '../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "scm";
const PATH_BASE = "supply-chain";
const ROLE = USER?.role ?? null;
export default [
        /* Service Route start */

        {
            path: `/${BASE}/services`,
            name: `${BASE}.service.index`,
            component: () => import(`../views/${PATH_BASE}/service/index.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'service-index' },
            props: (route) => ({ page: parseInt(route.query.page) || 1 }),
        },
        {
            path: `/${BASE}/service/create`,
            name: `${BASE}.service.create`,
            component: () => import(`../views/${PATH_BASE}/service/create.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'service-create' },
        },
        {
            path: `/${BASE}/service/:serviceId/edit`,
            name: `${BASE}.service.edit`,
            component: () => import(`../views/${PATH_BASE}/service/edit.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'service-edit' },
        },
        {
            path: `/${BASE}/service/:serviceId`,
            name: `${BASE}.service.show`,
            component: () => import(`../views/${PATH_BASE}/service/show.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'service-show'  },
        },

        /* Service Route end */
    
];
