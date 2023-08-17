const BASE = 'operation';
const ROLE = 'admin,super-admin,operation';

export default [

	{
		path: `/${BASE}/voyages`,
		name: `${BASE}.voyages.index`,
		component: () => import(`../views/${BASE}/voyages/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-voyages-show' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/edi-converter`,
		name: `${BASE}.edi-converter`,
		component: () => import(`../views/${BASE}/edi-converter.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-edi-converter' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/voyages/create`,
		name: `${BASE}.voyages.create`,
		component: () => import(`../views/${BASE}/voyages/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-voyages-create' },
	},
	{
		path: `/${BASE}/voyages/:voyageId/edit`,
		name: `${BASE}.voyages.edit`,
		component: () => import(`../views/${BASE}/voyages/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-voyages-edit' },
	},
	{
		path: `/${BASE}/containers/:containerId/edit`,
		name: `${BASE}.containers.edit`,
		component: () => import(`../views/${BASE}/containers/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-containers-edit' },
	},
	{
		path: `/${BASE}/voyages/show/:voyageId`,
		name: `${BASE}.voyages.show`,
		component: () => import(`../views/${BASE}/voyages/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-voyages-show'  },
	},
	{
		path: `/${BASE}/voyages/edi-excel/:voyageId`,
		name: `${BASE}.voyages.edi_excel`,
		component: () => import(`../views/${BASE}/voyages/edi.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-edi-upload' },
	},
	{
		path: `/${BASE}/voyages/load-list/:voyageId/:fileType`,
		name: `${BASE}.voyages.load_list`,
		component: () => import(`../views/${BASE}/voyages/loadList.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-tdr-upload' },
	},
	{
		path: `/${BASE}/voyages/copino/:voyageId`,
		name: `${BASE}.voyages.copino`,
		component: () => import(`../views/${BASE}/voyages/copino.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-copino-upload' },
	},
	{
		path: `/${BASE}/voyages/customers/:voyageId`,
		name: `${BASE}.voyages.customers`,
		component: () => import(`../views/${BASE}/voyages/customer.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-voyages-customers' },
	},
	{
		path: `/${BASE}/voyages/statement-of-fact`,
		name: `${BASE}.sof.index`,
		component: () => import(`../views/${BASE}/sof/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dashboard' },
	},
	{
		path: `/${BASE}/voyages/statement-of-fact/create`,
		name: `${BASE}.sof.create`,
		component: () => import(`../views/${BASE}/sof/create-edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dashboard' },
	},
	{
		path: `/${BASE}/voyages/statement-of-fact/edit/:sofId`,
		name: `${BASE}.sof.edit`,
		component: () => import(`../views/${BASE}/sof/create-edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dashboard' },
	},
	{
		path: `/${BASE}/voyages/sof/:voyageId`,
		name: `${BASE}.voyages.sof`,
		component: () => import(`../views/${BASE}/voyages/sof.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dashboard' },
	},
	{
		path: `/${BASE}/mlo/agents`,
		name: `${BASE}.mlo.agents.index`,
		component: () => import(`../views/${BASE}/mlo-agents/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-mlo-agents-show' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/external-email`,
		name: `${BASE}.mlo.agents.external-email`,
		component: () => import(`../views/${BASE}/mlo-agents/hrlines-cc.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-external-email-show' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/mlo/agents/create`,
		name: `${BASE}.mlo.agents.create`,
		component: () => import(`../views/${BASE}/mlo-agents/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-mlo-agents-create' },
	},
	{
		path: `/${BASE}/mlo/agents/:agentId/edit`,
		name: `${BASE}.mlo.agents.edit`,
		component: () => import(`../views/${BASE}/mlo-agents/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-mlo-agents-edit' },
	},
	{
		path: `/${BASE}/mlo/agents/:agentId`,
		name: `${BASE}.mlo.agents.show`,
		component: () => import(`../views/${BASE}/mlo-agents/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-mlo-agents-show' },
	},
	{
		path: `/${BASE}/send-advisory`,
		name: `${BASE}.send-advisory.index`,
		component: () => import(`../views/${BASE}/advisory/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'operation-advisory-agents' },
	},
	{
		path: `/${BASE}/sent-mails`,
		name: `${BASE}.send-advisory.sent-mail`,
		component: () => import(`../views/${BASE}/advisory/sent-mail.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dashboard' },
	},
];
