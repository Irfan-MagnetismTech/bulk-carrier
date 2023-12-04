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

	
	/* Critical Item Categories Start */
	{
		path: `/${BASE}/critical-item-categories`,
		name: `${BASE}.critical-item-categories.index`,
		component: () => import(`../views/${VIEWBASE}/critical-item-category/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-item-category-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/critical-item-categories/create`,
		name: `${BASE}.critical-item-categories.create`,
		component: () => import(`../views/${VIEWBASE}/critical-item-category/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-item-category-create' },
	},
	{
		path: `/${BASE}/critical-item-categories/:criticalItemCategoryId/edit`,
		name: `${BASE}.critical-item-categories.edit`,
		component: () => import(`../views/${VIEWBASE}/critical-item-category/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-item-category-edit' },
	},
	{
		path: `/${BASE}/critical-item-categories/:criticalItemCategoryId`,
		name: `${BASE}.critical-item-categories.show`,
		component: () => import(`../views/${VIEWBASE}/critical-item-category/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-item-category-show'  },
	},

	/* Critical Item Categories End */

	
	/* Critical Items Start */
	{
		path: `/${BASE}/critical-items`,
		name: `${BASE}.critical-items.index`,
		component: () => import(`../views/${VIEWBASE}/critical-item/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-item-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/critical-items/create`,
		name: `${BASE}.critical-items.create`,
		component: () => import(`../views/${VIEWBASE}/critical-item/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-item-create' },
	},
	{
		path: `/${BASE}/critical-items/:criticalItemId/edit`,
		name: `${BASE}.critical-items.edit`,
		component: () => import(`../views/${VIEWBASE}/critical-item/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-item-edit' },
	},
	{
		path: `/${BASE}/critical-items/:criticalItemId`,
		name: `${BASE}.critical-items.show`,
		component: () => import(`../views/${VIEWBASE}/critical-item/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-item-show'  },
	},

	/* Critical Items End */

	
	/* Critical Vessel Items Start */
	{
		path: `/${BASE}/critical-vessel-items`,
		name: `${BASE}.critical-vessel-items.index`,
		component: () => import(`../views/${VIEWBASE}/critical-vessel-item/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-vessel-item-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/critical-vessel-items/create`,
		name: `${BASE}.critical-vessel-items.create`,
		component: () => import(`../views/${VIEWBASE}/critical-vessel-item/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-vessel-item-create' },
	},
	{
		path: `/${BASE}/critical-vessel-items/:criticalVesselItemId/edit`,
		name: `${BASE}.critical-vessel-items.edit`,
		component: () => import(`../views/${VIEWBASE}/critical-vessel-item/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-vessel-item-edit' },
	},
	{
		path: `/${BASE}/critical-vessel-items/:criticalVesselItemId`,
		name: `${BASE}.critical-vessel-items.show`,
		component: () => import(`../views/${VIEWBASE}/critical-vessel-item/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-vessel-item-show'  },
	},

	/* Critical Vessel Items End */
	
	
	/* Critical Spare List Start */
	{
		path: `/${BASE}/critical-spare-lists`,
		name: `${BASE}.critical-spare-lists.index`,
		component: () => import(`../views/${VIEWBASE}/critical-spare-list/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-spare-list-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/critical-spare-lists/create`,
		name: `${BASE}.critical-spare-lists.create`,
		component: () => import(`../views/${VIEWBASE}/critical-spare-list/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-spare-list-create' },
	},
	{
		path: `/${BASE}/critical-spare-lists/:criticalSpareListId/edit`,
		name: `${BASE}.critical-spare-lists.edit`,
		component: () => import(`../views/${VIEWBASE}/critical-spare-list/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-spare-list-edit' },
	},
	{
		path: `/${BASE}/critical-spare-lists/:criticalSpareListId`,
		name: `${BASE}.critical-spare-lists.show`,
		component: () => import(`../views/${VIEWBASE}/critical-spare-list/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'critical-spare-list-show'  },
	},

	/* Critical Spare List End */

	
	/* Survey Items Start */
	{
		path: `/${BASE}/survey-items`,
		name: `${BASE}.survey-items.index`,
		component: () => import(`../views/${VIEWBASE}/survey-item/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'survey-item-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/survey-items/create`,
		name: `${BASE}.survey-items.create`,
		component: () => import(`../views/${VIEWBASE}/survey-item/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'survey-item-create' },
	},
	{
		path: `/${BASE}/survey-items/:surveyItemId/edit`,
		name: `${BASE}.survey-items.edit`,
		component: () => import(`../views/${VIEWBASE}/survey-item/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'survey-item-edit' },
	},
	{
		path: `/${BASE}/survey-items/:surveyItemId`,
		name: `${BASE}.survey-items.show`,
		component: () => import(`../views/${VIEWBASE}/survey-item/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'survey-item-show'  },
	},

	/* Survey Items End */
	
	
	/* Survey Types Start */
	{
		path: `/${BASE}/survey-types`,
		name: `${BASE}.survey-types.index`,
		component: () => import(`../views/${VIEWBASE}/survey-type/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'survey-type-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/survey-types/create`,
		name: `${BASE}.survey-types.create`,
		component: () => import(`../views/${VIEWBASE}/survey-type/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'survey-type-create' },
	},
	{
		path: `/${BASE}/survey-types/:surveyTypeId/edit`,
		name: `${BASE}.survey-types.edit`,
		component: () => import(`../views/${VIEWBASE}/survey-type/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'survey-type-edit' },
	},
	{
		path: `/${BASE}/survey-types/:surveyTypeId`,
		name: `${BASE}.survey-types.show`,
		component: () => import(`../views/${VIEWBASE}/survey-type/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'survey-type-show'  },
	},

	/* Survey Types End */
	
	/* Survey Start */
	{
		path: `/${BASE}/surveys`,
		name: `${BASE}.surveys.index`,
		component: () => import(`../views/${VIEWBASE}/survey/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'survey-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/surveys/create`,
		name: `${BASE}.surveys.create`,
		component: () => import(`../views/${VIEWBASE}/survey/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'survey-create' },
	},
	{
		path: `/${BASE}/surveys/:criticalItemId/edit`,
		name: `${BASE}.surveys.edit`,
		component: () => import(`../views/${VIEWBASE}/survey/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'survey-edit' },
	},
	{
		path: `/${BASE}/surveys/:criticalItemId`,
		name: `${BASE}.surveys.show`,
		component: () => import(`../views/${VIEWBASE}/survey/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'survey-show'  },
	},

	/* Survey End */








	
];
