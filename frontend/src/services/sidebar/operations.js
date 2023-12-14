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
                        route: 'ops.configurations.ports.index',
                        label: 'Ports',
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
                        route: 'ops.configurations.cargo-tariffs.index',
                        label: 'Cargo Tariffs',
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
                route: 'ops.vessel-particulars.index',
                label: 'Vessel Particulars',
                preIcon: '',
                postIcon: '',
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: []
            },
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
                route: 'ops.charterer-invoices.index',
                label: 'Charterer Invoices',
                preIcon: '',
                postIcon: '',
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: []
            },
            {
                route: '',
                label: 'Voyage Reports',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'ops.lighter-noon-reports.index',
                        label: 'Noon Reports (L)',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.bulk-noon-reports.index',
                        label: 'Noon Reports (B)',
                        preIcon: '',
                        postIcon: '',
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                ],
            },

            {
                route: 'ops.expense-heads.index',
                label: 'Expense Head',
                preIcon: '',
                postIcon: '',
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: []
            },
            {
                route: 'ops.vessel-expense-heads.index',
                label: 'Vessel Expense Head',
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
                route: 'ops.bunker-bills.index',
                label: 'Bunker Bill',
                preIcon: '',
                postIcon: '',
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: []
            },
            
            // {
            //     route: '',
            //     label: 'Voyages',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: []
            // },
            // {
            //     route: '',
            //     label: 'Voyage Boat Note and Survey',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: []
            // },
            // {
            //     route: '',
            //     label: 'Charterer Profiles',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: []
            // },
            // {
            //     route: '',
            //     label: 'Charterer Contracts',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: []
            // },
            // {
            //     route: '',
            //     label: 'Charterer Invoices',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: []
            // },
            {
                route: '',
                label: 'Certification',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'ops.maritime-certifications.index',
                        label: 'All Certificates',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.vessel-certificates.index',
                        label: 'Vessel Wise Certificate',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    // {
                    //     route: 'ops.vessel-certificates.renew-list',
                    //     label: 'Renew Schedule',
                    //     preIcon: '',
                    //     postIcon: icons.DownArrow,
                    //     is_active: false,
                    //     is_open: false,
                    //     permissionKey: '',
                    //     subSubMenu: []
                    // },
                ]
            },
                    // {
                    //     route: 'ops.configurations.cargo-types.index',
                    //     label: 'Cargo Types',
                    //     preIcon: '',
                    //     postIcon: icons.DownArrow,
                    //     is_active: false,
                    //     is_open: false,
                    //     permissionKey: '',
                    //     subSubMenu: []
                    // },
                    // {
                    //     route: 'ops.configurations.customers.index',
                    //     label: 'Customers',
                    //     preIcon: '',
                    //     postIcon: icons.DownArrow,
                    //     is_active: false,
                    //     is_open: false,
                    //     permissionKey: '',
                    //     subSubMenu: []
                    // },
                    // {
                    //     route: 'ops.configurations.cargo-tariffs.index',
                    //     label: 'Cargo Tariffs',
                    //     preIcon: '',
                    //     postIcon: icons.DownArrow,
                    //     is_active: false,
                    //     is_open: false,
                    //     permissionKey: '',
                    //     subSubMenu: []
                    // }
                
            
            
        ]
    },
];