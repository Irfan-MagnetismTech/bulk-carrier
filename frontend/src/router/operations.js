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
		path: `/${BASE}/cargo-tariffs/:cargoTypeId/edit`,
		name: `${BASE}.configurations.cargo-tariffs.edit`,
		component: () => import (`../views/${ViEWBASE}/cargo-tariffs/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: '' },
	},
];
