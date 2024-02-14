import useHeroIcon from "../../assets/heroIcon";
import supplyChain2 from "./supply-chain2";
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
                    route: `${BASE}.warehouse.index`,
                    label: 'Warehouse',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: '',
                    subSubMenu:[],
                    },
                    {
                    route: `${BASE}.service.index`,
                    label: 'Service',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: '',
                    subSubMenu:[],
                    },
                    {
                    route: `${BASE}.vendor.index`,
                    label: 'Vendor',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: '',
                    subSubMenu:[],
                    },
                    {
                    route: `${BASE}.opening-stock.index`,
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
                {
                    route: '',
                    label: 'Procurement',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: '',
                    subSubMenu: [
                        {
                            route: `${BASE}.purchase-requisitions.index`,
                            label: 'Purchase Requisition',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.material-cs.index`,
                            label: 'Material CS',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.purchase-orders.index`,
                            label: 'Purchase Order',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.lc-records.index`,
                            label: 'LC Records',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.material-receipt-reports.index`,
                            label: 'Material Receipt Reports',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.material-costings.index`,
                            label: 'Material Costings',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.vendor-bills.index`,
                            label: 'Vendor Bills',
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
                    label: 'Store',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: '',
                    subSubMenu: [
                        {
                            route: `${BASE}.store-requisitions.index`,
                            label: 'Store Requisitions',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                        {
                        route: `${BASE}.store-issues.index`,
                        label: 'Store Issues',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                    },
                    {
                        route: `${BASE}.store-issue-returns.index`,
                        label: 'Store Issue Returns',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: []
                        },
                        {
                            route: `${BASE}.movement-requisitions.index`,
                            label: 'Movement Requisitions',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.movement-outs.index`,
                            label: 'Movement Outs',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.movement-ins.index`,
                            label: 'Movement Ins',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.material-adjustments.index`,
                            label: 'Material Adjustment',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: '',
                            subSubMenu: []
                        },
                    ]
                },

                
                ...supplyChain2,
                // supplyChain2[0],
               
        ]
    },
];
