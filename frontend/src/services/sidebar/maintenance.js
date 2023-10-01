import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();

export default [
    {
        route: '',
        label: 'Maintenance',
        preIcon: icons.User,
        postIcon: icons.DownArrow,
        is_active: false,
        is_open: false,
        permissionKey: '',
        subMenu: [
            {
                route: '',
                label: 'Ship Department',
                preIcon: icons.User,
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'maintenance.ship-department.create',
                        label: 'New Ship Department',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'maintenance.ship-department.index',
                        label: 'List Ship Department',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                ],
            },
            
        ]
    },
];