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
    {
        path: `/${BASE}/checklists/:checkListId/show`,
        name: `${BASE}.checklists.show`,
        component: () => import (`../views/crew/checklist/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
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
    {
        path: `/${BASE}/vessel-required-crews/:vesselRequiredCrewId/show`,
        name: `${BASE}.vesselRequiredCrews.show`,
        component: () => import (`../views/crew/vessel-required-crew/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
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
    {
        path: `/${BASE}/crew-requisitions/:crewRequisitionId/show`,
        name: `${BASE}.crewRequisitions.show`,
        component: () => import (`../views/crew/crew-requisition/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
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
    {
        path: `/${BASE}/recruitment-approvals/:recruitmentApprovalId/show`,
        name: `${BASE}.recruitmentApprovals.show`,
        component: () => import (`../views/crew/recruitment-approval/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
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
    {
        path: `/${BASE}/agencies/:agencyId/show`,
        name: `${BASE}.agencies.show`,
        component: () => import (`../views/crew/agency/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
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
    {
        path: `/${BASE}/agency-contracts/:agencyContractId/show`,
        name: `${BASE}.agencyContracts.show`,
        component: () => import (`../views/crew/agency-contract/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
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
    {
        path: `/${BASE}/agency-bills/:agencyBillId/show`,
        name: `${BASE}.agencyBills.show`,
        component: () => import (`../views/crew/agency-bill/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
    },

    /* Crew Profile Routes */
    {
        path: `/${BASE}/profiles`,
        name: `${BASE}.profiles.index`,
        component: () => import(`../views/crew/profile/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/profiles/create`,
        name: `${BASE}.profiles.create`,
        component: () => import(`../views/crew/profile/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/profiles/:profileId/edit`,
        name: `${BASE}.profiles.edit`,
        component: () => import(`../views/crew/profile/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/profiles/:profileId/show`,
        name: `${BASE}.profiles.show`,
        component: () => import (`../views/crew/profile/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
    },

    /* Crew documents Routes */
    {
        path: `/${BASE}/documents`,
        name: `${BASE}.documents.index`,
        component: () => import(`../views/crew/document/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/documents/create`,
        name: `${BASE}.documents.create`,
        component: () => import(`../views/crew/document/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/documents/:documentId/edit`,
        name: `${BASE}.documents.edit`,
        component: () => import(`../views/crew/document/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/documents/:documentId/show`,
        name: `${BASE}.documents.show`,
        component: () => import (`../views/crew/document/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
    },

    /* Crew documents renew Routes */
    {
        path: `/${BASE}/renew-schedules`,
        name: `${BASE}.renew-schedules.index`,
        component: () => import(`../views/crew/document-renew-schedule/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/renew-schedules/create`,
        name: `${BASE}.renew-schedules.create`,
        component: () => import(`../views/crew/document-renew-schedule/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/renew-schedules/:renewScheduleId/edit`,
        name: `${BASE}.renew-schedules.edit`,
        component: () => import(`../views/crew/document-renew-schedule/edit.vue`),
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
    {
        path: `/${BASE}/crew-assigns/:crewAssignId/show`,
        name: `${BASE}.crewAssigns.show`,
        component: () => import (`../views/crew/crew-assign/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
    },

    /* Crew Attendance Routes */
    {
        path: `/${BASE}/crw-attendances`,
        name: `${BASE}.crwAttendances.index`,
        component: () => import(`../views/crew/crew-attendance/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/crw-attendances/create`,
        name: `${BASE}.crwAttendances.create`,
        component: () => import(`../views/crew/crew-attendance/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/crw-attendances/:crwAttendanceId/edit`,
        name: `${BASE}.crwAttendances.edit`,
        component: () => import(`../views/crew/crew-attendance/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/crw-attendances/:crwAttendanceId/show`,
        name: `${BASE}.crwAttendances.show`,
        component: () => import(`../views/crew/crew-attendance/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
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
    {
        path: `/${BASE}/incident-records/:incidentRecordId/show`,
        name: `${BASE}.incidentRecords.show`,
        component: () => import(`../views/crew/incident-record/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
    },

    /* Crew Salary Structure Routes */
    {
        path: `/${BASE}/crew-salary-structures`,
        name: `${BASE}.crewSalaryStructures.index`,
        component: () => import(`../views/crew/crew-salary-structure/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/crew-salary-structures/create`,
        name: `${BASE}.crewSalaryStructures.create`,
        component: () => import(`../views/crew/crew-salary-structure/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/crew-salary-structures/:crewSalaryStructureId/edit`,
        name: `${BASE}.crewSalaryStructures.edit`,
        component: () => import(`../views/crew/crew-salary-structure/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/crew-salary-structures/:crewSalaryStructureId/show`,
        name: `${BASE}.crewSalaryStructures.show`,
        component: () => import(`../views/crew/crew-salary-structure/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
    },

    /* Crew Bank Accounts Routes */
    {
        path: `/${BASE}/crew-bank-accounts`,
        name: `${BASE}.crewBankAccounts.index`,
        component: () => import(`../views/crew/crew-bank-account/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/crew-bank-accounts/create`,
        name: `${BASE}.crewBankAccounts.create`,
        component: () => import(`../views/crew/crew-bank-account/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/crew-bank-accounts/:crewBankAccountId/edit`,
        name: `${BASE}.crewBankAccounts.edit`,
        component: () => import(`../views/crew/crew-bank-account/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/crew-bank-accounts/:crewBankAccountId/show`,
        name: `${BASE}.crewBankAccounts.show`,
        component: () => import(`../views/crew/crew-bank-account/show.vue`),
        meta: { requiresAuth: true, role: "all", permission: '' },
    },

    /* Crew Payroll Batch Routes */
    {
        path: `/${BASE}/crew-payroll-batches`,
        name: `${BASE}.crewPayrollBatches.index`,
        component: () => import(`../views/crew/crew-payroll-batch/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/crew-payroll-batches/create`,
        name: `${BASE}.crewPayrollBatches.create`,
        component: () => import(`../views/crew/crew-payroll-batch/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/crew-payroll-batches/:crewPayrollBatchId/edit`,
        name: `${BASE}.crewPayrollBatches.edit`,
        component: () => import(`../views/crew/crew-payroll-batch/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: '' },
    },
    {
        path: `/${BASE}/crew-payroll-batches/:crewPayrollBatchId/show`,
        name: `${BASE}.crewPayrollBatches.show`,
        component: () => import(`../views/crew/crew-payroll-batch/show.vue`),
    },

    /* Appraisal Form Start */
    {
		path: `/${BASE}/appraisal-forms`,
		name: `${BASE}.appraisal-forms.index`,
		component: () => import(`../views/crew/appraisal-form/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'appraisal-form-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/appraisal-forms/create`,
		name: `${BASE}.appraisal-forms.create`,
		component: () => import(`../views/crew/appraisal-form/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'appraisal-form-create' },
	},
	{
		path: `/${BASE}/appraisal-forms/:appraisalFormId/edit`,
		name: `${BASE}.appraisal-forms.edit`,
		component: () => import(`../views/crew/appraisal-form/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'appraisal-form-edit' },
	},
	{
		path: `/${BASE}/appraisal-forms/:appraisalFormId`,
		name: `${BASE}.appraisal-forms.show`,
		component: () => import(`../views/crew/appraisal-form/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'appraisal-form-show'  },
	},
    /* Appraisal Form End */

    
    /* Appraisal Record Start */
    {
		path: `/${BASE}/appraisal-records`,
		name: `${BASE}.appraisal-records.index`,
		component: () => import(`../views/crew/appraisal-record/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'appraisal-record-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/appraisal-records/create`,
		name: `${BASE}.appraisal-records.create`,
		component: () => import(`../views/crew/appraisal-record/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'appraisal-record-create' },
	},
	{
		path: `/${BASE}/appraisal-records/:appraisalRecordId/edit`,
		name: `${BASE}.appraisal-records.edit`,
		component: () => import(`../views/crew/appraisal-record/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'appraisal-record-edit' },
	},
	{
		path: `/${BASE}/appraisal-records/:appraisalRecordId`,
		name: `${BASE}.appraisal-records.show`,
		component: () => import(`../views/crew/appraisal-record/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'appraisal-record-show'  },
	},
    /* Appraisal Record End */



    /* Appraisal Record Start */
    {
        path: `/${BASE}/rest-hour-records`,
        name: `${BASE}.rest-hour-records.index`,
        component: () => import(`../views/crew/rest-hour-record/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'rest-hour-record-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/rest-hour-records/create`,
        name: `${BASE}.rest-hour-records.create`,
        component: () => import(`../views/crew/rest-hour-record/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'rest-hour-record-create' },
    },
    /* Appraisal Record End */


];
