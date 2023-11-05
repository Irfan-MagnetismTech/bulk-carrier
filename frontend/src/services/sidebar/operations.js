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
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.configurations.cargo-types.index',
                        label: 'Cargo Types',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.configurations.customers.index',
                        label: 'Customers',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: 'ops.configurations.cargo-tariffs.index',
                        label: 'Cargo Tariffs',
                        preIcon: '',
                        postIcon: icons.DownArrow,
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
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: []
            },
            // {
            //     route: 'ops.charterer-profiles.index',
            //     label: 'Charterer Profiles',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: []
            // },
            // {
            //     route: 'ops.vessel-particulars.index',
            //     label: 'Vessel Particulars',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: []
            // },
            // {
            //     route: 'ops.voyages.index',
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
            // {
            //     route: '',
            //     label: 'Certification',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: [
            //         {
            //             route: 'ops.maritime-certifications.index',
            //             label: 'All Certificates',
            //             preIcon: '',
            //             postIcon: icons.DownArrow,
            //             is_active: false,
            //             is_open: false,
            //             permissionKey: '',
            //             subSubMenu: []
            //         },
            //         {
            //             route: 'ops.vessel-certificates.index',
            //             label: 'Vessel Wise Certificate',
            //             preIcon: '',
            //             postIcon: icons.DownArrow,
            //             is_active: false,
            //             is_open: false,
            //             permissionKey: '',
            //             subSubMenu: []
            //         },
            //         {
            //             route: 'ops.vessel-certificates.renew-list',
            //             label: 'Renew Schedule',
            //             preIcon: '',
            //             postIcon: icons.DownArrow,
            //             is_active: false,
            //             is_open: false,
            //             permissionKey: '',
            //             subSubMenu: []
            //         },
            //     ]
            // },
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