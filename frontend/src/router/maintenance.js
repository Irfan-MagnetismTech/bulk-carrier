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
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-ship-department-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/ship-departments/create`,
		name: `${BASE}.ship-departments.create`,
		component: () => import(`../views/${VIEWBASE}/ship-department/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-ship-department-create' },
	},
	{
		path: `/${BASE}/ship-departments/:shipDepartmentId/edit`,
		name: `${BASE}.ship-departments.edit`,
		component: () => import(`../views/${VIEWBASE}/ship-department/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-ship-department-edit' },
	},
	{
		path: `/${BASE}/ship-departments/:shipDepartmentId`,
		name: `${BASE}.ship-departments.show`,
		component: () => import(`../views/${VIEWBASE}/ship-department/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-ship-department-view'  },
	},

	/* Ship Department Route end */

	/* Item Group start */
	{
		path: `/${BASE}/item-groups`,
		name: `${BASE}.item-groups.index`,
		component: () => import(`../views/${VIEWBASE}/item-group/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-item-group-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/item-groups/create`,
		name: `${BASE}.item-groups.create`,
		component: () => import(`../views/${VIEWBASE}/item-group/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-item-group-create' },
	},
	{
		path: `/${BASE}/item-groups/:itemGroupId/edit`,
		name: `${BASE}.item-groups.edit`,
		component: () => import(`../views/${VIEWBASE}/item-group/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-item-group-edit' },
	},
	{
		path: `/${BASE}/item-groups/:itemGroupId`,
		name: `${BASE}.item-groups.show`,
		component: () => import(`../views/${VIEWBASE}/item-group/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-item-group-view'  },
	},

	/* Item Group end */

	/* Item start */
	{
		path: `/${BASE}/items`,
		name: `${BASE}.items.index`,
		component: () => import(`../views/${VIEWBASE}/item/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-item-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/items/create`,
		name: `${BASE}.items.create`,
		component: () => import(`../views/${VIEWBASE}/item/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-item-create' },
	},
	{
		path: `/${BASE}/items/:itemId/edit`,
		name: `${BASE}.items.edit`,
		component: () => import(`../views/${VIEWBASE}/item/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-item-edit' },
	},
	{
		path: `/${BASE}/items/:itemId`,
		name: `${BASE}.items.show`,
		component: () => import(`../views/${VIEWBASE}/item/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-item-view'  },
	},

	/* Item end */

	/* Job start */
	{
		path: `/${BASE}/jobs`,
		name: `${BASE}.jobs.index`,
		component: () => import(`../views/${VIEWBASE}/job/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-job-list-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/jobs/create`,
		name: `${BASE}.jobs.create`,
		component: () => import(`../views/${VIEWBASE}/job/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-job-list-create' },
	},
	{
		path: `/${BASE}/jobs/:jobId/edit`,
		name: `${BASE}.jobs.edit`,
		component: () => import(`../views/${VIEWBASE}/job/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-job-list-edit' },
	},
	{
		path: `/${BASE}/jobs/:jobId`,
		name: `${BASE}.jobs.show`,
		component: () => import(`../views/${VIEWBASE}/job/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-job-list-view'  },
	},

	/* Job end */

	/* Run Hour start */
	{
		path: `/${BASE}/run-hours`,
		name: `${BASE}.run-hours.index`,
		component: () => import(`../views/${VIEWBASE}/run-hour/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-running-hour-entry-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/run-hours/create`,
		name: `${BASE}.run-hours.create`,
		component: () => import(`../views/${VIEWBASE}/run-hour/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-running-hour-entry-create' },
	},
	{
		path: `/${BASE}/run-hours/:runHourId/edit`,
		name: `${BASE}.run-hours.edit`,
		component: () => import(`../views/${VIEWBASE}/run-hour/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-running-hour-entry-edit' },
	},
	{
		path: `/${BASE}/run-hours/:runHourId`,
		name: `${BASE}.run-hours.show`,
		component: () => import(`../views/${VIEWBASE}/run-hour/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-running-hour-entry-view'  },
	},

	/* Run Hour end */

	

	/* Work Requisition start */
	{
		path: `/${BASE}/work-requisitions`,
		name: `${BASE}.work-requisitions.index`,
		component: () => import(`../views/${VIEWBASE}/work-requisition/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-work-requisition-pending-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/work-requisitions/create`,
		name: `${BASE}.work-requisitions.create`,
		component: () => import(`../views/${VIEWBASE}/work-requisition/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-work-requisition-pending-create' },
	},
	{
		path: `/${BASE}/work-requisitions/:workRequisitionId/edit`,
		name: `${BASE}.work-requisitions.edit`,
		component: () => import(`../views/${VIEWBASE}/work-requisition/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-work-requisition-pending-edit' },
	},
	{
		path: `/${BASE}/work-requisitions/:workRequisitionId`,
		name: `${BASE}.work-requisitions.show`,
		component: () => import(`../views/${VIEWBASE}/work-requisition/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-work-requisition-pending-view'  },
	},

	/* Work Requisition end */

	
	/* Work Requisition WIP start */
	{
		path: `/${BASE}/wip-work-requisitions`,
		name: `${BASE}.wip-work-requisitions.index`,
		component: () => import(`../views/${VIEWBASE}/wip-work-requisition/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-work-requisition-wip-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	// {
	// 	path: `/${BASE}/wip-work-requisitions/create`,
	// 	name: `${BASE}.wip-work-requisitions.create`,
	// 	component: () => import(`../views/${VIEWBASE}/wip-work-requisition/create.vue`),
	// 	meta: { requiresAuth: true, role: ROLE, permission: 'mnt-work-requisition-wip-create' },
	// },
	{
		path: `/${BASE}/wip-work-requisitions/:wipWorkRequisitionId/edit`,
		name: `${BASE}.wip-work-requisitions.edit`,
		component: () => import(`../views/${VIEWBASE}/wip-work-requisition/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-work-requisition-wip-edit' },
	},
	{
		path: `/${BASE}/wip-work-requisitions/:wipWorkRequisitionId`,
		name: `${BASE}.wip-work-requisitions.show`,
		component: () => import(`../views/${VIEWBASE}/wip-work-requisition/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-work-requisition-wip-view'  },
	},

	/* Work Requisition wip end */

	
	/* Work Requisition Done start */
	{
		path: `/${BASE}/done-work-requisitions`,
		name: `${BASE}.done-work-requisitions.index`,
		component: () => import(`../views/${VIEWBASE}/done-work-requisition/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-work-requisition-done-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	// {
	// 	path: `/${BASE}/done-work-requisitions/create`,
	// 	name: `${BASE}.done-work-requisitions.create`,
	// 	component: () => import(`../views/${VIEWBASE}/done-work-requisition/create.vue`),
	// 	meta: { requiresAuth: true, role: ROLE, permission: 'mnt-work-requisition-done-create' },
	// },
	// {
	// 	path: `/${BASE}/done-work-requisitions/:doneWorkRequisitionId/edit`,
	// 	name: `${BASE}.done-work-requisitions.edit`,
	// 	component: () => import(`../views/${VIEWBASE}/done-work-requisition/edit.vue`),
	// 	meta: { requiresAuth: true, role: ROLE, permission: 'mnt-work-requisition-done-edit' },
	// },
	{
		path: `/${BASE}/done-work-requisitions/:doneWorkRequisitionId`,
		name: `${BASE}.done-work-requisitions.show`,
		component: () => import(`../views/${VIEWBASE}/done-work-requisition/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-work-requisition-done-view'  },
	},

	/* Work Requisition Done end */

	/* Critical Ship Function start */
	{
		path: `/${BASE}/critical-functions`,
		name: `${BASE}.critical-functions.index`,
		component: () => import(`../views/${VIEWBASE}/critical-function/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-function-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/critical-functions/create`,
		name: `${BASE}.critical-functions.create`,
		component: () => import(`../views/${VIEWBASE}/critical-function/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-function-create' },
	},
	{
		path: `/${BASE}/critical-functions/:criticalFunctionId/edit`,
		name: `${BASE}.critical-functions.edit`,
		component: () => import(`../views/${VIEWBASE}/critical-function/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-function-edit' },
	},
	{
		path: `/${BASE}/critical-functions/:criticalFunctionId`,
		name: `${BASE}.critical-functions.show`,
		component: () => import(`../views/${VIEWBASE}/critical-function/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-function-view'  },
	},

	/* Critical Ship Function end */

	
	/* Critical Item Categories Start */
	{
		path: `/${BASE}/critical-item-categories`,
		name: `${BASE}.critical-item-categories.index`,
		component: () => import(`../views/${VIEWBASE}/critical-item-category/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-item-category-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/critical-item-categories/create`,
		name: `${BASE}.critical-item-categories.create`,
		component: () => import(`../views/${VIEWBASE}/critical-item-category/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-item-category-create' },
	},
	{
		path: `/${BASE}/critical-item-categories/:criticalItemCategoryId/edit`,
		name: `${BASE}.critical-item-categories.edit`,
		component: () => import(`../views/${VIEWBASE}/critical-item-category/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-item-category-edit' },
	},
	{
		path: `/${BASE}/critical-item-categories/:criticalItemCategoryId`,
		name: `${BASE}.critical-item-categories.show`,
		component: () => import(`../views/${VIEWBASE}/critical-item-category/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-item-category-view'  },
	},

	/* Critical Item Categories End */

	
	/* Critical Items Start */
	{
		path: `/${BASE}/critical-items`,
		name: `${BASE}.critical-items.index`,
		component: () => import(`../views/${VIEWBASE}/critical-item/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-item-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/critical-items/create`,
		name: `${BASE}.critical-items.create`,
		component: () => import(`../views/${VIEWBASE}/critical-item/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-item-create' },
	},
	{
		path: `/${BASE}/critical-items/:criticalItemId/edit`,
		name: `${BASE}.critical-items.edit`,
		component: () => import(`../views/${VIEWBASE}/critical-item/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-item-edit' },
	},
	{
		path: `/${BASE}/critical-items/:criticalItemId`,
		name: `${BASE}.critical-items.show`,
		component: () => import(`../views/${VIEWBASE}/critical-item/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-item-view'  },
	},

	/* Critical Items End */

	
	/* Critical Vessel Items Start */
	{
		path: `/${BASE}/critical-vessel-items`,
		name: `${BASE}.critical-vessel-items.index`,
		component: () => import(`../views/${VIEWBASE}/critical-vessel-item/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-vessel-item-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/critical-vessel-items/create`,
		name: `${BASE}.critical-vessel-items.create`,
		component: () => import(`../views/${VIEWBASE}/critical-vessel-item/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-vessel-item-create' },
	},
	{
		path: `/${BASE}/critical-vessel-items/:criticalVesselItemId/edit`,
		name: `${BASE}.critical-vessel-items.edit`,
		component: () => import(`../views/${VIEWBASE}/critical-vessel-item/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-vessel-item-edit' },
	},
	{
		path: `/${BASE}/critical-vessel-items/:criticalVesselItemId`,
		name: `${BASE}.critical-vessel-items.show`,
		component: () => import(`../views/${VIEWBASE}/critical-vessel-item/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-vessel-item-view'  },
	},

	/* Critical Vessel Items End */
	
	
	/* Critical Spare List Start */
	{
		path: `/${BASE}/critical-spare-lists`,
		name: `${BASE}.critical-spare-lists.index`,
		component: () => import(`../views/${VIEWBASE}/critical-spare-list/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-spare-list-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/critical-spare-lists/create`,
		name: `${BASE}.critical-spare-lists.create`,
		component: () => import(`../views/${VIEWBASE}/critical-spare-list/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-spare-list-create' },
	},
	{
		path: `/${BASE}/critical-spare-lists/:criticalSpareListId/edit`,
		name: `${BASE}.critical-spare-lists.edit`,
		component: () => import(`../views/${VIEWBASE}/critical-spare-list/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-spare-list-edit' },
	},
	{
		path: `/${BASE}/critical-spare-lists/:criticalSpareListId`,
		name: `${BASE}.critical-spare-lists.show`,
		component: () => import(`../views/${VIEWBASE}/critical-spare-list/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-spare-list-view'  },
	},

	/* Critical Spare List End */

	
	/* Survey Items Start */
	{
		path: `/${BASE}/survey-items`,
		name: `${BASE}.survey-items.index`,
		component: () => import(`../views/${VIEWBASE}/survey-item/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-item-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/survey-items/create`,
		name: `${BASE}.survey-items.create`,
		component: () => import(`../views/${VIEWBASE}/survey-item/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-item-create' },
	},
	{
		path: `/${BASE}/survey-items/:surveyItemId/edit`,
		name: `${BASE}.survey-items.edit`,
		component: () => import(`../views/${VIEWBASE}/survey-item/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-item-edit' },
	},
	{
		path: `/${BASE}/survey-items/:surveyItemId`,
		name: `${BASE}.survey-items.show`,
		component: () => import(`../views/${VIEWBASE}/survey-item/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-item-view'  },
	},

	/* Survey Items End */
	
	
	/* Survey Types Start */
	{
		path: `/${BASE}/survey-types`,
		name: `${BASE}.survey-types.index`,
		component: () => import(`../views/${VIEWBASE}/survey-type/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-type-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/survey-types/create`,
		name: `${BASE}.survey-types.create`,
		component: () => import(`../views/${VIEWBASE}/survey-type/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-type-create' },
	},
	{
		path: `/${BASE}/survey-types/:surveyTypeId/edit`,
		name: `${BASE}.survey-types.edit`,
		component: () => import(`../views/${VIEWBASE}/survey-type/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-type-edit' },
	},
	{
		path: `/${BASE}/survey-types/:surveyTypeId`,
		name: `${BASE}.survey-types.show`,
		component: () => import(`../views/${VIEWBASE}/survey-type/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-type-view'  },
	},

	/* Survey Types End */
	
	/* Survey Start */
	{
		path: `/${BASE}/surveys`,
		name: `${BASE}.surveys.index`,
		component: () => import(`../views/${VIEWBASE}/survey/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/surveys/create`,
		name: `${BASE}.surveys.create`,
		component: () => import(`../views/${VIEWBASE}/survey/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-create' },
	},
	{
		path: `/${BASE}/surveys/:surveyId/edit`,
		name: `${BASE}.surveys.edit`,
		component: () => import(`../views/${VIEWBASE}/survey/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-edit' },
	},
	{
		path: `/${BASE}/surveys/:surveyId`,
		name: `${BASE}.surveys.show`,
		component: () => import(`../views/${VIEWBASE}/survey/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-view'  },
	},

	/* Survey End */

	
	/* Survey Entry Start */
	{
		path: `/${BASE}/survey-entries`,
		name: `${BASE}.survey-entries.index`,
		component: () => import(`../views/${VIEWBASE}/survey-entry/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-entry-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/survey-entries/create`,
		name: `${BASE}.survey-entries.create`,
		component: () => import(`../views/${VIEWBASE}/survey-entry/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-entry-create' },
	},
	{
		path: `/${BASE}/survey-entries/:surveyEntryId/edit`,
		name: `${BASE}.survey-entries.edit`,
		component: () => import(`../views/${VIEWBASE}/survey-entry/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-entry-edit' },
	},
	{
		path: `/${BASE}/survey-entries/:surveyEntryId`,
		name: `${BASE}.survey-entries.show`,
		component: () => import(`../views/${VIEWBASE}/survey-entry/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-entry-view'  },
	},

	/* Survey Entry End */



	/* Report Start */
	/* All Jobs */
	{
		path: `/${BASE}/reports/all-jobs`,
		name: `${BASE}.reports.all-jobs`,
		component: () => import(`../views/${VIEWBASE}/reports/all-jobs-report/report.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-all-jobs-report' },
	},
	/* All Jobs */
	
	/* Upcoming Jobs */
	{
		path: `/${BASE}/reports/upcoming-jobs`,
		name: `${BASE}.reports.upcoming-jobs`,
		component: () => import(`../views/${VIEWBASE}/reports/upcoming-jobs-report/report.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-upcoming-jobs-report' },
	},
	/* Upcoming Jobs */

	
	/* Overdue Jobs */
	{
		path: `/${BASE}/reports/overdue-jobs`,
		name: `${BASE}.reports.overdue-jobs`,
		component: () => import(`../views/${VIEWBASE}/reports/overdue-jobs-report/report.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-overdue-jobs-report' },
	},
	/* Overdue Jobs */


	
	/* Critical Items */
	{
		path: `/${BASE}/reports/critical-vessel-functions`,
		name: `${BASE}.reports.critical-vessel-functions`,
		component: () => import(`../views/${VIEWBASE}/reports/critical-vessel-functions-report/report.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-critical-vessel-functions-report' },
	},
	/* Critical Items */

	
	/* Survey Start */
	{
		path: `/${BASE}/reports/surveys`,
		name: `${BASE}.reports.surveys`,
		component: () => import(`../views/${VIEWBASE}/reports/survey-entries-report/report.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'mnt-survey-entries-report' },
	},
	/* Survey End */



	

	/* Report End */








	
];
