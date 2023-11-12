import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "crw";
const ROLE = USER?.role ?? null;
export default [
    /* Crew Rank Management Routes */
    {
        path: `/${BASE}/ranks`,
        name: `${BASE}.ranks.index`,
        component: () => import(`../views/crew/rank/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/ranks/create`,
        name: `${BASE}.ranks.create`,
        component: () => import(`../views/crew/rank/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/ranks/:rankId/edit`,
        name: `${BASE}.ranks.edit`,
        component: () => import(`../views/crew/rank/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },

    /* Crew Vessel Particulars Routes */
    {
        path: `/${BASE}/vessel-particulars`,
        name: `${BASE}.vesselParticulars.index`,
        component: () => import(`../views/crew/vessel-particulars/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },

    /* Crew Policy Management Routes */
    {
        path: `/${BASE}/policies`,
        name: `${BASE}.policies.index`,
        component: () => import(`../views/crew/policy/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/policies/create`,
        name: `${BASE}.policies.create`,
        component: () => import(`../views/crew/policy/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/policies/:policyId/edit`,
        name: `${BASE}.policies.edit`,
        component: () => import(`../views/crew/policy/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    /* Crew Onboard Checklist Routes */
    {
        path: `/${BASE}/checklists`,
        name: `${BASE}.checklists.index`,
        component: () => import(`../views/crew/checklist/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/checklists/create`,
        name: `${BASE}.checklists.create`,
        component: () => import(`../views/crew/checklist/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/checklists/:checkListId/edit`,
        name: `${BASE}.checklists.edit`,
        component: () => import(`../views/crew/checklist/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    /* Vessel Required Crew Routes */
    {
        path: `/${BASE}/vessel-required-crews`,
        name: `${BASE}.vesselRequiredCrews.index`,
        component: () => import(`../views/crew/vessel-required-crew/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/vessel-required-crews/create`,
        name: `${BASE}.vesselRequiredCrews.create`,
        component: () => import(`../views/crew/vessel-required-crew/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/vessel-required-crews/:vesselRequiredCrewId/edit`,
        name: `${BASE}.vesselRequiredCrews.edit`,
        component: () => import(`../views/crew/vessel-required-crew/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    /* Crew Requisition Routes */
    {
        path: `/${BASE}/crew-requisitions`,
        name: `${BASE}.crewRequisitions.index`,
        component: () => import(`../views/crew/crew-requisition/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/crew-requisitions/create`,
        name: `${BASE}.crewRequisitions.create`,
        component: () => import(`../views/crew/crew-requisition/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/crew-requisitions/:crewRequisitionId/edit`,
        name: `${BASE}.crewRequisitions.edit`,
        component: () => import(`../views/crew/crew-requisition/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    /* Recruitment Approval Routes */
    {
        path: `/${BASE}/recruitment-approvals`,
        name: `${BASE}.recruitmentApprovals.index`,
        component: () => import(`../views/crew/recruitment-approval/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/recruitment-approvals/create`,
        name: `${BASE}.recruitmentApprovals.create`,
        component: () => import(`../views/crew/recruitment-approval/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/recruitment-approvals/:recruitmentApprovalId/edit`,
        name: `${BASE}.recruitmentApprovals.edit`,
        component: () => import(`../views/crew/recruitment-approval/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    /* Crew agencies Routes */
    {
        path: `/${BASE}/agencies`,
        name: `${BASE}.agencies.index`,
        component: () => import(`../views/crew/agency/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/agencies/create`,
        name: `${BASE}.agencies.create`,
        component: () => import(`../views/crew/agency/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/agencies/:agencyId/edit`,
        name: `${BASE}.agencies.edit`,
        component: () => import(`../views/crew/agency/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },

    /* Agency Contract Routes */
    {
        path: `/${BASE}/agency-contracts`,
        name: `${BASE}.agencyContracts.index`,
        component: () => import(`../views/crew/agency-contract/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/agency-contracts/create`,
        name: `${BASE}.agencyContracts.create`,
        component: () => import(`../views/crew/agency-contract/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/agency-contracts/:agencyContractId/edit`,
        name: `${BASE}.agencyContracts.edit`,
        component: () => import(`../views/crew/agency-contract/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },

    /* Agency Bills Routes */
    {
        path: `/${BASE}/agency-bills`,
        name: `${BASE}.agencyBills.index`,
        component: () => import(`../views/crew/agency-bill/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/agency-bills/create`,
        name: `${BASE}.agencyBills.create`,
        component: () => import(`../views/crew/agency-bill/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/agency-bills/:agencyBillId/edit`,
        name: `${BASE}.agencyBills.edit`,
        component: () => import(`../views/crew/agency-bill/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },

    /* Crew Assigns Routes */
    {
        path: `/${BASE}/crew-assigns`,
        name: `${BASE}.crewAssigns.index`,
        component: () => import(`../views/crew/crew-assign/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/crew-assigns/create`,
        name: `${BASE}.crewAssigns.create`,
        component: () => import(`../views/crew/crew-assign/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/crew-assigns/:crewAssignId/edit`,
        name: `${BASE}.crewAssigns.edit`,
        component: () => import(`../views/crew/crew-assign/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },

    /* Crew Incident Records Routes */
    {
        path: `/${BASE}/incident-records`,
        name: `${BASE}.incidentRecords.index`,
        component: () => import(`../views/crew/incident-record/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/incident-records/create`,
        name: `${BASE}.incidentRecords.create`,
        component: () => import(`../views/crew/incident-record/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/incident-records/:incidentRecordId/edit`,
        name: `${BASE}.incidentRecords.edit`,
        component: () => import(`../views/crew/incident-record/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
];
