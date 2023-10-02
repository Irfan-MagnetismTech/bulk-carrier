import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "administration";
const ROLE = USER?.role ?? null;
export default [

	/* User Management Routes */
	{
		path: `/${BASE}/users`,
		name: `${BASE}.users.index`,
		component: () => import(`../views/${BASE}/user/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'user-show' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/users/create`,
		name: `${BASE}.users.create`,
		component: () => import(`../views/${BASE}/user/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'user-create' },
	},
	{
		path: `/${BASE}/users/:userId/edit`,
		name: `${BASE}.users.edit`,
		component: () => import(`../views/${BASE}/user/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'user-edit' },
	},

	/* User Role Management Routes */
	{
		path: `/${BASE}/user/roles`,
		name: `${BASE}.user.roles.index`,
		component: () => import(`../views/${BASE}/role/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'role-show' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/user/roles/create`,
		name: `${BASE}.user.roles.create`,
		component: () => import(`../views/${BASE}/role/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'role-create' },
	},
	{
		path: `/${BASE}/user/role/:roleId/edit`,
		name: `${BASE}.user.role.edit`,
		component: () => import(`../views/${BASE}/role/edit-role.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'role-edit' },
	},

	/* User Permission Management Routes */
	{
		path: `/${BASE}/user/permissions`,
		name: `${BASE}.user.permissions.index`,
		component: () => import(`../views/${BASE}/permission/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'permission-show' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

	{
		path: `/${BASE}/user/password/update`,
		name: `${BASE}.user.password.update`,
		component: () => import(`../views/${BASE}/user/update-password.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'user-password-update' },
	},
];
