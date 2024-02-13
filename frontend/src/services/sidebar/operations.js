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
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'ops.configurations.cargo-tariffs.index',
                        label: 'Cargo Tariffs',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.configurations.cargo-types.index',
                        label: 'Cargo Types',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.expense-heads.index',
                        label: 'Expense Heads',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.maritime-certifications.index',
                        label: 'Maritime Certificates',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.configurations.ports.index',
                        label: 'Ports',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
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
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'ops.configurations.customers.index',
                        label: 'Customers',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.customer-invoices.index',
                        label: 'Customer Invoices',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
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
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'ops.vessels.index',
                        label: 'Vessels',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.vessel-certificates.index',
                        label: 'Vessel Certificates',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.vessel-expense-heads.index',
                        label: 'Vessel Expense Heads',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.vessel-particulars.index',
                        label: 'Vessel Particulars',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
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
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'ops.charterer-profiles.index',
                        label: 'Charterer Profiles',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.charterer-contracts.index',
                        label: 'Charterer Contracts',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.delivery-redelivery.index',
                        label: 'Delivery & Re-delivery',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.charterer-invoices.index',
                        label: 'Charterer Invoices',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
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
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'ops.voyages.index',
                        label: 'Voyages',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.contract-assigns.index',
                        label: 'Contract Assign',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.lighter-noon-reports.index',
                        label: 'Lighterage Noon Reports',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    }, 
                    {
                        route: 'ops.bulk-noon-reports.index',
                        label: 'Bulk Noon Reports',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.voyage-boat-notes.index',
                        label: 'Voyage Boat Note',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.voyage-budgets.index',
                        label: 'Voyage Budget',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.voyage-expenditures.index',
                        label: 'Voyage Expenses',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
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
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'ops.bunker-requisitions.index',
                        label: 'Purchase Requisition',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.vessel-bunkers.index',
                        label: 'Vessel Bunkers',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.bunker-bills.index',
                        label: 'Bunker Bill',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
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
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'ops.reports.vessel-bunker-report',
                        label: 'Voyage Bunker Report',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.reports.gross-bunker-report',
                        label: 'Vessel Bunker Report',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.reports.lighter-voyage-report',
                        label: 'Lighterage Voyage Report',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.reports.bulk-voyage-report',
                        label: 'Bulk Voyage Report',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.reports.port-wise-expense-report',
                        label: 'Port Wise Expense Report',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.reports.budget-vs-expense-report',
                        label: 'Budget vs Expense Report',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.reports.month-wise-expense-report',
                        label: 'Month Wise Expense Report',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    }
                ]
            },
        ]
    },
];