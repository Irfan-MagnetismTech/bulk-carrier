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
		path: `/${BASE}/user/role`,
		name: `${BASE}.user.role.index`,
		component: () => import(`../views/${BASE}/role/roles.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'role-show' },
	},
	{
		path: `/${BASE}/user/role/create`,
		name: `${BASE}.user.role.create`,
		component: () => import(`../views/${BASE}/role/create-role.vue`),
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
		path: `/${BASE}/user/permission`,
		name: `${BASE}.user.permission.index`,
		component: () => import(`../views/${BASE}/permission/permissions.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'permission-show' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/user/permission/create`,
		name: `${BASE}.user.permission.create`,
		component: () => import(`../views/${BASE}/permission/create-permission.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'permission-create' },
	},
	{
		path: `/${BASE}/user/permission/:permissionId/edit`,
		name: `${BASE}.user.permission.edit`,
		component: () => import(`../views/${BASE}/permission/edit-permission.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'permission-edit' },
	},

	// approval management routes
	{
		path: `/${BASE}/approval/management/index`,
		name: `${BASE}.approval.management.index`,
		component: () => import(`../views/${BASE}/approval-management/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'approval-management-show' },
	},
	{
		path: `/${BASE}/approval/management/create`,
		name: `${BASE}.approval.management.create`,
		component: () => import(`../views/${BASE}/approval-management/create-approval.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'approval-management-create' },
	},
	{
		path: `/${BASE}/approval/management/:approvalId/edit`,
		name: `${BASE}.approval.management.edit`,
		component: () => import(`../views/${BASE}/approval-management/edit-approval.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'approval-management-edit' },
	},

	{
		path: `/${BASE}/user/password/update`,
		name: `${BASE}.user.password.update`,
		component: () => import(`../views/${BASE}/user/update-password.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'user-password-update' },
	},

];
