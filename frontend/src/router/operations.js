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
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-port-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/ports/create`,
		name: `${BASE}.configurations.ports.create`,
		component: () => import (`../views/${ViEWBASE}/port/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-port-create' },
	},
	{
		path: `/${BASE}/ports/:portId/edit`,
		name: `${BASE}.configurations.ports.edit`,
		component: () => import (`../views/${ViEWBASE}/port/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-port-edit' },
	},
	{
		path: `/${BASE}/ports/:portId`,
		name: `${BASE}.configurations.ports.show`,
		component: () => import (`../views/${ViEWBASE}/port/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-port-view' },
	},
	/* Cargo Types */
	{
		path: `/${BASE}/cargo-types`,
		name: `${BASE}.configurations.cargo-types.index`,
		component: () => import(`../views/${ViEWBASE}/cargo-types/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-cargo-type-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/cargo-types/create`,
		name: `${BASE}.configurations.cargo-types.create`,
		component: () => import (`../views/${ViEWBASE}/cargo-types/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-cargo-type-create' },
	},
	{
		path: `/${BASE}/cargo-types/:cargoTypeId/edit`,
		name: `${BASE}.configurations.cargo-types.edit`,
		component: () => import (`../views/${ViEWBASE}/cargo-types/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-cargo-type-edit' },
	},
	{
		path: `/${BASE}/cargo-types/:cargoTypeId/show`,
		name: `${BASE}.configurations.cargo-types.show`,
		component: () => import (`../views/${ViEWBASE}/cargo-types/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-cargo-type-view' },
	},
	/* Customer */
	{
		path: `/${BASE}/customers`,
		name: `${BASE}.configurations.customers.index`,
		component: () => import(`../views/${ViEWBASE}/customers/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-customer-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/customers/create`,
		name: `${BASE}.configurations.customers.create`,
		component: () => import (`../views/${ViEWBASE}/customers/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-customer-create' },
	},
	{
		path: `/${BASE}/customers/:customerId/edit`,
		name: `${BASE}.configurations.customers.edit`,
		component: () => import (`../views/${ViEWBASE}/customers/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-customer-edit' },
	},
	{
		path: `/${BASE}/customers/:customerId/show`,
		name: `${BASE}.configurations.customers.show`,
		component: () => import (`../views/${ViEWBASE}/customers/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-customer-view' },
	},
	/* Cargo Tariffs */
	{
		path: `/${BASE}/cargo-tariffs`,
		name: `${BASE}.configurations.cargo-tariffs.index`,
		component: () => import(`../views/${ViEWBASE}/cargo-tariffs/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-cargo-tariff-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/cargo-tariffs/create`,
		name: `${BASE}.configurations.cargo-tariffs.create`,
		component: () => import (`../views/${ViEWBASE}/cargo-tariffs/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-cargo-tariff-create' },
	},
	{
		path: `/${BASE}/cargo-tariffs/:cargoTariffId/edit`,
		name: `${BASE}.configurations.cargo-tariffs.edit`,
		component: () => import (`../views/${ViEWBASE}/cargo-tariffs/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-cargo-tariff-edit' },
	},
	{
		path: `/${BASE}/cargo-tariffs/:cargoTariffId/show`,
		name: `${BASE}.configurations.cargo-tariffs.show`,
		component: () => import (`../views/${ViEWBASE}/cargo-tariffs/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-cargo-tariff-view' },
	},
	/* Maritime Certifications */
	{
		path: `/${BASE}/maritime-certifications`,
		name: `${BASE}.maritime-certifications.index`,
		component: () => import(`../views/${ViEWBASE}/maritime-certifications/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-maritime-certificate-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/maritime-certifications/create`,
		name: `${BASE}.maritime-certifications.create`,
		component: () => import (`../views/${ViEWBASE}/maritime-certifications/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-maritime-certificate-create' },
	},
	{
		path: `/${BASE}/maritime-certifications/:maritimeCertificateId/edit`,
		name: `${BASE}.maritime-certifications.edit`,
		component: () => import (`../views/${ViEWBASE}/maritime-certifications/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-maritime-certificate-edit' },
	},
	/* Vessels */
	{
		path: `/${BASE}/vessels`,
		name: `${BASE}.vessels.index`,
		component: () => import(`../views/${ViEWBASE}/vessels/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-vessel-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/vessels/create`,
		name: `${BASE}.vessels.create`,
		component: () => import (`../views/${ViEWBASE}/vessels/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-create' },
	},
	{
		path: `/${BASE}/vessels/:vesselId/edit`,
		name: `${BASE}.vessels.edit`,
		component: () => import (`../views/${ViEWBASE}/vessels/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-edit' },
	},
	{
		path: `/${BASE}/vessels/:vesselId/show`,
		name: `${BASE}.vessels.show`,
		component: () => import (`../views/${ViEWBASE}/vessels/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-view' },
	},
	/* Vessel Particulars */
	{
		path: `/${BASE}/vessel-particulars`,
		name: `${BASE}.vessel-particulars.index`,
		component: () => import(`../views/${ViEWBASE}/vessel-particulars/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-vessel-particular-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/vessel-particulars/create`,
		name: `${BASE}.vessel-particulars.create`,
		component: () => import (`../views/${ViEWBASE}/vessel-particulars/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-particular-create' },
	},
	{
		path: `/${BASE}/vessel-particulars/:vesselParticularId/edit`,
		name: `${BASE}.vessel-particulars.edit`,
		component: () => import (`../views/${ViEWBASE}/vessel-particulars/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-particular-edit' },
	},
	{
		path: `/${BASE}/vessel-particulars/:vesselParticularId/show`,
		name: `${BASE}.vessel-particulars.show`,
		component: () => import (`../views/${ViEWBASE}/vessel-particulars/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-particular-view' },
	},
	/* Vessel Wise Certificates */
	{
		path: `/${BASE}/vessel-certificates`,
		name: `${BASE}.vessel-certificates.index`,
		component: () => import(`../views/${ViEWBASE}/vessel-certificates/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-vessel-certificate-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/vessel-certificates/create`,
		name: `${BASE}.vessel-certificates.create`,
		component: () => import (`../views/${ViEWBASE}/vessel-certificates/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-certificate-create' },
	},
	{
		path: `/${BASE}/vessel-certificates/:vesselCertificateId/edit`,
		name: `${BASE}.vessel-certificates.edit`,
		component: () => import (`../views/${ViEWBASE}/vessel-certificates/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-certificate-edit' },
	},
	{
		path: `/${BASE}/vessel-certificates/:vesselCertificateId/show`,
		name: `${BASE}.vessel-certificates.show`,
		component: () => import (`../views/${ViEWBASE}/vessel-certificates/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-certificate-view' },
	},
	{
		path: `/${BASE}/vessel-certificates/history/:vesselId/:certificateId`,
		name: `${BASE}.vessel-certificates.history`,
		component: () => import (`../views/${ViEWBASE}/vessel-certificates/history-index.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-certificate-view' },
	},
	{
		path: `/${BASE}/vessel-certificates/renew`,
		name: `${BASE}.vessel-certificates.renew-list`,
		component: () => import(`../views/${ViEWBASE}/vessel-certificates/renew-index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-vessel-certificate-create' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/vessel-certificates/renew/:vesselId/:marineCertificateId`,
		name: `${BASE}.vessel-certificates.renew`,
		component: () => import (`../views/${ViEWBASE}/vessel-certificates/renew.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-certificate-create' },
	},
	/* Voyages */
	{
		path: `/${BASE}/voyages`,
		name: `${BASE}.voyages.index`,
		component: () => import(`../views/${ViEWBASE}/voyages/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-voyage-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/voyages/create`,
		name: `${BASE}.voyages.create`,
		component: () => import (`../views/${ViEWBASE}/voyages/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-create' },
	},
	{
		path: `/${BASE}/voyages/:voyageId/edit`,
		name: `${BASE}.voyages.edit`,
		component: () => import (`../views/${ViEWBASE}/voyages/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-edit' },
	},
	{
		path: `/${BASE}/voyages/:voyageId/show`,
		name: `${BASE}.voyages.show`,
		component: () => import (`../views/${ViEWBASE}/voyages/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-view' },
	},
	/* Charterer Profiles */
	{
		path: `/${BASE}/charterer-profiles`,
		name: `${BASE}.charterer-profiles.index`,
		component: () => import(`../views/${ViEWBASE}/charterer-profiles/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-charterer-profile-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/charterer-profiles/create`,
		name: `${BASE}.charterer-profiles.create`,
		component: () => import (`../views/${ViEWBASE}/charterer-profiles/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-charterer-profile-create' },
	},
	{
		path: `/${BASE}/charterer-profiles/:chartererProfileId/edit`,
		name: `${BASE}.charterer-profiles.edit`,
		component: () => import (`../views/${ViEWBASE}/charterer-profiles/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-charterer-profile-edit' },
	},
	{
		path: `/${BASE}/charterer-profiles/:chartererProfileId/show`,
		name: `${BASE}.charterer-profiles.show`,
		component: () => import (`../views/${ViEWBASE}/charterer-profiles/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-charterer-profile-view' },
	},
	/* Charterer Contracts */
	{
		path: `/${BASE}/charterer-contracts`,
		name: `${BASE}.charterer-contracts.index`,
		component: () => import(`../views/${ViEWBASE}/charterer-contracts/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-charterer-contract-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/charterer-contracts/create`,
		name: `${BASE}.charterer-contracts.create`,
		component: () => import (`../views/${ViEWBASE}/charterer-contracts/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-charterer-contract-create' },
	},
	{
		path: `/${BASE}/charterer-contracts/:chartererContractId/edit`,
		name: `${BASE}.charterer-contracts.edit`,
		component: () => import (`../views/${ViEWBASE}/charterer-contracts/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-charterer-contract-edit' },
	},
	{
		path: `/${BASE}/charterer-contracts/:chartererContractId/show`,
		name: `${BASE}.charterer-contracts.show`,
		component: () => import (`../views/${ViEWBASE}/charterer-contracts/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-charterer-contract-view' },
	},

	/* Charterer Invoice */
	{
		path: `/${BASE}/charterer-invoices`,
		name: `${BASE}.charterer-invoices.index`,
		component: () => import(`../views/${ViEWBASE}/charterer-invoices/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-charterer-invoice-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/charterer-invoices/create`,
		name: `${BASE}.charterer-invoices.create`,
		component: () => import (`../views/${ViEWBASE}/charterer-invoices/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-charterer-invoice-create' },
	},
	{
		path: `/${BASE}/charterer-invoices/:chartererInvoiceId/edit`,
		name: `${BASE}.charterer-invoices.edit`,
		component: () => import (`../views/${ViEWBASE}/charterer-invoices/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-charterer-invoice-edit' },
	},
	{
		path: `/${BASE}/charterer-invoices/:chartererInvoiceId/show`,
		name: `${BASE}.charterer-invoices.show`,
		component: () => import (`../views/${ViEWBASE}/charterer-invoices/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-charterer-invoice-view' },
	},
	/* Delivery and Re-delivery Note */
	{
		path: `/${BASE}/delivery-redelivery`,
		name: `${BASE}.delivery-redelivery.index`,
		component: () => import(`../views/${ViEWBASE}/delivery-redelivery/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-delivery-redelivery-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/delivery-redelivery/create`,
		name: `${BASE}.delivery-redelivery.create`,
		component: () => import (`../views/${ViEWBASE}/delivery-redelivery/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-delivery-redelivery-create' },
	},
	{
		path: `/${BASE}/delivery-redelivery/:deliveryRedeliveryId/edit`,
		name: `${BASE}.delivery-redelivery.edit`,
		component: () => import (`../views/${ViEWBASE}/delivery-redelivery/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-delivery-redelivery-edit' },
	},
	{
		path: `/${BASE}/delivery-redelivery/:deliveryRedeliveryId/show`,
		name: `${BASE}.delivery-redelivery.show`,
		component: () => import (`../views/${ViEWBASE}/delivery-redelivery/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-delivery-redelivery-view' },
	},
	/* Voyage Boat Note */
	{
		path: `/${BASE}/voyage-boat-notes`,
		name: `${BASE}.voyage-boat-notes.index`,
		component: () => import(`../views/${ViEWBASE}/voyage-boat-notes/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-voyage-boat-note-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/voyage-boat-notes/create`,
		name: `${BASE}.voyage-boat-notes.create`,
		component: () => import (`../views/${ViEWBASE}/voyage-boat-notes/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-boat-note-create' },
	},
	{
		path: `/${BASE}/voyage-boat-notes/:voyageBoatNoteId/edit`,
		name: `${BASE}.voyage-boat-notes.edit`,
		component: () => import (`../views/${ViEWBASE}/voyage-boat-notes/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-boat-note-edit' },
	},
	{
		path: `/${BASE}/voyage-boat-notes/:voyageBoatNoteId/show`,
		name: `${BASE}.voyage-boat-notes.show`,
		component: () => import (`../views/${ViEWBASE}/voyage-boat-notes/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-boat-note-view' },
	},
	/* Lighter Noon Report */
	{
		path: `/${BASE}/lighterage-noon-reports`,
		name: `${BASE}.lighter-noon-reports.index`,
		component: () => import(`../views/${ViEWBASE}/lighter-noon-reports/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-lighterage-noon-report-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/lighterage-noon-reports/create`,
		name: `${BASE}.lighter-noon-reports.create`,
		component: () => import (`../views/${ViEWBASE}/lighter-noon-reports/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-lighterage-noon-report-create' },
	},
	{
		path: `/${BASE}/lighterage-noon-reports/:lighterNoonReportId/edit`,
		name: `${BASE}.lighter-noon-reports.edit`,
		component: () => import (`../views/${ViEWBASE}/lighter-noon-reports/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-lighterage-noon-report-edit' },
	},
	{
		path: `/${BASE}/lighterage-noon-reports/:lighterNoonReportId/show`,
		name: `${BASE}.lighter-noon-reports.show`,
		component: () => import (`../views/${ViEWBASE}/lighter-noon-reports/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-lighterage-noon-report-view' },
	},
	{
		path: `/${BASE}/lighterage-noon-reports/:lighterNoonReportId/copy`,
		name: `${BASE}.lighter-noon-reports.copy`,
		component: () => import (`../views/${ViEWBASE}/lighter-noon-reports/copy.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-lighterage-noon-report-create' },
	},
	/* Bulk Noon Report */
	{
		path: `/${BASE}/bulk-noon-reports`,
		name: `${BASE}.bulk-noon-reports.index`,
		component: () => import(`../views/${ViEWBASE}/bulk-noon-reports/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-bulk-noon-report-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/bulk-noon-reports/create`,
		name: `${BASE}.bulk-noon-reports.create`,
		component: () => import (`../views/${ViEWBASE}/bulk-noon-reports/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-bulk-noon-report-create' },
	},
	{
		path: `/${BASE}/bulk-noon-reports/:bulkNoonReportId/edit`,
		name: `${BASE}.bulk-noon-reports.edit`,
		component: () => import (`../views/${ViEWBASE}/bulk-noon-reports/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-bulk-noon-report-edit' },
	},
	{
		path: `/${BASE}/bulk-noon-reports/:bulkNoonReportId/show`,
		name: `${BASE}.bulk-noon-reports.show`,
		component: () => import (`../views/${ViEWBASE}/bulk-noon-reports/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-bulk-noon-report-view' },
	},
	{
		path: `/${BASE}/bulk-noon-reports/:bulkNoonReportId/copy`,
		name: `${BASE}.bulk-noon-reports.copy`,
		component: () => import (`../views/${ViEWBASE}/bulk-noon-reports/copy.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-bulk-noon-report-create' },
	},
		/*Contract Assign */
	{
		path: `/${BASE}/contract-assigns`,
		name: `${BASE}.contract-assigns.index`,
		component: () => import(`../views/${ViEWBASE}/contract-assigns/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-contract-assign-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/contract-assigns/create`,
		name: `${BASE}.contract-assigns.create`,
		component: () => import (`../views/${ViEWBASE}/contract-assigns/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-contract-assign-create' },
	},
	{
		path: `/${BASE}/contract-assigns/:contractAssignId/edit`,
		name: `${BASE}.contract-assigns.edit`,
		component: () => import (`../views/${ViEWBASE}/contract-assigns/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-contract-assign-edit' },
	},
	{
		path: `/${BASE}/contract-assigns/:contractAssignId/show`,
		name: `${BASE}.contract-assigns.show`,
		component: () => import (`../views/${ViEWBASE}/contract-assigns/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-contract-assign-view' },
	},

	/* Expense Heads */
	{
		path: `/${BASE}/expense-heads`,
		name: `${BASE}.expense-heads.index`,
		component: () => import(`../views/${ViEWBASE}/expense-heads/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-expense-head-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/expense-heads/create`,
		name: `${BASE}.expense-heads.create`,
		component: () => import (`../views/${ViEWBASE}/expense-heads/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-expense-head-create' },
	},
	{
		path: `/${BASE}/expense-heads/:expenseHeadId/edit`,
		name: `${BASE}.expense-heads.edit`,
		component: () => import (`../views/${ViEWBASE}/expense-heads/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-expense-head-edit' },
	},
	{
		path: `/${BASE}/expense-heads/:expenseHeadId/show`,
		name: `${BASE}.expense-heads.show`,
		component: () => import (`../views/${ViEWBASE}/expense-heads/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-expense-head-view' },
	},

	/* Vessel Expense Heads */
	{
		path: `/${BASE}/vessel-expense-heads`,
		name: `${BASE}.vessel-expense-heads.index`,
		component: () => import(`../views/${ViEWBASE}/vessel-expense-heads/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-vessel-expense-head-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/vessel-expense-heads/create`,
		name: `${BASE}.vessel-expense-heads.create`,
		component: () => import (`../views/${ViEWBASE}/vessel-expense-heads/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-expense-head-create' },
	},
	{
		path: `/${BASE}/vessel-expense-heads/:vesselExpenseHeadId/edit`,
		name: `${BASE}.vessel-expense-heads.edit`,
		component: () => import (`../views/${ViEWBASE}/vessel-expense-heads/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-expense-head-edit' },
	},
	{
		path: `/${BASE}/vessel-expense-heads/:vesselExpenseHeadId/show`,
		name: `${BASE}.vessel-expense-heads.show`,
		component: () => import (`../views/${ViEWBASE}/vessel-expense-heads/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-expense-head-view' },
	},

	/* Bunker Requisitons */
	{
		path: `/${BASE}/bunker-requisitions`,
		name: `${BASE}.bunker-requisitions.index`,
		component: () => import(`../views/${ViEWBASE}/bunker-requisitions/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-purchase-requisition-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/bunker-requisitions/create`,
		name: `${BASE}.bunker-requisitions.create`,
		component: () => import (`../views/${ViEWBASE}/bunker-requisitions/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-purchase-requisition-create' },
	},
	{
		path: `/${BASE}/bunker-requisitions/:bunkerRequisitionId/edit`,
		name: `${BASE}.bunker-requisitions.edit`,
		component: () => import (`../views/${ViEWBASE}/bunker-requisitions/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-purchase-requisition-edit' },
	},
	{
		path: `/${BASE}/bunker-requisitions/:bunkerRequisitionId/show`,
		name: `${BASE}.bunker-requisitions.show`,
		component: () => import (`../views/${ViEWBASE}/bunker-requisitions/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-purchase-requisition-view' },
	},
	{
		path: `/${BASE}/bunker-requisitions/approved/:bunkerRequisitionId`,
		name: `${BASE}.bunker-requisitions.approved`,
		component: () => import (`../views/${ViEWBASE}/bunker-requisitions/approved.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-purchase-requisition-view' },
	},

	
	/* Bunker Bills */
	{
		path: `/${BASE}/bunker-bills`,
		name: `${BASE}.bunker-bills.index`,
		component: () => import(`../views/${ViEWBASE}/bunker-bills/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-bunker-bill-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/bunker-bills/create`,
		name: `${BASE}.bunker-bills.create`,
		component: () => import (`../views/${ViEWBASE}/bunker-bills/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-bunker-bill-create' },
	},
	{
		path: `/${BASE}/bunker-bills/:bunkerBillId/edit`,
		name: `${BASE}.bunker-bills.edit`,
		component: () => import (`../views/${ViEWBASE}/bunker-bills/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-bunker-bill-edit' },
	},
	{
		path: `/${BASE}/bunker-bills/:bunkerBillId/show`,
		name: `${BASE}.bunker-bills.show`,
		component: () => import (`../views/${ViEWBASE}/bunker-bills/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-bunker-bill-view' },
	},
	/* Voyage Budgets */
	{
		path: `/${BASE}/voyage-budgets`,
		name: `${BASE}.voyage-budgets.index`,
		component: () => import(`../views/${ViEWBASE}/voyage-budgets/index.vue`),
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-budget-view' },
	},
	{
		path: `/${BASE}/voyage-budgets/create`,
		name: `${BASE}.voyage-budgets.create`,
		component: () => import (`../views/${ViEWBASE}/voyage-budgets/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-budget-create' },
	},
	{
		path: `/${BASE}/voyage-budgets/:voyageBudgetId/edit`,
		name: `${BASE}.voyage-budgets.edit`,
		component: () => import (`../views/${ViEWBASE}/voyage-budgets/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-budget-edit' },
	},
	{
		path: `/${BASE}/voyage-budgets/:voyageBudgetId/show`,
		name: `${BASE}.voyage-budgets.show`,
		component: () => import (`../views/${ViEWBASE}/voyage-budgets/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-budget-view' },
	},
	/* Customer Invoice */
	{
		path: `/${BASE}/customer-invoices`,
		name: `${BASE}.customer-invoices.index`,
		component: () => import(`../views/${ViEWBASE}/customer-invoices/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'ops-customer-invoice-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/customer-invoices/create`,
		name: `${BASE}.customer-invoices.create`,
		component: () => import (`../views/${ViEWBASE}/customer-invoices/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-customer-invoice-create' },
	},
	{
		path: `/${BASE}/customer-invoices/:customerInvoiceId/edit`,
		name: `${BASE}.customer-invoices.edit`,
		component: () => import (`../views/${ViEWBASE}/customer-invoices/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-customer-invoice-edit' },
	},
	{
		path: `/${BASE}/customer-invoices/:customerInvoiceId/show`,
		name: `${BASE}.customer-invoices.show`,
		component: () => import (`../views/${ViEWBASE}/customer-invoices/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-customer-invoice-view' },
	},

	/* Voyage Expenditures*/
	{
		path: `/${BASE}/voyage-expenditures`,
		name: `${BASE}.voyage-expenditures.index`,
		component: () => import(`../views/${ViEWBASE}/voyage-expenditures/index.vue`),
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-expense-view' },
	},
	{
		path: `/${BASE}/voyage-expenditures/create`,
		name: `${BASE}.voyage-expenditures.create`,
		component: () => import (`../views/${ViEWBASE}/voyage-expenditures/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-expense-create' },
	},
	{
		path: `/${BASE}/voyage-expenditures/:voyageExpenditureId/edit`,
		name: `${BASE}.voyage-expenditures.edit`,
		component: () => import (`../views/${ViEWBASE}/voyage-expenditures/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-expense-edit' },
	},
	{
		path: `/${BASE}/voyage-expenditures/:voyageExpenditureId/show`,
		name: `${BASE}.voyage-expenditures.show`,
		component: () => import (`../views/${ViEWBASE}/voyage-expenditures/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-expense-view' },
	},

	/* Vessel Bunkers*/
	{
		path: `/${BASE}/vessel-bunkers`,
		name: `${BASE}.vessel-bunkers.index`,
		component: () => import(`../views/${ViEWBASE}/vessel-bunkers/index.vue`),
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-bunker-view' },
	},
	{
		path: `/${BASE}/vessel-bunkers/create`,
		name: `${BASE}.vessel-bunkers.create`,
		component: () => import (`../views/${ViEWBASE}/vessel-bunkers/create.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-bunker-create' },
	},
	{
		path: `/${BASE}/vessel-bunkers/:vesselBunkerId/edit`,
		name: `${BASE}.vessel-bunkers.edit`,
		component: () => import (`../views/${ViEWBASE}/vessel-bunkers/edit.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-bunker-edit' },
	},
	{
		path: `/${BASE}/vessel-bunkers/:vesselBunkerId/show`,
		name: `${BASE}.vessel-bunkers.show`,
		component: () => import (`../views/${ViEWBASE}/vessel-bunkers/show.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-bunker-view' },
	},

	/* Reports */
	{
		path: `/${BASE}/reports/port-wise-expense-report`,
		name: `${BASE}.reports.port-wise-expense-report`,
		component: () => import (`../views/${ViEWBASE}/reports/port-wise-expense-report.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-port-wise-expense-report' },
	},
	{
		path: `/${BASE}/reports/lighterage-voyage-report`,
		name: `${BASE}.reports.lighter-voyage-report`,
		component: () => import (`../views/${ViEWBASE}/reports/lighter-voyage-report.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-lighterage-voyage-report' },
	},
	{
		path: `/${BASE}/reports/bulk-voyage-report`,
		name: `${BASE}.reports.bulk-voyage-report`,
		component: () => import (`../views/${ViEWBASE}/reports/bulk-voyage-report.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-bulk-voyage-report' },
	},
	{
		path: `/${BASE}/reports/voyage-bunker-report`,
		name: `${BASE}.reports.voyage-bunker-report`,
		component: () => import (`../views/${ViEWBASE}/reports/voyage-bunker-report.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-voyage-bunker-report' },
	},
	{
		path: `/${BASE}/reports/vessel-bunker-report`,
		name: `${BASE}.reports.vessel-bunker-report`,
		component: () => import (`../views/${ViEWBASE}/reports/vessel-bunker-report.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-vessel-bunker-report' },
	},
	{
		path: `/${BASE}/reports/budget-vs-expense-report`,
		name: `${BASE}.reports.budget-vs-expense-report`,
		component: () => import (`../views/${ViEWBASE}/reports/budget-vs-expense-report.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-budget-vs-expense-report' },
	},
	{
		path: `/${BASE}/reports/month-wise-expense-report`,
		name: `${BASE}.reports.month-wise-expense-report`,
		component: () => import (`../views/${ViEWBASE}/reports/month-wise-expense-report.vue`),
		meta: { requiresAuth: true, role: "all", permission: 'ops-month-wise-expense-report' },
	},
];