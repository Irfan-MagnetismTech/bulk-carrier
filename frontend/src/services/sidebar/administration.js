import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();

export default [
    {
        route: '',
        label: 'Control User',
        icon: icons.DownArrow,
        is_active: false,
        is_open: false,
        permissionKey: '',
        subMenu: [
            {
                route: '',
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
                        route: 'administration.users.index',
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
                route: 'administration.users.index',
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