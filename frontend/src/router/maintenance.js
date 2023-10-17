import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "mnt";
const VIEWBASE = "maintenance";
const ROLE = USER?.role ?? null;
export default [
    /* Ship Department Route start */
	{
		path: `/${BASE}/ship-departments`,
		name: `${BASE}.ship-departments.index`,
		component: () => import(`../views/${VIEWBASE}/ship-department/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ship-department-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/ship-departments/create`,
		name: `${BASE}.ship-departments.create`,
		component: () => import(`../views/${VIEWBASE}/ship-department/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ship-department-create' },
	},
	{
		path: `/${BASE}/ship-departments/:shipDepartmentId/edit`,
		name: `${BASE}.ship-departments.edit`,
		component: () => import(`../views/${VIEWBASE}/ship-department/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ship-department-edit' },
	},
	{
		path: `/${BASE}/ship-departments/:shipDepartmentId`,
		name: `${BASE}.ship-departments.show`,
		component: () => import(`../views/${VIEWBASE}/ship-department/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ship-department-show'  },
	},

	/* Ship Department Route end */

	/* Item Group start */
	{
		path: `/${BASE}/item-groups`,
		name: `${BASE}.item-groups.index`,
		component: () => import(`../views/${VIEWBASE}/item-group/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-group-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/item-groups/create`,
		name: `${BASE}.item-groups.create`,
		component: () => import(`../views/${VIEWBASE}/item-group/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-group-create' },
	},
	{
		path: `/${BASE}/item-groups/:itemGroupId/edit`,
		name: `${BASE}.item-groups.edit`,
		component: () => import(`../views/${VIEWBASE}/item-group/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-group-edit' },
	},
	{
		path: `/${BASE}/item-groups/:itemGroupId`,
		name: `${BASE}.item-groups.show`,
		component: () => import(`../views/${VIEWBASE}/item-group/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-group-show'  },
	},

	/* Item Group end */

	/* Item start */
	{
		path: `/${BASE}/items`,
		name: `${BASE}.items.index`,
		component: () => import(`../views/${VIEWBASE}/item/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/items/create`,
		name: `${BASE}.items.create`,
		component: () => import(`../views/${VIEWBASE}/item/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-create' },
	},
	{
		path: `/${BASE}/items/:itemId/edit`,
		name: `${BASE}.items.edit`,
		component: () => import(`../views/${VIEWBASE}/item/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-edit' },
	},
	{
		path: `/${BASE}/items/:itemId`,
		name: `${BASE}.items.show`,
		component: () => import(`../views/${VIEWBASE}/item/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-show'  },
	},

	/* Item end */

	/* Run Hour start */
	{
		path: `/${BASE}/run-hours`,
		name: `${BASE}.run-hours.index`,
		component: () => import(`../views/${BASE}/run-hour/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'run-hour-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/run-hours/create`,
		name: `${BASE}.run-hours.create`,
		component: () => import(`../views/${BASE}/run-hour/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'run-hour-create' },
	},
	{
		path: `/${BASE}/run-hours/:runHourId/edit`,
		name: `${BASE}.run-hours.edit`,
		component: () => import(`../views/${BASE}/run-hour/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'run-hour-edit' },
	},
	{
		path: `/${BASE}/run-hours/:runHourId`,
		name: `${BASE}.run-hours.show`,
		component: () => import(`../views/${BASE}/run-hour/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'run-hour-show'  },
	},

	/* Run Hour end */

	

	/* Job start */
	{
		path: `/${BASE}/job`,
		name: `${BASE}.job.index`,
		component: () => import(`../views/${BASE}/job/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'job-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/job/create`,
		name: `${BASE}.job.create`,
		component: () => import(`../views/${BASE}/job/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'job-create' },
	},
	{
		path: `/${BASE}/job/:jobId/edit`,
		name: `${BASE}.job.edit`,
		component: () => import(`../views/${BASE}/job/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'job-edit' },
	},
	{
		path: `/${BASE}/job/:jobId`,
		name: `${BASE}.job.show`,
		component: () => import(`../views/${BASE}/job/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'job-show'  },
	},

	/* Job end */
	

	
];
