import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();
const BASE = "scm";

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
                    route: '',
                    label: 'Configuration',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: '',
                    subSubMenu: [
                    {
                        route: `${BASE}.units.index`,
                        label: 'Unit',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: `${BASE}.material-category.index`,
                        label: 'Material Category',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                    route: `${BASE}.material.index`,
                    label: 'Material',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: '',
                    subSubMenu: [],
                    },
                    {
                    route: `${BASE}.warehouse.create`,
                    label: 'Warehouse',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: '',
                    subSubMenu:[],
                    },
                    {
                    route: `${BASE}.service.create`,
                    label: 'Service',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: '',
                    subSubMenu:[],
                    },
                    {
                    route: `${BASE}.vendor.create`,
                    label: 'Vendor',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: '',
                    subSubMenu:[],
                    },
                    {
                    route: `${BASE}.opening-stock.create`,
                    label: 'Opening Stock',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: '',
                    subSubMenu:[],
                    },
                ]
            },
            
        ]
    },
];
