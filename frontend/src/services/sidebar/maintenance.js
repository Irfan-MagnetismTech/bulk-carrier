import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();

export default [
    {
        route: '',
        label: 'Maintenance',
        preIcon: icons.wrenchScrewdriver,
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
                                    route: 'mnt.ship-departments.index',
                                    label: 'Ship Department',
                                    preIcon: icons.User,
                                    postIcon: icons.DownArrow,
                                    is_active: false,
                                    is_open: false,
                                    permissionKey: '',
                                    subSubMenu: [
                                        // {
                                        //     route: 'mnt.ship-departments.create',
                                        //     label: 'New',
                                        //     preIcon: icons.User,
                                        //     postIcon: icons.DownArrow,
                                        //     is_active: false,
                                        //     is_open: false,
                                        //     permissionKey: '',
                                        //     subSubMenu: [],
                                        // },
                                        // {
                                        //     route: 'mnt.ship-departments.index',
                                        //     label: 'List',
                                        //     preIcon: icons.User,
                                        //     postIcon: icons.DownArrow,
                                        //     is_active: false,
                                        //     is_open: false,
                                        //     permissionKey: '',
                                        //     subSubMenu: [],
                                        // },
                                    ],
                                },
                                {
                                    route: 'mnt.item-groups.index',
                                    label: 'Item Group',
                                    preIcon: icons.User,
                                    postIcon: icons.DownArrow,
                                    is_active: false,
                                    is_open: false,
                                    permissionKey: '',
                                    subSubMenu: [],
                                },
                                {
                                    route: 'mnt.items.index',
                                    label: 'Item',
                                    preIcon: icons.User,
                                    postIcon: icons.DownArrow,
                                    is_active: false,
                                    is_open: false,
                                    permissionKey: '',
                                    subSubMenu: [],
                                },
                                {
                                    route: 'mnt.jobs.index',
                                    label: 'Job',
                                    preIcon: icons.User,
                                    postIcon: icons.DownArrow,
                                    is_active: false,
                                    is_open: false,
                                    permissionKey: '',
                                    subSubMenu: [],
                                }
                            ],
            },
            
            {
                route: 'mnt.run-hours.index',
                label: 'Run Hour Entry',
                preIcon: icons.User,
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            
            {
                route: 'mnt.work-requisitions.index',
                label: 'Work Requisitions',
                preIcon: icons.User,
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            
            {
                route: 'mnt.wip-work-requisitions.index',
                label: 'WIP Work Requisitions',
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