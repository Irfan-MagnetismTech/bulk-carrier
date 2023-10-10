import Store from '../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "ops";
const ROLE = USER?.role ?? null;
export default [
    /* Ports */
	{
		path: `/${BASE}/ports`,
		name: `${BASE}.configurations.ports.index`,
		component: () => import(`../views/${BASE}/user/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'user-show' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
];
