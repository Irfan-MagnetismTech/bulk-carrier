import Store from '../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "scm";
const ROLE = USER?.role ?? null;
export default [

	/* Material Routes Start */
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
		meta: { requiresAuth: true, role: ROLE, permission: 'material-show' },
	},

	/* Material Routes End */

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

	/* Purchase Order Route start */

	{
		path: `/${BASE}/purchase-orders`,
		name: `${BASE}.purchase-order.index`,
		component: () => import(`../views/${BASE}/purchase-order/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'purchase-order-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/purchase-order/create`,
		name: `${BASE}.purchase-order.create`,
		component: () => import(`../views/${BASE}/purchase-order/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'purchase-order-create' },
	},
	{
		path: `/${BASE}/purchase-order/:purchaseOrderId/edit`,
		name: `${BASE}.purchase-order.edit`,
		component: () => import(`../views/${BASE}/purchase-order/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'purchase-order-edit' },
	},
	{
		path: `/${BASE}/purchase-order/:purchaseOrderId`,
		name: `${BASE}.purchase-order.show`,
		component: () => import(`../views/${BASE}/purchase-order/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'purchase-order-show'  },
	},

	/* Purchase Order Route end */

	/* Comparative statement route start */

	{
		path: `/${BASE}/comparative-statement`,
		name: `${BASE}.comparative-statement.index`,
		component: () => import(`../views/${BASE}/comparative-statement/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'comparative-statement-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/comparative-statement/create`,
		name: `${BASE}.comparative-statement.create`,
		component: () => import(`../views/${BASE}/comparative-statement/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'comparative-statement-create' },
	},
	{
		path: `/${BASE}/comparative-statement/:csId/edit`,
		name: `${BASE}.comparative-statement.edit`,
		component: () => import(`../views/${BASE}/comparative-statement/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'comparative-statement-edit' },
	},
	{
		path: `/${BASE}/comparative-statement/:csId`,
		name: `${BASE}.comparative-statement.show`,
		component: () => import(`../views/${BASE}/comparative-statement/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'comparative-statement-show'  },
	},

	/* Comparative statement route end */

	/* Material requisition route start*/
	{
		path: `/${BASE}/material-requisitions`,
		name: `${BASE}.material-requisitions.index`,
		component: () => import(`../views/${BASE}/material-requisition/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-requisition-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/material-requisitions/create`,
		name: `${BASE}.material-requisitions.create`,
		component: () => import(`../views/${BASE}/material-requisition/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-requisition-create' },
	},
	{
		path: `/${BASE}/material-requisitions/:materialRequisitionId/edit`,
		name: `${BASE}.material-requisitions.edit`,
		component: () => import(`../views/${BASE}/material-requisition/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-requisition-edit' },
	},
	{
		path: `/${BASE}/material-requisitions/:materialRequisitionId`,
		name: `${BASE}.material-requisitions.show`,
		component: () => import(`../views/${BASE}/material-requisition/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-requisition-show'  },
	},
	/* Material requisition route end*/

	/* Material receive route start*/
	{
		path: `/${BASE}/material-receives`,
		name: `${BASE}.material-receives.index`,
		component: () => import(`../views/${BASE}/material-receive/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-receive-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/material-receives/create`,
		name: `${BASE}.material-receives.create`,
		component: () => import(`../views/${BASE}/material-receive/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-receive-create' },
	},
	{
		path: `/${BASE}/material-receives/:materialReceiveId/edit`,
		name: `${BASE}.material-receives.edit`,
		component: () => import(`../views/${BASE}/material-receive/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-receive-edit' },
	},
	{
		path: `/${BASE}/material-receives/:materialReceiveId`,
		name: `${BASE}.material-receives.show`,
		component: () => import(`../views/${BASE}/material-receive/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-receive-show'  },
	},
	/* Material receive route end*/

	/* Material Adjustment route start*/
	{
		path: `/${BASE}/material-adjustments`,
		name: `${BASE}.material-adjustments.index`,
		component: () => import(`../views/${BASE}/material-adjustment/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-adjustment-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/material-adjustments/create`,
		name: `${BASE}.material-adjustments.create`,
		component: () => import(`../views/${BASE}/material-adjustment/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-adjustment-create' },
	},
	{
		path: `/${BASE}/material-adjustments/:materialAdjustmentId/edit`,
		name: `${BASE}.material-adjustments.edit`,
		component: () => import(`../views/${BASE}/material-adjustment/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-adjustment-edit' },
	},
	{
		path: `/${BASE}/material-adjustments/:materialAdjustmentId`,
		name: `${BASE}.material-adjustments.show`,
		component: () => import(`../views/${BASE}/material-adjustment/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-adjustment-show'  },
	},

	/* Material Adjustment route end*/

	/* Supplier Bill route start*/
	{
		path: `/${BASE}/supplier-bills`,
		name: `${BASE}.supplier-bills.index`,
		component: () => import(`../views/${BASE}/supplier-bill/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'supplier-bill-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/supplier-bills/create`,
		name: `${BASE}.supplier-bills.create`,
		component: () => import(`../views/${BASE}/supplier-bill/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'supplier-bill-create' },
	},
	{
		path: `/${BASE}/supplier-bills/:supplierBillId/edit`,
		name: `${BASE}.supplier-bills.edit`,
		component: () => import(`../views/${BASE}/supplier-bill/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'supplier-bill-edit' },
	},
	{
		path: `/${BASE}/supplier-bills/:supplierBillId`,
		name: `${BASE}.supplier-bills.show`,
		component: () => import(`../views/${BASE}/supplier-bill/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'supplier-bill-show'  },
	},
	/* Supplier Bill route end*/

	// Reports
	{
		path: `/${BASE}/reports/material-ledger`,
		name: `${BASE}.reports.material-ledger`,
		component: () => import(`../views/${BASE}/reports/material-ledger.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-ledger-report'  },
	},
	{
		path: `/${BASE}/reports/single-material-ledger`,
		name: `${BASE}.reports.material-ledger.single`,
		component: () => import(`../views/${BASE}/reports/material-ledger-single.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'distinct-material-ledger-report'  },
	},
	{
		path: `/${BASE}/reports/material-summary`,
		name: `${BASE}.reports.material-summary`,
		component: () => import(`../views/${BASE}/reports/material-summary.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-summary-report'  },
	},

	/* Material Movement Requisition route start*/
	{
		path: `/${BASE}/material-movement-requisitions`,
		name: `${BASE}.material-movement-requisitions.index`,
		component: () => import(`../views/${BASE}/material-movement-requisition/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-movement-requisition-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/material-movement-requisitions/create`,
		name: `${BASE}.material-movement-requisitions.create`,
		component: () => import(`../views/${BASE}/material-movement-requisition/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-movement-requisition-create' },
	},
	{
		path: `/${BASE}/material-movement-requisitions/:materialMovementRequisitionId/edit`,
		name: `${BASE}.material-movement-requisitions.edit`,
		component: () => import(`../views/${BASE}/material-movement-requisition/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-movement-requisition-edit' },
	},
	{
		path: `/${BASE}/material-movement-requisitions/:materialMovementRequisitionId`,
		name: `${BASE}.material-movement-requisitions.show`,
		component: () => import(`../views/${BASE}/material-movement-requisition/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-movement-requisition-show'  },
	},
	/* Material Movement Requisition route end*/

	/* Material Movements route start*/
	{
		path: `/${BASE}/material-movements/:movementType`,
		name: `${BASE}.material-movements.index`,
		component: () => import(`../views/${BASE}/material-movement/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-movement-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/material-movements/create/:movementType`,
		name: `${BASE}.material-movements.create`,
		component: () => import(`../views/${BASE}/material-movement/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-movement-create' },
	},
	{
		path: `/${BASE}/material-movements/:materialMovementId/edit/:movementType`,
		name: `${BASE}.material-movements.edit`,
		component: () => import(`../views/${BASE}/material-movement/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-movement-edit' },
	},
	{
		path: `/${BASE}/material-movements/:materialMovementId/:movementType`,
		name: `${BASE}.material-movements.show`,
		component: () => import(`../views/${BASE}/material-movement/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-movement-show'  },
	},
	/* Material Movements route end*/
];
