const BASE = "dataencoding";
const ROLE = 'admin,super-admin,dataencoding,operation';

export default [
	
	{
		path: `/${BASE}/currency`,
		name: `${BASE}.currency.index`,
		component: () => import(`../views/${BASE}/currency/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-currency-show'  },
	},
	{
		path: `/${BASE}/currency/create`,
		name: `${BASE}.currency.create`,
		component: () => import(`../views/${BASE}/currency/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-currency-create'  },
	},
	{
		path: `/${BASE}/currency/:currencyId`,
		name: `${BASE}.currency.show`,
		component: () => import(`../views/${BASE}/currency/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-currency-show'  },
	},
	{
		path: `/${BASE}/currency/:currencyId/edit`,
		name: `${BASE}.currency.edit`,
		component: () => import(`../views/${BASE}/currency/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-currency-edit'  },
	},

	// for slot partner
	{
		path: `/${BASE}/slot-partner`,
		name: `${BASE}.slot-partner.index`,
		component: () => import(`../views/${BASE}/slot-partner/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-slot-partner-show'  },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/slot-partner/create`,
		name: `${BASE}.slot-partner.create`,
		component: () => import(`../views/${BASE}/slot-partner/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-slot-partner-create'  },
	},
	{
		path: `/${BASE}/slot-partner/:partnerId`,
		name: `${BASE}.slot-partner.show`,
		component: () => import(`../views/${BASE}/slot-partner/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-slot-partner-show'  },
	},
	{
		path: `/${BASE}/slot-partner/:partnerId/edit`,
		name: `${BASE}.slot-partner.edit`,
		component: () => import(`../views/${BASE}/slot-partner/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-slot-partner-edit'  },
	},
	// for slot partner


	{
		path: `/${BASE}/vessels`,
		name: `${BASE}.vessels.index`,
		component: () => import(`../views/${BASE}/vessels/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-vessel-show'  },
	},
	{
		path: `/${BASE}/vessels/create`,
		name: `${BASE}.vessels.create`,
		component: () => import(`../views/${BASE}/vessels/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-vessel-create'  },
	},
	{
		path: `/${BASE}/vessels/:vesselId`,
		name: `${BASE}.vessels.show`,
		component: () => import(`../views/${BASE}/vessels/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-vessel-show'  },
	},
	{
		path: `/${BASE}/vessels/:vesselId/edit`,
		name: `${BASE}.vessels.edit`,
		component: () => import(`../views/${BASE}/vessels/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-vessel-edit'  },
	},
	{
		path: `/${BASE}/charge-types`,
		name: `${BASE}.chargetypes.index`,
		component: () => import(`../views/${BASE}/chargetypes/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-chargetype-show'  },
	},
	{
		path: `/${BASE}/charge-types/create`,
		name: `${BASE}.chargetypes.create`,
		component: () => import(`../views/${BASE}/chargetypes/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-chargetype-create'  },
	},
	{
		path: `/${BASE}/charge-types/:chargeTypeId`,
		name: `${BASE}.chargetypes.show`,
		component: () => import(`../views/${BASE}/chargetypes/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-chargetype-show'  },
	},
	{
		path: `/${BASE}/charge-types/:chargeTypeId/edit`,
		name: `${BASE}.chargetypes.edit`,
		component: () => import(`../views/${BASE}/chargetypes/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-chargetype-edit'  },
	},
	{
		path: `/mail-templates`,
		name: `${BASE}.mail-templates.index`,
		component: () => import(`../views/${BASE}/mail-templates/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-mail-template-show'  },
	},
	{
		path: `/mail-templates/create`,
		name: `${BASE}.mail-templates.create`,
		component: () => import(`../views/${BASE}/mail-templates/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-mail-template-create'  },
	},
	{
		path: `/${BASE}/mail-templates/:templateId/edit`,
		name: `${BASE}.mail-templates.edit`,
		component: () => import(`../views/${BASE}/mail-templates/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-mail-template-edit'  },
	},
	{
		path: `/${BASE}/mail-templates/:templateId`,
		name: `${BASE}.mail-templates.show`,
		component: () => import(`../views/${BASE}/mail-templates/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-mail-template-show'  },
	},

	/**
	 * Tariff route start
	 * */

	{
		path: `/${BASE}/tariffs`,
		name: `${BASE}.tariffs.index`,
		component: () => import(`../views/${BASE}/tariffs/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-tariff-show'  },
	},

	{
		path: `/${BASE}/tariffs/create`,
		name: `${BASE}.tariffs.create`,
		component: () => import(`../views/${BASE}/tariffs/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-tariff-create'  },
	},

	{
		path: `/${BASE}/tariffs/:tariffId/edit`,
		name: `${BASE}.tariffs.edit`,
		component: () => import(`../views/${BASE}/tariffs/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-tariff-edit'  },
	},
	{
		path: `/${BASE}/tariffs/:tariffId`,
		name: `${BASE}.tariffs.show`,
		component: () => import(`../views/${BASE}/tariffs/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dataencoding-tariff-show'  },
	},

	/**
	 * Tariff route end
	 * */

];
