const BASE = "commercial";
const ROLE = "admin,super-admin,commercial";

export default [
    {
        path: `/${BASE}/bookings`,
        name: `${BASE}.bookings.index`,
        component: () => import(`../views/${BASE}/bookings/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-booking-show'  },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/bookings/create`,
        name: `${BASE}.bookings.create`,
        component: () => import(`../views/${BASE}/bookings/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-booking-create'  },
    },
    {
        path: `/${BASE}/bookings/:bookingId/edit`,
        name: `${BASE}.bookings.edit`,
        component: () => import(`../views/${BASE}/bookings/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-booking-edit'  },
    },
    {
        path: `/${BASE}/bookings/:bookingId`,
        name: `${BASE}.bookings.show`,
        component: () => import(`../views/${BASE}/bookings/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-booking-show'  },
    },

    //Service Routes
    {
        path: `/${BASE}/services`,
        name: `${BASE}.services.index`,
        component: () => import(`../views/${BASE}/services/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-service-show'  },
    },
    {
        path: `/${BASE}/services/create`,
        name: `${BASE}.services.create`,
        component: () => import(`../views/${BASE}/services/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-service-create'  },
    },
    {
        path: `/${BASE}/services/:serviceId/edit`,
        name: `${BASE}.services.edit`,
        component: () => import(`../views/${BASE}/services/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-service-edit'  },
    },
    {
        path: `/${BASE}/services/:serviceId`,
        name: `${BASE}.services.show`,
        component: () => import(`../views/${BASE}/services/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-service-show'  },
    },

    //Reports Routes
    {
        path: `/${BASE}/reports/freight-cargo-manifest/index`,
        name: `${BASE}.freight-cargo-manifest.index`,
        component: () =>
            import(`../views/${BASE}/reports/freight-cargo-manifest.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-freight-cargo-manifest-report'  },
    },
    {
        path: `/${BASE}/reports/freight-manifest/index`,
        name: `${BASE}.freight-manifest.index`,
        component: () =>
            import(`../views/${BASE}/reports/freight-manifest.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-freight-manifest-report'  },
    },
    {
        path: `/${BASE}/reports/freight-cargo-manifest/:voyageNo?`,
        name: `${BASE}.freight-cargo-manifest`,
        component: () =>
            import(`../views/${BASE}/reports/freight-cargo-manifest.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-freight-manifest-report'  },
    },
    // {
    //     path: `/${BASE}/reports/customer-load-calculation/index`,
    //     name: `${BASE}.customer-load-calculation.index`,
    //     component: () =>
    //         import(`../views/${BASE}/reports/customer-load-calculation.vue`),
    //     meta: { requiresAuth: true, role: ROLE, permission: 'show'  },
    // },

    // {
    //     path: `/${BASE}/reports/slot-uses-statement`,
    //     name: `${BASE}.slot-uses-statement`,
    //     component: () =>
    //         import(`../views/${BASE}/reports/slot-uses-statement.vue`),
    //     meta: { requiresAuth: true, role: ROLE, permission: 'show'  },
    // },

    {
        path: `/${BASE}/reports/voyage-slot-uses-statement/:voyageId?/:customerId?/:sector?`,
        name: `${BASE}.voyage-slot-uses-statement`,
        component: () =>
            import(`../views/${BASE}/reports/voyage-open-fixed-slot-uses-statement-form.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-voyage-slot-uses-statement'  },
    },

    {
        path: `/${BASE}/reports/aging-schedule`,
        name: `${BASE}.aging-schedule`,
        component: () =>
            import(`../views/${BASE}/reports/aging-schedule.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'finance-aging-schedule'  },
    },

    {
        path: `/${BASE}/reports/manual-debit-note`,
        name: `${BASE}.manual-debit-note`,
        component: () =>
            import(`../views/${BASE}/reports/manual-debit-note.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'finance-manual-debit-generate'  },
    },

    // {
    //     path: `/${BASE}/reports/customer-load-calculation/:voyageId?/:customerCode?`,
    //     name: `${BASE}.customer-load-calculation`,
    //     component: () =>
    //         import(`../views/${BASE}/reports/customer-load-calculation.vue`),
    //     meta: { requiresAuth: true, role: ROLE, permission: 'show'  },
    // },
    // {
    //     path: `/${BASE}/reports/receivable-schedule`,
    //     name: `${BASE}.receivable-schedule`,
    //     component: () =>
    //         import(`../views/${BASE}/reports/receivable-schedule.vue`),
    //     meta: { requiresAuth: true, role: ROLE, permission: 'show'  },
    // },
    {
        path: `/${BASE}/reports/generate-bl`,
        name: `${BASE}.generate-bl`,
        component: () => import(`../views/${BASE}/reports/generate-bl.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'documentation-bl-generate'  },
    },

    //Contract routes
    {
        path: `/${BASE}/contracts`,
        name: `${BASE}.contracts.index`,
        component: () => import(`../views/${BASE}/contracts/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-contract-show'  },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/contracts/create`,
        name: `${BASE}.contracts.create`,
        component: () => import(`../views/${BASE}/contracts/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-contract-create'  },
    },
    {
        path: `/${BASE}/contracts/:contractId/edit`,
        name: `${BASE}.contracts.edit`,
        component: () => import(`../views/${BASE}/contracts/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-contract-edit'  },
    },
    {
        path: `/${BASE}/slot-charter-contracts/:contractId`,
        name: `${BASE}.slot-charter-contracts.show`,
        component: () => import(`../views/${BASE}/contracts/slot-charter-contract.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-contract-show'  },
    },
    {
        path: `/${BASE}/contracts/:contractId/copy`,
        name: `${BASE}.contracts.copy`,
        component: () => import(`../views/${BASE}/contracts/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-contract-copy'  },
    },
    {
        path: `/${BASE}/contracts/:contractId`,
        name: `${BASE}.contracts.show`,
        component: () => import(`../views/${BASE}/contracts/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-contract-show'  },
    },


    //open slot contract routes
    {
        path: `/${BASE}/open-slot-contract/create`,
        name: `${BASE}.open-slot-contract.create`,
        component: () => import(`../views/${BASE}/open-slot-contract/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-open-slot-contract-show'  },
    },
    {
        path: `/${BASE}/open-slot-contract/:contractId/edit`,
        name: `${BASE}.open-slot-contract.edit`,
        component: () => import(`../views/${BASE}/open-slot-contract/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-open-slot-contract-edit'  },
    },
    {
        path: `/${BASE}/open-slot-contract/:contractId`,
        name: `${BASE}.open-slot-contract.show`,
        component: () => import(`../views/${BASE}/open-slot-contract/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-open-slot-contract-show'  },
    },
    {
        path: `/${BASE}/slot-charter-open-contract/:contractId`,
        name: `${BASE}.slot-charter-open-contract.show`,
        component: () => import(`../views/${BASE}/open-slot-contract/slot-charter-contract.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-open-slot-contract-show'  },
    },
    {
        path: `/${BASE}/open-slot-contract/:contractId/edit`,
        name: `${BASE}.open-slot-contract.edit`,
        component: () => import(`../views/${BASE}/open-slot-contract/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-open-slot-contract-edit'  },
    },


    //force load contract routes
    {
        path: `/${BASE}/force-load-contract/create`,
        name: `${BASE}.force-load-contract.create`,
        component: () => import(`../views/${BASE}/force-load-contract/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-force-load-contract-show'  },
    },
    {
        path: `/${BASE}/force-load-contract/:contractId/edit`,
        name: `${BASE}.force-load-contract.edit`,
        component: () => import(`../views/${BASE}/force-load-contract/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-force-load-contract-edit'  },
    },
    {
        path: `/${BASE}/force-load-contract/:contractId`,
        name: `${BASE}.force-load-contract.show`,
        component: () => import(`../views/${BASE}/force-load-contract/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-force-load-contract-show'  },
    },
    {
        path: `/${BASE}/force-load-contract/:contractId/edit`,
        name: `${BASE}.force-load-contract.edit`,
        component: () => import(`../views/${BASE}/force-load-contract/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-force-load-contract-edit'  },
    },

    //Customer Profile
    {
        path: `/${BASE}/customers`,
        name: `${BASE}.customers.index`,
        component: () => import(`../views/${BASE}/customers/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-customer-show'  },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/customers/create`,
        name: `${BASE}.customers.create`,
        component: () => import(`../views/${BASE}/customers/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-customer-create'  },
    },
    {
        path: `/${BASE}/customers/:customerId/edit`,
        name: `${BASE}.customers.edit`,
        component: () => import(`../views/${BASE}/customers/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission:'commercial-customer-edit'  },
    },
    {
        path: `/${BASE}/customers/:customerId`,
        name: `${BASE}.customers.show`,
        component: () => import(`../views/${BASE}/customers/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-customer-show'  },
    },

    //Contract Assign start
    {
        path: `/${BASE}/contract-assigns/voyages`,
        name: `${BASE}.contract-assigns.voyages`,
        component: () =>
            import(`../views/${BASE}/contract-assigns/voyages.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-contract-assign-voyages'  },
    },
    {
        path: `/${BASE}/contract-assigns/voyage/:voyageId`,
        name: `${BASE}.contract-assigns.voyage`,
        component: () => import(`../views/${BASE}/contract-assigns/assign.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-contract-assign-voyage'  },
    },
    {
        path: `/${BASE}/voyage/assign-contract/list/:voyageId`,
        name: `${BASE}.voyage.assign-contract.list`,
        component: () =>
            import(`../views/${BASE}/contract-assigns/contract-list.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-contract-assign-voyage'  },
    },
    {
        path: `/${BASE}/update/voyage/assigned-contract/:voyageId`,
        name: `${BASE}.contract-assigns.update`,
        component: () => import(`../views/${BASE}/contract-assigns/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-contract-assign-update'  },
    },
    //Contract Assign end

    {
        path: `/${BASE}/voyage/exchange-rate`,
        name: `${BASE}.voyage.exchange-rate`,
        component: () =>
            import(`../views/${BASE}/exchange-rate/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-voyage-exchange-rate'  },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },

    //customer assign start
    {
        path: `/${BASE}/voyages-slot-partners`,
        name: `${BASE}.voyages.slot-partners`,
        component: () =>
            import(`../views/${BASE}/customer-assigns/slot-partner-list.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-voyage-slot-partner-list'},
    },
    //customer assign end


    {
        path: `/${BASE}/booking-allocation`,
        name: `${BASE}.voyages.booking-allocation`,
        component: () =>
            import(`../views/${BASE}/booking-allocation/booking-allocation.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-voyage-slot-partner-list'},
    },


    {
        path: `/${BASE}/voyage-customer-containers/:voyageCustomerId`,
        name: `${BASE}.voyage.customer-container.list`,
        component: () => import(`../views/${BASE}/contract-assigns/voyage-customer-containers.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-contract-assign-voyage'  },
    },
    {
        path: `/${BASE}/voyage-dg-containers`,
        name: `${BASE}.voyage-dg-containers.voyage`,
        component: () =>
            import(`../views/${BASE}/container-assign/dg-container-list.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-dg-container-show'  },
    },
    {
        path: `/${BASE}/voyage-fr-containers`,
        name: `${BASE}.voyage-fr-containers.voyage`,
        component: () =>
            import(`../views/${BASE}/container-assign/fr-container-list.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-fr-container-show'  },
    },
    {
        path: `/${BASE}/voyage-rf-containers`,
        name: `${BASE}.voyage-rf-containers.voyage`,
        component: () =>
            import(`../views/${BASE}/container-assign/rf-container-list.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-rf-container-show'  },
    },

    //Invoice route
    {
        path: `/${BASE}/invoice/create`,
        name: `${BASE}.invoice.create`,
        component: () => import(`../views/${BASE}/invoices/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'dashboard'  },
    },
    {
        path: `/${BASE}/advance/invoices`,
        name: `${BASE}.advance.invoice`,
        component: () =>
            import(`../views/${BASE}/invoices/advance-invoice-list.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'dashboard'  },
    },
    {
        path: `/${BASE}/advance/invoice/create`,
        name: `${BASE}.advance.invoice.create`,
        component: () =>
            import(`../views/${BASE}/invoices/advance-invoice-create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-advanced-invoice-create'  },
    },
    {
        path: `/${BASE}/pending/invoices`,
        name: `${BASE}.pending.invoice`,
        component: () =>
            import(`../views/${BASE}/invoices/pending-invoice.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-pending-invoice-create'  },
    },
    {
        path: `/${BASE}/invoice/list`,
        name: `${BASE}.invoice.list`,
        component: () => import(`../views/${BASE}/invoices/invoice-list.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-invoice-list'  },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/download/customer/invoice/:invoiceId`,
        name: `${BASE}.download.customer.invoice`,
        component: () =>
            import(`../views/${BASE}/invoices/download-invoice.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'dashboard'  },
    },
    {
        path: `/${BASE}/invoice/add-debit-credit-note/:invoiceId`,
        name: `${BASE}.invoice.add.debit.credit.note`,
        component: () =>
            import(`../views/${BASE}/invoices/create-debit-credit-note.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-invoice-list'  },
    },
    {
        path: `/${BASE}/reports/container-wise-freight-calculation`,
        name: `${BASE}.container-wise-freight-calculation`,
        component: () =>
            import(`../views/${BASE}/reports/container-wise-freight-calculation.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'finance-container-wise-freight-calculation'  },
    },
    {
        path: `/${BASE}/reports/customer-loading-information`,
        name: `${BASE}.customer-loading-information`,
        component: () =>
            import(`../views/${BASE}/reports/customer-loading-information.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'commercial-customer-loading-information'  },
    },
    {
        path: `/${BASE}/schedule`,
        name: `${BASE}.schedule.index`,
        component: () => import(`../views/${BASE}/schedule/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'documentation-schedule-report'  },
    },
    {
        path: `/${BASE}/schedule/create`,
        name: `${BASE}.schedule.create`,
        component: () => import(`../views/${BASE}/schedule/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'documentation-schedule-report'  },
    },
    {
        path: `/${BASE}/schedule/:scheduleId/edit`,
        name: `${BASE}.schedule.edit`,
        component: () => import(`../views/${BASE}/schedule/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'documentation-schedule-report'  },
    },
    {
        path: `/${BASE}/schedule/:scheduleId`,
        name: `${BASE}.schedule.show`,
        component: () => import(`../views/${BASE}/schedule/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'documentation-schedule-report'  },
    },
    {
        path: `/${BASE}/schedule/website-layout`,
        name: `${BASE}.schedule.website.layout`,
        component: () => import(`../views/${BASE}/schedule/website-layout.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'documentation-schedule-report'  },
    },

];
