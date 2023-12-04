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
                                    label: 'Job List',
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
                label: 'Running Hour Entry',
                preIcon: icons.User,
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            
            {
                route: '',
                label: 'Work Requisitions',
                preIcon: icons.User,
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'mnt.work-requisitions.create',
                        label: 'Create',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    
                    {
                        route: 'mnt.work-requisitions.index',
                        label: 'Pending',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    
                    {
                        route: 'mnt.wip-work-requisitions.index',
                        label: 'WIP',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
              
                    {
                        route: 'mnt.done-work-requisitions.index',
                        label: 'Done',
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
                label: 'Cri. Ship Function',
                preIcon: icons.User,
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'mnt.critical-functions.index',
                        label: 'Critical Functions',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                             
                    {
                        route: 'mnt.critical-item-categories.index',
                        label: 'Categories',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },

                    
                    {
                        route: 'mnt.critical-items.index',
                        label: 'Items',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    
                    {
                        route: 'mnt.critical-vessel-items.index',
                        label: 'Vessel Items',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },

                    
                    {
                        route: 'mnt.critical-spare-lists.index',
                        label: 'Critical Spare List',
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
                label: 'Vessel Surveys',
                preIcon: icons.User,
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'mnt.survey-items.index',
                        label: 'Survey Items',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                             
                    {
                        route: 'mnt.survey-types.index',
                        label: 'Survey Types',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },

                    
                    {
                        route: 'mnt.surveys.index',
                        label: 'Surveys',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                ]
            }                   
                
            
        ]
    },
];