const BASE = 'finance';
const ROLE = 'admin,super-admin,finance';

export default [
	{
		path: `/${BASE}/voyages`,
		name: `${BASE}.voyages.index`,
		component: () => import(`../views/${BASE}/voyages/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-voyages-show'  },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/voyage-expenditure-heads`,
		name: `${BASE}.voyageExpenditureHead.index`,
		component: () => import(`../views/${BASE}/voyage-expenditure/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-voyage-expenditure-head-show' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/voyage-expenditure-heads/create`,
		name: `${BASE}.voyageExpenditureHead.create`,
		component: () => import(`../views/${BASE}/voyage-expenditure/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-voyage-expenditure-head-create'  },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/voyage-expenditure-heads/:headId/edit`,
		name: `${BASE}.voyage-expenditure-heads.edit`,
		component: () => import(`../views/${BASE}/voyage-expenditure/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-voyage-expenditure-head-edit'  },
	},
	{
		path: `/${BASE}/voyage-expenditure-heads/:headId`,
		name: `${BASE}.voyage-expenditure-heads.show`,
		component: () => import(`../views/${BASE}/voyage-expenditure/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-voyage-expenditure-head-show'  },
	},


	{
		path: `/${BASE}/port-wise-head-generation/index`,
		name: `${BASE}.port-wise-head-generation.index`,
		component: () => import(`../views/${BASE}/port-wise-head-generation/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-port-expenditure-head-show'  },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/port-wise-head-generation/create`,
		name: `${BASE}.port-wise-head-generation.create`,
		component: () => import(`../views/${BASE}/port-wise-head-generation/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-port-expenditure-head-create'  },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/port-wise-head-generation/:headId/edit`,
		name: `${BASE}.port-wise-head-generation.edit`,
		component: () => import(`../views/${BASE}/port-wise-head-generation/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-port-expenditure-head-edit'  },
	},
	{
		path: `/${BASE}/voyage/expense/:pairId/create`,
		name: `${BASE}.voyage-expense.create`,
		component: () => import(`../views/${BASE}/expense-entry/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dashboard'  },
	},
	{
		path: `/${BASE}/voyage/expense/headwise-entry/create`,
		name: `${BASE}.head-wise-voyage-expense.create`,
		component: () => import(`../views/${BASE}/head-wise-expense-entry/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dashboard'  },
	},
	{
		path: `/${BASE}/voyage/expense/:pairId/show`,
		name: `${BASE}.voyage-expense.show`,
		component: () => import(`../views/${BASE}/expense-entry/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dashboard'  },
	},
	{
		path: `/${BASE}/voyage-expenditure-heads/:headId/edit`,
		name: `${BASE}.voyage-expenditure-heads.edit`,
		component: () => import(`../views/${BASE}/voyage-expenditure/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-edit` },
	},
	{
		path: `/${BASE}/voyage-expenditure-heads/:headId`,
		name: `${BASE}.voyage-expenditure-heads.show`,
		component: () => import(`../views/${BASE}/voyage-expenditure/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
	{
		path: `/${BASE}/budget`,
		name: `${BASE}.budget.index`,
		component: () => import(`../views/${BASE}/budget/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
	{
		path: `/${BASE}/expense-invoices`,
		name: `${BASE}.expense-invoices.index`,
		component: () => import(`../views/${BASE}/expense-invoices/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/expense-invoices/edit/:expenseInvoiceId`,
		name: `${BASE}.expense-invoice.edit`,
		component: () => import(`../views/${BASE}/expense-invoices/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
	{
		path: `/${BASE}/expense-invoices/show/:expenseInvoiceId`,
		name: `${BASE}.expense-invoice.show`,
		component: () => import(`../views/${BASE}/expense-invoices/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
	{
		path: `/${BASE}/budget/create`,
		name: `${BASE}.budget.create`,
		component: () => import(`../views/${BASE}/budget/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
	{
		path: `/${BASE}/budget/edit/:budgetId`,
		name: `${BASE}.budget.edit`,
		component: () => import(`../views/${BASE}/budget/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
	{
		path: `/${BASE}/budget/show/:budgetId`,
		name: `${BASE}.budget.show`,
		component: () => import(`../views/${BASE}/budget/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
	{
		path: `/${BASE}/voyage/budget-entry/:budgetId/create`,
		name: `${BASE}.budget-entry.create`,
		component: () => import(`../views/${BASE}/budget-entry/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dashboard'  },
	},
	{
		path: `/${BASE}/Budget-vs-Expense`,
		name: `${BASE}.profitability-report`,
		component: () => import(`../views/${BASE}/budget/profitability-report.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
	{
		path: `/${BASE}/Revenue-and-Expense`,
		name: `${BASE}.revenue-and-expense`,
		component: () => import(`../views/${BASE}/revenue/revenue-expense.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
	{
		path: `/${BASE}/voyage-pairing`,
		name: `${BASE}.voyage-pairing.index`,
		component: () => import(`../views/${BASE}/voyage-pairing/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
	{
		path: `/${BASE}/voyage-pairing/create`,
		name: `${BASE}.voyage-pairing.create`,
		component: () => import(`../views/${BASE}/voyage-pairing/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
	{
		path: `/${BASE}/voyage-pairing/edit/:pairId`,
		name: `${BASE}.voyage-pairing.edit`,
		component: () => import(`../views/${BASE}/voyage-pairing/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
	{
		path: `/${BASE}/budget-assign/:pairId`,
		name: `${BASE}.budget-assign`,
		component: () => import(`../views/${BASE}/budget-assign/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
	{
		path: `/${BASE}/Expenditure-Report`,
		name: `${BASE}.expenditure-report`,
		component: () => import(`../views/${BASE}/voyage-expenditure-report/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
    {
        path: `/${BASE}/voyage-job-status`,
        name: `${BASE}.voyage-job-status`,
        component: () =>
            import(`../views/${BASE}/voyage-job-status.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-voyage-slot-partner-list'},
    },
	{
        path: `/${BASE}/vessel-conditioning-tdr`,
        name: `${BASE}.vessel-conditioning.index`,
        component: () =>
            import(`../views/${BASE}/vessel-conditioning/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'voyage-expenditure-head-show'},
    },
	{
        path: `/${BASE}/vessel-conditioning-tdr/create`,
        name: `${BASE}.vessel-conditioning.create`,
        component: () =>
            import(`../views/${BASE}/vessel-conditioning/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'voyage-expenditure-head-show'},
	},
	{
		path: `/${BASE}/vessel-conditioning-tdr/edit/:agentTdrId`,
		name: `${BASE}.vessel-conditioning.edit`,
		component: () => import(`../views/${BASE}/vessel-conditioning/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: `${BASE}-voyage-expenditure-head-show` },
	},
    {
        path: `/${BASE}/customer-ledger`,
        name: `${BASE}.customer-ledger`,
        component: () =>
            import(`../views/${BASE}/customer-ledger.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-voyage-slot-partner-list'},
    },

	
	
];
