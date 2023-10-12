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
                ],
            },
            
        ]
    },
];