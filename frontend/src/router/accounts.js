import Store from '../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "accounts";
const ROLE = USER?.role ?? null;
export default [
	{
		path: `/${BASE}/balance-and-income-lines`,
		name: `${BASE}.balance-and-income-lines.index`,
		component: () => import(`../views/${BASE}/balance-and-income-lines/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/balance-and-income-lines/create`,
		name: `${BASE}.balance-and-income-lines.create`,
		component: () => import(`../views/${BASE}/balance-and-income-lines/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},	
	{
		path: `/${BASE}/balance-and-income-lines/:balanceIncomeLineId/edit`,
		name: `${BASE}.balance-and-income-lines.edit`,
		component: () => import(`../views/${BASE}/balance-and-income-lines/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	{
		path: `/${BASE}/balance-and-income-lines/:balanceIncomeLineId`,
		name: `${BASE}.balance-and-income-lines.show`,
		component: () => import(`../views/${BASE}/balance-and-income-lines/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	

	{
		path: `/${BASE}/accounts`,
		name: `${BASE}.accounts.index`,
		component: () => import(`../views/${BASE}/accounts/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/accounts/create`,
		name: `${BASE}.accounts.create`,
		component: () => import(`../views/${BASE}/accounts/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	{
		path: `/${BASE}/accounts/:accountId/edit`,
		name: `${BASE}.accounts.edit`,
		component: () => import(`../views/${BASE}/accounts/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	{
		path: `/${BASE}/accounts/:accountId`,
		name: `${BASE}.accounts.show`,
		component: () => import(`../views/${BASE}/accounts/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},


	{
		path: `/${BASE}/cash-accounts`,
		name: `${BASE}.cash-accounts.index`,
		component: () => import(`../views/${BASE}/cash-accounts/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/cash-accounts/create`,
		name: `${BASE}.cash-accounts.create`,
		component: () => import(`../views/${BASE}/cash-accounts/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	{
		path: `/${BASE}/cash-accounts/:cashAccountId/edit`,
		name: `${BASE}.cash-accounts.edit`,
		component: () => import(`../views/${BASE}/cash-accounts/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	{
		path: `/${BASE}/cash-accounts/:cashAccountId`,
		name: `${BASE}.cash-accounts.show`,
		component: () => import(`../views/${BASE}/cash-accounts/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},


	{
		path: `/${BASE}/cost-centers`,
		name: `${BASE}.cost-centers.index`,
		component: () => import(`../views/${BASE}/cost-centers/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/cost-centers/create`,
		name: `${BASE}.cost-centers.create`,
		component: () => import(`../views/${BASE}/cost-centers/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	{
		path: `/${BASE}/cost-centers/:costCenterId/edit`,
		name: `${BASE}.cost-centers.edit`,
		component: () => import(`../views/${BASE}/cost-centers/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	{
		path: `/${BASE}/cost-centers/:costCenterId`,
		name: `${BASE}.cost-centers.show`,
		component: () => import(`../views/${BASE}/cost-centers/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},

	{
		path: `/${BASE}/opening-balances`,
		name: `${BASE}.opening-balances.index`,
		component: () => import(`../views/${BASE}/opening-balances/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/opening-balances/create`,
		name: `${BASE}.opening-balances.create`,
		component: () => import(`../views/${BASE}/opening-balances/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	{
		path: `/${BASE}/opening-balances/:openingBalanceId/edit`,
		name: `${BASE}.opening-balances.edit`,
		component: () => import(`../views/${BASE}/opening-balances/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	{
		path: `/${BASE}/opening-balances/:openingBalanceId`,
		name: `${BASE}.opening-balances.show`,
		component: () => import(`../views/${BASE}/opening-balances/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	{
		path: `/${BASE}/transactions`,
		name: `${BASE}.transactions.index`,
		component: () => import(`../views/${BASE}/transactions/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{	
		path: `/${BASE}/transactions/create`,
		name: `${BASE}.transactions.create`,
		component: () => import(`../views/${BASE}/transactions/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	{
		path: `/${BASE}/transactions/:transactionId/edit`,
		name: `${BASE}.transactions.edit`,
		component: () => import(`../views/${BASE}/transactions/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	{
		path: `/${BASE}/transactions/:transactionId`,
		name: `${BASE}.transactions.show`,
		component: () => import(`../views/${BASE}/transactions/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},

	{
		path: `/${BASE}/bank-reconciliations`,
		name: `${BASE}.bank-reconciliations.index`,
		component: () => import(`../views/${BASE}/bank-reconciliations/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

	/* AIS report start */
	{
		path: `/${BASE}/ais-reports/day-book`,
		name: `${BASE}.ais-reports.day-book`,
		component: () => import(`../views/${BASE}/ais-reports/day-book.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/ais-reports/ledgers`,
		name: `${BASE}.ais-reports.ledgers`,
		component: () => import(`../views/${BASE}/ais-reports/ledger.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/ais-reports/trial-balance`,
		name: `${BASE}.ais-reports.trial-balance`,
		component: () => import(`../views/${BASE}/ais-reports/trial-balance.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/ais-reports/income-statement`,
		name: `${BASE}.ais-reports.income-statement`,
		component: () => import(`../views/${BASE}/ais-reports/income-statement.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/ais-reports/balance-sheet`,
		name: `${BASE}.ais-reports.balance-sheet`,
		component: () => import(`../views/${BASE}/ais-reports/balance-sheet.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	/* AIS report end */

];
