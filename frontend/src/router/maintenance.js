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
		component: () => import(`../views/${VIEWBASE}/run-hour/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'run-hour-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/run-hours/create`,
		name: `${BASE}.run-hours.create`,
		component: () => import(`../views/${VIEWBASE}/run-hour/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'run-hour-create' },
	},
	{
		path: `/${BASE}/run-hours/:runHourId/edit`,
		name: `${BASE}.run-hours.edit`,
		component: () => import(`../views/${VIEWBASE}/run-hour/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'run-hour-edit' },
	},
	{
		path: `/${BASE}/run-hours/:runHourId`,
		name: `${BASE}.run-hours.show`,
		component: () => import(`../views/${VIEWBASE}/run-hour/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'run-hour-show'  },
	},

	/* Run Hour end */

	

	/* Job start */
	{
		path: `/${BASE}/jobs`,
		name: `${BASE}.jobs.index`,
		component: () => import(`../views/${VIEWBASE}/job/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'job-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/jobs/create`,
		name: `${BASE}.jobs.create`,
		component: () => import(`../views/${VIEWBASE}/job/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'job-create' },
	},
	{
		path: `/${BASE}/jobs/:jobId/edit`,
		name: `${BASE}.jobs.edit`,
		component: () => import(`../views/${VIEWBASE}/job/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'job-edit' },
	},
	{
		path: `/${BASE}/jobs/:jobId`,
		name: `${BASE}.jobs.show`,
		component: () => import(`../views/${VIEWBASE}/job/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'job-show'  },
	},

	/* Job end */

	/* Work Requisition start */
	{
		path: `/${BASE}/work-requisitions`,
		name: `${BASE}.work-requisitions.index`,
		component: () => import(`../views/${VIEWBASE}/work-requisition/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'work-requisition-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/work-requisitions/create`,
		name: `${BASE}.work-requisitions.create`,
		component: () => import(`../views/${VIEWBASE}/work-requisition/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'work-requisition-create' },
	},
	{
		path: `/${BASE}/work-requisitions/:workRequisitionId/edit`,
		name: `${BASE}.work-requisitions.edit`,
		component: () => import(`../views/${VIEWBASE}/work-requisition/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'work-requisition-edit' },
	},
	{
		path: `/${BASE}/work-requisitions/:workRequisitionId`,
		name: `${BASE}.work-requisitions.show`,
		component: () => import(`../views/${VIEWBASE}/work-requisition/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'work-requisition-show'  },
	},

	/* Work Requisition end */

	
	/* Work Requisition WIP start */
	{
		path: `/${BASE}/wip-work-requisitions`,
		name: `${BASE}.wip-work-requisitions.index`,
		component: () => import(`../views/${VIEWBASE}/wip-work-requisition/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'wip-work-requisition-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/wip-work-requisitions/create`,
		name: `${BASE}.wip-work-requisitions.create`,
		component: () => import(`../views/${VIEWBASE}/wip-work-requisition/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'wip-work-requisition-create' },
	},
	{
		path: `/${BASE}/wip-work-requisitions/:wipWorkRequisitionId/edit`,
		name: `${BASE}.wip-work-requisitions.edit`,
		component: () => import(`../views/${VIEWBASE}/wip-work-requisition/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'wip-work-requisition-edit' },
	},
	{
		path: `/${BASE}/wip-work-requisitions/:wipWorkRequisitionId`,
		name: `${BASE}.wip-work-requisitions.show`,
		component: () => import(`../views/${VIEWBASE}/wip-work-requisition/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'wip-work-requisition-show'  },
	},

	/* Work Requisition wip end */

	
	/* Work Requisition Done start */
	{
		path: `/${BASE}/done-work-requisitions`,
		name: `${BASE}.done-work-requisitions.index`,
		component: () => import(`../views/${VIEWBASE}/done-work-requisition/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'done-work-requisition-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/done-work-requisitions/create`,
		name: `${BASE}.done-work-requisitions.create`,
		component: () => import(`../views/${VIEWBASE}/done-work-requisition/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'done-work-requisition-create' },
	},
	{
		path: `/${BASE}/done-work-requisitions/:doneWorkRequisitionId/edit`,
		name: `${BASE}.done-work-requisitions.edit`,
		component: () => import(`../views/${VIEWBASE}/done-work-requisition/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'done-work-requisition-edit' },
	},
	{
		path: `/${BASE}/done-work-requisitions/:doneWorkRequisitionId`,
		name: `${BASE}.done-work-requisitions.show`,
		component: () => import(`../views/${VIEWBASE}/done-work-requisition/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'done-work-requisition-show'  },
	},

	/* Work Requisition Done end */

	/* Critical Ship Function start */
	{
		path: `/${BASE}/critical-functions`,
		name: `${BASE}.critical-functions.index`,
		component: () => import(`../views/${VIEWBASE}/critical-function/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-function-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/critical-functions/create`,
		name: `${BASE}.critical-functions.create`,
		component: () => import(`../views/${VIEWBASE}/critical-function/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-function-create' },
	},
	{
		path: `/${BASE}/critical-functions/:criticalFunctionId/edit`,
		name: `${BASE}.critical-functions.edit`,
		component: () => import(`../views/${VIEWBASE}/critical-function/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-function-edit' },
	},
	{
		path: `/${BASE}/critical-functions/:criticalFunctionId`,
		name: `${BASE}.critical-functions.show`,
		component: () => import(`../views/${VIEWBASE}/critical-function/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-function-show'  },
	},

	/* Critical Ship Function end */




	
];
