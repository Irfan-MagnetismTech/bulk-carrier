import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();

export default [
    {
        route: 'dashboard',
        label: 'Dashboard',
        preIcon: icons.HomeSolid,
        postIcon: '',
        is_active: false,
        is_open: false,
        permissionKey: '',
        subMenu: [],
    },
    {
        route: '',
        label: 'Control User',
        preIcon: icons.User,
        postIcon: icons.DownArrow,
        is_active: false,
        is_open: false,
        permissionKey: '',
        subMenu: [
            {
                route: 'administration.users.index',
                label: 'User',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: 'administration.user.roles.index',
                label: 'Role',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: 'administration.user.permissions.index',
                label: 'Permission',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
        ]
    },
];