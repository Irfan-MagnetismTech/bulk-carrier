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

        
        /* Work Requisition Route start */

        {
            path: `/${BASE}/work-requisitions`,
            name: `${BASE}.work-requisition.index`,
            component: () => import(`../views/${PATH_BASE}/work-requisition/index.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'work-requisition-index' },
            props: (route) => ({ page: parseInt(route.query.page) || 1 }),
        },
        {
            path: `/${BASE}/work-requisition/create`,
            name: `${BASE}.work-requisition.create`,
            component: () => import(`../views/${PATH_BASE}/work-requisition/create.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'work-requisition-create' },
        },
        {
            path: `/${BASE}/work-requisition/:workRequisitionId/edit`,
            name: `${BASE}.work-requisition.edit`,
            component: () => import(`../views/${PATH_BASE}/work-requisition/edit.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'work-requisition-edit' },
        },
        {
            path: `/${BASE}/work-requisition/:workRequisitionId`,
            name: `${BASE}.work-requisition.show`,
            component: () => import(`../views/${PATH_BASE}/work-requisition/show.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'work-requisition-show'  },
        },

        /* Work Requisition Route end */


    
];
