import Store from '../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "revenue";
const ROLE = USER?.role ?? null;
export default [

	/* Cash Sales Route start */

	{
		path: `/${BASE}/cash-sales`,
		name: `${BASE}.cash-sale.index`,
		component: () => import(`../views/${BASE}/cash-sale/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'cash-sales' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/cash-sale/create`,
		name: `${BASE}.cash-sale.create`,
		component: () => import(`../views/${BASE}/cash-sale/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'sale-controller-create' },
	},
	{
		path: `/${BASE}/cash-sale/:cashSaleId/edit`,
		name: `${BASE}.cash-sale.edit`,
		component: () => import(`../views/${BASE}/cash-sale/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'sale-controller-edit' },
	},
	{
		path: `/${BASE}/cash-sale/:cashSaleId`,
		name: `${BASE}.cash-sale.show`,
		component: () => import(`../views/${BASE}/cash-sale/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'sale-controller-show'  },
	},

	/* Cash Sales Route end */

	/* Credit Sales Route start */

	{
		path: `/${BASE}/credit-sales`,
		name: `${BASE}.credit-sale.index`,
		component: () => import(`../views/${BASE}/credit-sale/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'credit-sales' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/credit-sale/create`,
		name: `${BASE}.credit-sale.create`,
		component: () => import(`../views/${BASE}/credit-sale/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'sale-controller-create' },
	},
	{
		path: `/${BASE}/credit-sale/:creditSaleId/edit`,
		name: `${BASE}.credit-sale.edit`,
		component: () => import(`../views/${BASE}/credit-sale/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'sale-controller-edit' },
	},
	{
		path: `/${BASE}/credit-sale/:creditSaleId`,
		name: `${BASE}.credit-sale.show`,
		component: () => import(`../views/${BASE}/credit-sale/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'sale-controller-show'  },
	},

	/* Credit Sales Route end */
	/* Purchase Rate Route start */

	{
		path: `/${BASE}/purchase-rates`,
		name: `${BASE}.purchase-rate.index`,
		component: () => import(`../views/${BASE}/purchase-rate/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'purchase-rate-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/purchase-rate/create`,
		name: `${BASE}.purchase-rate.create`,
		component: () => import(`../views/${BASE}/purchase-rate/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'purchase-rate-create' },
	},
	{
		path: `/${BASE}/purchase-rate/:purchaseRateId/edit`,
		name: `${BASE}.purchase-rate.edit`,
		component: () => import(`../views/${BASE}/purchase-rate/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'purchase-rate-edit' },
	},
	{
		path: `/${BASE}/purchase-rate/history/:vendorId/:materialId`,
		name: `${BASE}.purchase-rate.history`,
		component: () => import(`../views/${BASE}/purchase-rate/history.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'purchase-rate-history' },
	},

	/* Purchase Rate Route end */
	/* Sale Rate Route start */

	{
		path: `/${BASE}/sale-rates`,
		name: `${BASE}.sale-rate.index`,
		component: () => import(`../views/${BASE}/sale-rate/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'sale-rate-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/sale-rate/create`,
		name: `${BASE}.sale-rate.create`,
		component: () => import(`../views/${BASE}/sale-rate/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'sale-rate-create' },
	},
	{
		path: `/${BASE}/sale-rate/:saleRateId/edit`,
		name: `${BASE}.sale-rate.edit`,
		component: () => import(`../views/${BASE}/sale-rate/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'sale-rate-edit' },
	},

	/* Sale Rate Route end */

	/* Service Rate Route start */

	{
		path: `/${BASE}/service-rates`,
		name: `${BASE}.service-rate.index`,
		component: () => import(`../views/${BASE}/service-rate/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'service-rate-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/service-rate/create`,
		name: `${BASE}.service-rate.create`,
		component: () => import(`../views/${BASE}/service-rate/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'service-rate-create' },
	},
	{
		path: `/${BASE}/service-rate/:serviceRateId/edit`,
		name: `${BASE}.service-rate.edit`,
		component: () => import(`../views/${BASE}/service-rate/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'service-rate-edit' },
	},

	/* Service Rate Route end */

	/* Service Routes Start */

	{
		path: `/${BASE}/services`,
		name: `${BASE}.service.index`,
		component: () => import(`../views/${BASE}/service/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'service-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/service/create`,
		name: `${BASE}.service.create`,
		component: () => import(`../views/${BASE}/service/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'service-create' },
	},
	{
		path: `/${BASE}/service/:serviceId/edit`,
		name: `${BASE}.service.edit`,
		component: () => import(`../views/${BASE}/service/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'service-edit' },
	},
	{
		path: `/${BASE}/service/:serviceId`,
		name: `${BASE}.service.show`,
		component: () => import(`../views/${BASE}/service/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'service-show' },
	},

	/* Service Routes End */

	/* Billing Route start */
	{
		path: `/${BASE}/billing/create`,
		name: `${BASE}.billing.create`,
		component: () => import(`../views/${BASE}/billing/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'customer-bill-create' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

	{
		path: `/${BASE}/billing`,
		name: `${BASE}.billing.index`,
		component: () => import(`../views/${BASE}/billing/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'customer-bill-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/billing/:billId/edit`,
		name: `${BASE}.billing.edit`,
		component: () => import(`../views/${BASE}/billing/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'customer-bill-edit' },
	},
	{
		path: `/${BASE}/billing/:billId`,
		name: `${BASE}.billing.show`,
		component: () => import(`../views/${BASE}/billing/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'customer-bill-show' },
	},

	/* Billing Routes End */

	/* Money Receipt Route start */

	{
		path: `/${BASE}/money-receipt/create`,
		name: `${BASE}.money-receipt.create`,
		component: () => import(`../views/${BASE}/money-receipt/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'money-receipt-create' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

	{
		path: `/${BASE}/money-receipt`,
		name: `${BASE}.money-receipt.index`,
		component: () => import(`../views/${BASE}/money-receipt/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'money-receipt-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/money-receipt/:moneyReceiptId/edit`,
		name: `${BASE}.money-receipt.edit`,
		component: () => import(`../views/${BASE}/money-receipt/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'money-receipt-edit' },
	},
	{
		path: `/${BASE}/money-receipt/:moneyReceiptId`,
		name: `${BASE}.money-receipt.show`,
		component: () => import(`../views/${BASE}/money-receipt/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'money-receipt-show' },
	},

	/* Money Receipt Routes End */

	/* Shift Switching */

	{
		path: `/${BASE}/shift-performances/create`,
		name: `${BASE}.shift-performance.create`,
		component: () => import(`../views/${BASE}/shift-performance/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'shift-performance-create' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

	{
		path: `/${BASE}/shift-performances`,
		name: `${BASE}.shift-performance.index`,
		component: () => import(`../views/${BASE}/shift-performance/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'shift-performance-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/shift-performances/:shiftPerformanceId/edit`,
		name: `${BASE}.shift-performance.edit`,
		component: () => import(`../views/${BASE}/shift-performance/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'shift-performance-edit' },
	},
	{
		path: `/${BASE}/shift-performances/:shiftPerformanceId`,
		name: `${BASE}.shift-performance.show`,
		component: () => import(`../views/${BASE}/shift-performance/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'shift-performance-show' },
	},

	/* Shift Performance Route End */

	{
		path: `/${BASE}/reports/sales-reports`,
		name: `${BASE}.reports.sales-reports.index`,
		component: () => import(`../views/${BASE}/reports/sales-reports/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'sales-report' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

	{
		path: `/${BASE}/reports/shift-summary`,
		name: `${BASE}.reports.full-shifts-summary.index`,
		component: () => import(`../views/${BASE}/reports/sales-reports/shift-summary.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'daily-shift-cash-sale-report' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

	{
		path: `/${BASE}/reports/customer-reports`,
		name: `${BASE}.reports.customer-reports.index`,
		component: () => import(`../views/${BASE}/reports/customer-reports/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'customer-reports' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

	{
		path: `/${BASE}/reports/aging-schedule`,
		name: `${BASE}.reports.aging-schedule.index`,
		component: () => import(`../views/${BASE}/reports/aging-schedule/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'aging-schedule' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
];
