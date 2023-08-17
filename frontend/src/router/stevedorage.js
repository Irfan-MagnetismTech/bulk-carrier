import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "stevedorage";
const ROLE = USER?.role ?? null;

export default [

	/* Tariff route start */

	{
		path: `/${BASE}/tariffs`,
		name: `${BASE}.tariffs.index`,
		component: () => import(`../views/${BASE}/tariffs/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'stevedorage-tariff-show'  },
	},

	{
		path: `/${BASE}/tariffs/create`,
		name: `${BASE}.tariffs.create`,
		component: () => import(`../views/${BASE}/tariffs/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'stevedorage-tariff-create'  },
	},

	{
		path: `/${BASE}/tariffs/:tariffId/edit`,
		name: `${BASE}.tariffs.edit`,
		component: () => import(`../views/${BASE}/tariffs/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'stevedorage-tariff-edit'  },
	},
	{
		path: `/${BASE}/tariffs/:tariffId`,
		name: `${BASE}.tariffs.show`,
		component: () => import(`../views/${BASE}/tariffs/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'stevedorage-tariff-show'  },
	},

	/* Tariff route end */

	{
		path: `/${BASE}/update-load-status`,
		name: `${BASE}.update-load-status.index`,
		component: () => import(`../views/${BASE}/load-assign.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'stevedorage-load-assign' },
	},

	//tariff assign start
	{
		path: `/${BASE}/assign-tariff/voyages`,
		name: `${BASE}.assign-tariff.voyages`,
		component: () =>
			import(`../views/${BASE}/assign-tariff/voyages.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'stevedorage-tariff-assign-voyages'  },
	},
	{
		path: `/${BASE}/assign-tariff/generate-invoices`,
		name: `${BASE}.assign-tariff.generate-invoices`,
		component: () =>
			import(`../views/${BASE}/assign-tariff/generate-invoice.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'stevedorage-tariff-assign-invoices'  },
	},
	//tariff assign end

];
