import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();

export default [
    {
        route: '',
        label: 'Operations',
        preIcon: icons.VehicleShip,
        postIcon: icons.DownArrow,
        is_active: false,
        is_open: false,
        permissionKey: '',
        subMenu: [
            
            {
                route: '',
                label: 'Configurations',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: ['ops-cargo-tariff-view', 'ops-cargo-type-view', 'ops-expense-head-view', 'ops-maritime-certificate-view', 'ops-port-view'],
                subSubMenu: [
                    {
                        route: 'ops.configurations.cargo-tariffs.index',
                        label: 'Cargo Tariffs',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-cargo-tariff-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.configurations.cargo-types.index',
                        label: 'Cargo Types',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-cargo-type-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.expense-heads.index',
                        label: 'Expense Heads',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-expense-head-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.maritime-certifications.index',
                        label: 'Maritime Certificates',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-maritime-certificate-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.configurations.ports.index',
                        label: 'Ports',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-port-view',
                        subSubMenu: []
                    }
                ],
            },
            {
                route: '',
                label: 'Customers',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: ['ops-customer-view', 'ops-customer-invoice-view'],
                subSubMenu: [
                    {
                        route: 'ops.configurations.customers.index',
                        label: 'Customers',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-customer-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.customer-invoices.index',
                        label: 'Customer Invoices',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-customer-invoice-view',
                        subSubMenu: []
                    }
                ],
            },
            {
                route: '',
                label: 'Vessels',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: ['ops-vessel-view', 'ops-vessel-certificate-view', 'ops-vessel-expense-head-view', 'ops-vessel-particular-view'],
                subSubMenu: [
                    {
                        route: 'ops.vessels.index',
                        label: 'Vessels',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-vessel-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.vessel-certificates.index',
                        label: 'Vessel Certificates',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-vessel-certificate-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.vessel-expense-heads.index',
                        label: 'Vessel Expense Heads',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-vessel-expense-head-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.vessel-particulars.index',
                        label: 'Vessel Particulars',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-vessel-particular-view',
                        subSubMenu: []
                    }
                ],
            },
            {
                route: '',
                label: 'Charterers',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: ['ops-charterer-profile-view', 'ops-charterer-contract-view', 'ops-delivery-redelivery-view', 'ops-charterer-invoice-view'],
                subSubMenu: [
                    {
                        route: 'ops.charterer-profiles.index',
                        label: 'Charterer Profiles',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-charterer-profile-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.charterer-contracts.index',
                        label: 'Charterer Contracts',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-charterer-contract-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.delivery-redelivery.index',
                        label: 'Delivery & Re-delivery',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-delivery-redelivery-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.charterer-invoices.index',
                        label: 'Charterer Invoices',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-charterer-invoice-view',
                        subSubMenu: []
                    }
                ]
            },
            {
                route: '',
                label: 'Voyages',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: ['ops-voyage-view', 'ops-contract-assign-view', 'ops-lighterage-noon-report-view', 'ops-bulk-noon-report-view', 'ops-voyage-boat-note-view', 'ops-voyage-budget-view', 'ops-voyage-expense-view'],
                subSubMenu: [
                    {
                        route: 'ops.voyages.index',
                        label: 'Voyages',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-voyage-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.contract-assigns.index',
                        label: 'Contract Assign',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-contract-assign-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.lighter-noon-reports.index',
                        label: 'Lighterage Noon Reports',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-lighterage-noon-report-view',
                        subSubMenu: []
                    }, 
                    {
                        route: 'ops.bulk-noon-reports.index',
                        label: 'Bulk Noon Reports',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-bulk-noon-report-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.voyage-boat-notes.index',
                        label: 'Voyage Boat Note',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-voyage-boat-note-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.voyage-budgets.index',
                        label: 'Voyage Budget',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-voyage-budget-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.voyage-expenditures.index',
                        label: 'Voyage Expenses',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-voyage-expense-view',
                        subSubMenu: []
                    }
                ]
            },
            
            {
                route: '',
                label: 'Bunkers',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: ['ops-purchase-requisition-view', 'ops-vessel-bunker-view', 'ops-bunker-bill-view'],
                subSubMenu: [
                    {
                        route: 'ops.bunker-requisitions.index',
                        label: 'Purchase Requisition',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-purchase-requisition-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.vessel-bunkers.index',
                        label: 'Vessel Bunkers',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-vessel-bunker-view',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.bunker-bills.index',
                        label: 'Bunker Bill',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-bunker-bill-view',
                        subSubMenu: []
                    },
                ]
            },
            {
                route: '',
                label: 'Reports',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: ['ops-voyage-bunker-report', 'ops-vessel-bunker-report', 'ops-lighterage-voyage-report', 'ops-bulk-voyage-report', 'ops-port-wise-expense-report', 'ops-budget-vs-expense-report', 'ops-month-wise-expense-report'],
                subSubMenu: [
                    {
                        route: 'ops.reports.voyage-bunker-report',
                        label: 'Voyage Bunker Report',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-voyage-bunker-report',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.reports.vessel-bunker-report',
                        label: 'Vessel Bunker Report',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-vessel-bunker-report',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.reports.lighter-voyage-report',
                        label: 'Lighterage Voyage Report',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-lighterage-voyage-report',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.reports.bulk-voyage-report',
                        label: 'Bulk Voyage Report',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-bulk-voyage-report',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.reports.port-wise-expense-report',
                        label: 'Port Wise Expense Report',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-port-wise-expense-report',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.reports.budget-vs-expense-report',
                        label: 'Budget vs Expense Report',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-budget-vs-expense-report',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.reports.month-wise-expense-report',
                        label: 'Month Wise Expense Report',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'ops-month-wise-expense-report',
                        subSubMenu: []
                    }
                ]
            },
        ]
    },
];