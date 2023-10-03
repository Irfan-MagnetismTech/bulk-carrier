import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "maintenance";
const ROLE = USER?.role ?? null;
export default [
    /* Ship Department Route start */
	{
		path: `/${BASE}/ship-department`,
		name: `${BASE}.ship-department.index`,
		component: () => import(`../views/${BASE}/ship-department/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ship-department-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/ship-department/create`,
		name: `${BASE}.ship-department.create`,
		component: () => import(`../views/${BASE}/ship-department/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ship-department-create' },
	},
	{
		path: `/${BASE}/ship-department/:shipDepartmentId/edit`,
		name: `${BASE}.ship-department.edit`,
		component: () => import(`../views/${BASE}/ship-department/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ship-department-edit' },
	},
	{
		path: `/${BASE}/ship-department/:shipDepartmentId`,
		name: `${BASE}.ship-department.show`,
		component: () => import(`../views/${BASE}/ship-department/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ship-department-show'  },
	},

	/* Ship Department Route end */

	/* Item Group start */
	{
		path: `/${BASE}/item-group`,
		name: `${BASE}.item-group.index`,
		component: () => import(`../views/${BASE}/item-group/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-group-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/item-group/create`,
		name: `${BASE}.item-group.create`,
		component: () => import(`../views/${BASE}/item-group/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-group-create' },
	},
	{
		path: `/${BASE}/item-group/:itemGroupId/edit`,
		name: `${BASE}.item-group.edit`,
		component: () => import(`../views/${BASE}/item-group/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-group-edit' },
	},
	{
		path: `/${BASE}/item-group/:itemGroupId`,
		name: `${BASE}.item-group.show`,
		component: () => import(`../views/${BASE}/item-group/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-group-show'  },
	},

	/* Ship Department Route end */

	
];
