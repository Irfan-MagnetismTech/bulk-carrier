const BASE = 'documentation';
const ROLE = "admin,super-admin,documentation";

export default [
	
	{
		path: `/${BASE}/particular-customer-report`,
		name: `${BASE}.particular-customer-report`,
		component: () => import(`../views/${BASE}/reports/particular-customer-report.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'documentation-particular-customer-report'  },
	},
	{
		path: `/${BASE}/service-report`,
		name: `${BASE}.service-report`,
		component: () => import(`../views/${BASE}/reports/service-report.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'documentation-service-report'  },
	},
	{
		path: `/${BASE}/monthwise-service-report`,
		name: `${BASE}.monthwise-service-report`,
		component: () => import(`../views/${BASE}/reports/monthwise-service-report.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'documentation-monthwise-service-report'  },
	},
	{
		path: `/${BASE}/customer-loading-performance-report`,
		name: `${BASE}.customer-loading-performance-report`,
		component: () => import(`../views/${BASE}/reports/customer-loading-performance-report.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'documentation-customer-loading-performance-report'  },
	},
	{
		path: `/${BASE}/voyage-schedule-report`,
		name: `${BASE}.voyage-schedule-report`,
		component: () => import(`../views/${BASE}/reports/voyage-schedule-report.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'documentation-schedule-report'  },
	},
	{
		path: `/${BASE}/generate-bl-report`,
		name: `${BASE}.generate-bl.report.index`,
		component: () => import(`../views/${BASE}/reports/generate-bl-report-index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'documentation-bl-generate'  },
	},
	{
		path: `/${BASE}/generate-bl-report-list`,
		name: `${BASE}.generate-bl.report.list`,
		component: () => import(`../views/${BASE}/reports/generate-bl-report-list.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'documentation-bl-generate'  },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
];
