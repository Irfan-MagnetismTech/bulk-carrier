import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();

export default [
    {
        route: '',
        label: 'Supply Chain',
        preIcon: icons.BookOpen,
        postIcon: icons.DownArrow,
        is_active: false,
        is_open: false,
        permissionKey: '',
        subMenu: [
            {
                route: 'administration.users.index',
                label: 'User',
                preIcon: icons.User,
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [
                    {
                        route: '',
                        label: 'New User',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: '',
                        label: 'List User',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
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
                preIcon: icons.User,
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: '',
                label: 'Permission',
                preIcon: icons.User,
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
        ]
    },
];