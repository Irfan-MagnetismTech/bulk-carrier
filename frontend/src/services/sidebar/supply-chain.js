import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();

export default [
    {
        route: '',
        label: 'Supply Chain',
        icon: icons.DownArrow,
        is_active: false,
        is_open: false,
        permissionKey: '',
        subMenu: [
            {
                route: 'administration.users.index',
                label: 'User',
                icon: icons.User,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [
                    {
                        route: '',
                        label: 'New User',
                        icon: icons.User,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: '',
                        label: 'List User',
                        icon: icons.User,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                ],
            },
            {
                route: '',
                label: 'Role',
                icon: icons.User,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: '',
                label: 'Permission',
                icon: icons.User,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
        ]
    },
];