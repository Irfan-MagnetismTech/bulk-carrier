import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();
const BASE = "scm";

export default [
             {
                    route: '',
                    label: 'Work',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: '',
                    subSubMenu: [
                        {
                            route: `${BASE}.work-requisitions.index`,
                            label: 'Work Requisition',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.work-cs.index`,
                            label: 'Work Cs',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.work-orders.index`,
                            label: 'Work Order',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                        
                        {
                            route: `${BASE}.work-receipt-reports.index`,
                            label: 'Work Receipt Report',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },

            
                    ]
                },
                {
                    route: '',
                    label: 'Reports',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: '',
                    subSubMenu: [
                        {
                            route: `${BASE}.reports.inventory-report`,
                            label: 'Inventory Report',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                        
                        {
                            route: `${BASE}.reports.stock-history-report`,
                            label: 'Stock History Report',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },

                    ]
                },
             ]
