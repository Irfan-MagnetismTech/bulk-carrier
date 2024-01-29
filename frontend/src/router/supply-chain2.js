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
            name: `${BASE}.work-requisitions.index`,
            component: () => import(`../views/${PATH_BASE}/work-requisition/index.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'work-requisition-index' },
            props: (route) => ({ page: parseInt(route.query.page) || 1 }),
        },
        {
            path: `/${BASE}/work-requisitions/create`,
            name: `${BASE}.work-requisitions.create`,
            component: () => import(`../views/${PATH_BASE}/work-requisition/create.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'work-requisition-create' },
        },
        {
            path: `/${BASE}/work-requisitions/:workRequisitionId/edit`,
            name: `${BASE}.work-requisitions.edit`,
            component: () => import(`../views/${PATH_BASE}/work-requisition/edit.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'work-requisition-edit' },
        },
        {
            path: `/${BASE}/work-requisitions/:workRequisitionId`,
            name: `${BASE}.work-requisitions.show`,
            component: () => import(`../views/${PATH_BASE}/work-requisition/show.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'work-requisition-show'  },
        },

        /* Work Requisition Route end */
        /* Work Cs start */

        {
            path: `/${BASE}/work-cs`,
            name: `${BASE}.work-cs.index`,
            component: () => import(`../views/${PATH_BASE}/work-cs/index.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'work-cs-index' },
            props: (route) => ({ page: parseInt(route.query.page) || 1 }),
        },
        {
            path: `/${BASE}/work-cs/create`,
            name: `${BASE}.work-cs.create`,
            component: () => import(`../views/${PATH_BASE}/work-cs/create.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'work-cs-create' },
        },
        {
            path: `/${BASE}/work-cs/:workCsId/edit`,
            name: `${BASE}.work-cs.edit`,
            component: () => import(`../views/${PATH_BASE}/work-cs/edit.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'work-cs-edit' },
        },
        {
            path: `/${BASE}/work-cs/:workCsId`,
            name: `${BASE}.work-cs.show`,
            component: () => import(`../views/${PATH_BASE}/work-cs/show.vue`),
            meta: { requiresAuth: true, role: ROLE, permission: 'work-cs-show'  },
        },
    /* Work Cs end */
    /* Quotation Start */
    {
        path: `/${BASE}/wcs-quotations/:wcsId/create`,
        name: `${BASE}.wcs-quotations.create`,
        component: () => import(`../views/${PATH_BASE}/wcs-quotations/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'wcs-quotations-create' },
        props: (route) => ({
            wcs_id: route.query.wcs_id ?? null
        })
    },
    {
        path: `/${BASE}/wcs-quotations/:wcsId/:wcsQuotationId/edit`,
        name: `${BASE}.wcs-quotations.edit`,
        component: () => import(`../views/${PATH_BASE}/wcs-quotations/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'wcs-quotations-edit' },
    },
    {
        path: `/${BASE}/wcs-quotations/:wcsId/:wcsQuotationId/show`,
        name: `${BASE}.wcs-quotations.show`,
        component: () => import(`../views/${PATH_BASE}/wcs-quotations/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'wcs-quotations-show'  },
    },
    {
        path: `/${BASE}/wcs-quotations/:wcsId/index`,
        name: `${BASE}.wcs-quotations.index`,
        component: () => import(`../views/${PATH_BASE}/wcs-quotations/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'wcs-quotations-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/work-cs/:wcsId/supplier-selection`,
        name: `${BASE}.wcs-supplier-selection`,
        component: () => import(`../views/${PATH_BASE}/wcs-supplier-selection/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'wcs-supplier-selection' },
    },
    /* Quotation End */

    /* Work Order start */

    {
        path: `/${BASE}/work-orders`,
        name: `${BASE}.work-orders.index`,
        component: () => import(`../views/${PATH_BASE}/work-orders/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'work-orders-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/work-orders/create`,
        name: `${BASE}.work-orders.create`,
        component: () => import(`../views/${PATH_BASE}/work-orders/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'work-orders-create' },
        props: (route) => ({
            wr_id: route.query.wr_id,
            wcs_id: route.query.wcs_id || null // Set to null if cs_id is not provided
        })
    },
    {
        path: `/${BASE}/work-orders/:workOrderId/edit`,
        name: `${BASE}.work-orders.edit`,
        component: () => import(`../views/${PATH_BASE}/work-orders/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'work-orders-edit' },
    },
    {
        path: `/${BASE}/work-orders/:workOrderId`,
        name: `${BASE}.work-orders.show`,
        component: () => import(`../views/${PATH_BASE}/work-orders/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'work-orders-show'  },
    },

    /* Work Order end */


    
];
