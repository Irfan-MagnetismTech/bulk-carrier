import Store from '../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "ops";
const ViEWBASE = "operation";
const ROLE = USER?.role ?? null;
export default [
    /* Ports */
	{
		path: `/${BASE}/ports`,
		name: `${BASE}.configurations.ports.index`,
		component: () => import(`../views/${ViEWBASE}/port/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/ports/create`,
		name: `${BASE}.configurations.ports.create`,
		component: () => import (`../views/${ViEWBASE}/port/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	{
		path: `/${BASE}/ports/:portId/edit`,
		name: `${BASE}.configurations.ports.edit`,
		component: () => import (`../views/${ViEWBASE}/port/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	/* Cargo Types */
	{
		path: `/${BASE}/cargo-types`,
		name: `${BASE}.configurations.cargo-types.index`,
		component: () => import(`../views/${ViEWBASE}/cargo-types/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/cargo-types/create`,
		name: `${BASE}.configurations.cargo-types.create`,
		component: () => import (`../views/${ViEWBASE}/cargo-types/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	{
		path: `/${BASE}/cargo-types/:cargoTypeId/edit`,
		name: `${BASE}.configurations.cargo-types.edit`,
		component: () => import (`../views/${ViEWBASE}/cargo-types/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	/* Customer */
	{
		path: `/${BASE}/customers`,
		name: `${BASE}.configurations.customers.index`,
		component: () => import(`../views/${ViEWBASE}/customers/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/customers/create`,
		name: `${BASE}.configurations.customers.create`,
		component: () => import (`../views/${ViEWBASE}/customers/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	{
		path: `/${BASE}/customers/:customerId/edit`,
		name: `${BASE}.configurations.customers.edit`,
		component: () => import (`../views/${ViEWBASE}/customers/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	/* Cargo Tariffs */
	{
		path: `/${BASE}/cargo-tariffs`,
		name: `${BASE}.configurations.cargo-tariffs.index`,
		component: () => import(`../views/${ViEWBASE}/cargo-tariffs/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/cargo-tariffs/create`,
		name: `${BASE}.configurations.cargo-tariffs.create`,
		component: () => import (`../views/${ViEWBASE}/cargo-tariffs/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	{
		path: `/${BASE}/cargo-tariffs/:cargoTariffId/edit`,
		name: `${BASE}.configurations.cargo-tariffs.edit`,
		component: () => import (`../views/${ViEWBASE}/cargo-tariffs/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	{
		path: `/${BASE}/cargo-tariffs/:cargoTariffId`,
		name: `${BASE}.configurations.cargo-tariffs.show`,
		component: () => import (`../views/${ViEWBASE}/cargo-tariffs/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	/* Maritime Certifications */
	{
		path: `/${BASE}/maritime-certifications`,
		name: `${BASE}.maritime-certifications.index`,
		component: () => import(`../views/${ViEWBASE}/maritime-certifications/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/maritime-certifications/create`,
		name: `${BASE}.maritime-certifications.create`,
		component: () => import (`../views/${ViEWBASE}/maritime-certifications/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	{
		path: `/${BASE}/maritime-certifications/:maritimeCertificateId/edit`,
		name: `${BASE}.maritime-certifications.edit`,
		component: () => import (`../views/${ViEWBASE}/maritime-certifications/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	/* Vessels */
	{
		path: `/${BASE}/vessels`,
		name: `${BASE}.vessels.index`,
		component: () => import(`../views/${ViEWBASE}/vessels/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/vessels/create`,
		name: `${BASE}.vessels.create`,
		component: () => import (`../views/${ViEWBASE}/vessels/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	{
		path: `/${BASE}/vessels/:vesselId/edit`,
		name: `${BASE}.vessels.edit`,
		component: () => import (`../views/${ViEWBASE}/vessels/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	{
		path: `/${BASE}/vessels/:vesselId/show`,
		name: `${BASE}.vessels.show`,
		component: () => import (`../views/${ViEWBASE}/vessels/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	/* Vessel Particulars */
	{
		path: `/${BASE}/vessel-particulars`,
		name: `${BASE}.vessel-particulars.index`,
		component: () => import(`../views/${ViEWBASE}/vessel-particulars/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/vessel-particulars/create`,
		name: `${BASE}.vessel-particulars.create`,
		component: () => import (`../views/${ViEWBASE}/vessel-particulars/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	{
		path: `/${BASE}/vessel-particulars/:vesselParticularId/edit`,
		name: `${BASE}.vessel-particulars.edit`,
		component: () => import (`../views/${ViEWBASE}/vessel-particulars/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
	{
		path: `/${BASE}/vessel-particulars/:vesselParticularId/show`,
		name: `${BASE}.vessel-particulars.show`,
		component: () => import (`../views/${ViEWBASE}/vessel-particulars/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
];