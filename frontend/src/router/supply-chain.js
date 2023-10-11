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

    /* Material Category Route start */
    
    {
        path: `/${BASE}/material-categories`,
        name: `${BASE}.material-category.index`,
        component: () => import(`../views/${BASE}/material-category/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-category-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/material-category/create`,
        name: `${BASE}.material-category.create`,
        component: () => import(`../views/${BASE}/material-category/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-category-create' },
    },
    {
        path: `/${BASE}/material-category/:materialCategoryId/edit`,
        name: `${BASE}.material-category.edit`,
        component: () => import(`../views/${BASE}/material-category/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-category-edit' },
    },
    {
        path: `/${BASE}/material-category/:materialCategoryId`,
        name: `${BASE}.material-category.show`,
        component: () => import(`../views/${BASE}/material-category/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-category-show'  },
    },

    /* Material Category Route end */
    /* Warehouse Route start */

	{
		path: `/${BASE}/warehouses`,
		name: `${BASE}.warehouse.index`,
		component: () => import(`../views/${BASE}/warehouse/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'warehouse-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/warehouse/create`,
		name: `${BASE}.warehouse.create`,
		component: () => import(`../views/${BASE}/warehouse/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'warehouse-create' },
	},
	{
		path: `/${BASE}/warehouse/:warehouseId/edit`,
		name: `${BASE}.warehouse.edit`,
		component: () => import(`../views/${BASE}/warehouse/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'warehouse-edit' },
	},
	{
		path: `/${BASE}/warehouse/:warehouseId`,
		name: `${BASE}.warehouse.show`,
		component: () => import(`../views/${BASE}/warehouse/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'warehouse-show'  },
	},
	/* Warehouse Route end */
    /* Material Route start */

    {
        path: `/${BASE}/material`,
        name: `${BASE}.material.index`,
        component: () => import(`../views/${BASE}/material/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/material/create`,
        name: `${BASE}.material.create`,
        component: () => import(`../views/${BASE}/material/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-create' },
    },
    {
        path: `/${BASE}/material/:materialId/edit`,
        name: `${BASE}.material.edit`,
        component: () => import(`../views/${BASE}/material/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-edit' },
    },
    {
        path: `/${BASE}/material/:materialId`,
        name: `${BASE}.material.show`,
        component: () => import(`../views/${BASE}/material/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-show'  },
    },

    /* Material Route end */
];
