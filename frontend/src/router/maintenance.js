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

	/* Item Group end */

	/* Item start */
	{
		path: `/${BASE}/item`,
		name: `${BASE}.item.index`,
		component: () => import(`../views/${BASE}/item/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/item/create`,
		name: `${BASE}.item.create`,
		component: () => import(`../views/${BASE}/item/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-create' },
	},
	{
		path: `/${BASE}/item/:itemId/edit`,
		name: `${BASE}.item.edit`,
		component: () => import(`../views/${BASE}/item/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'item-edit' },
	},
	{
		path: `/${BASE}/item/:itemId`,
		name: `${BASE}.item.show`,
		component: () => import(`../views/${BASE}/item/show.vue`),
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
