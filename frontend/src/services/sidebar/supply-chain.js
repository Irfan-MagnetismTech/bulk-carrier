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
                    label: 'Configurations',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: '',
                    subSubMenu: [
                    {
                        route: `${BASE}.units.index`,
                        label: 'Units',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'scm-unit-view',
                        subSubMenu: [],
                    },
                    {
                        route: `${BASE}.material-category.index`,
                        label: 'Material Categories',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'scm-material-category-view',
                        subSubMenu: [],
                    },
                    {
                    route: `${BASE}.material.index`,
                    label: 'Materials',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: 'scm-material-view',
                    subSubMenu: [],
                    },
                    {
                    route: `${BASE}.warehouse.index`,
                    label: 'Warehouses',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: 'scm-warehouse-view',
                    subSubMenu:[],
                    },
                    {
                    route: `${BASE}.service.index`,
                    label: 'Services',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: 'scm-service-view',
                    subSubMenu:[],
                    },
                    {
                    route: `${BASE}.vendor.index`,
                    label: 'Vendors',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: 'scm-vendor-view',
                    subSubMenu:[],
                    },
                    {
                    route: `${BASE}.opening-stock.index`,
                    label: 'Opening Stock',
                    preIcon: icons.User,
                    postIcon: icons.DownArrow,
                    is_active: false,
                    is_open: false,
                    permissionKey: 'scm-opening-stock-view',
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
                            label: 'Purchase Requisitions',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: 'scm-purchase-requisition-view',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.material-cs.index`,
                            label: 'Material CS',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: 'scm-material-cs-view',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.purchase-orders.index`,
                            label: 'Purchase Orders',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: 'scm-purchase-order-view',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.lc-records.index`,
                            label: 'LC Records',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: 'scm-lc-records-view',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.material-receipt-reports.index`,
                            label: 'Material Receipt Reports',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: 'scm-material-receipt-report-view',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.material-costings.index`,
                            label: 'Material Costings',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: 'scm-material-costing-view',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.vendor-bills.index`,
                            label: 'Vendor Bills',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: 'scm-vendor-bill-view',
                            subSubMenu: []
                        },
                        
                    ]
                },
                {
                    route: '',
                    label: 'Stores',
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
                            permissionKey: 'scm-store-requisition-view',
                            subSubMenu: []
                        },
                        {
                        route: `${BASE}.store-issues.index`,
                        label: 'Store Issues',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'scm-store-issue-view',
                        subSubMenu: []
                    },
                    {
                        route: `${BASE}.store-issue-returns.index`,
                        label: 'Store Issue Returns',
                        preIcon: icons.User,
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: 'scm-store-issue-return-view',
                        subSubMenu: []
                        },
                        {
                            route: `${BASE}.movement-requisitions.index`,
                            label: 'Movement Requisitions',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: 'scm-movement-requisition-view',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.movement-outs.index`,
                            label: 'Movement Outs',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: 'scm-movement-out-view',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.movement-ins.index`,
                            label: 'Movement Ins',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: 'scm-movement-in-view',
                            subSubMenu: []
                        },
                        {
                            route: `${BASE}.material-adjustments.index`,
                            label: 'Material Adjustment',
                            preIcon: icons.User,
                            postIcon: icons.DownArrow,
                            is_active: false,
                            is_open: false,
                            permissionKey: 'scm-material-adjustment-view',
                            subSubMenu: []
                        },
                    ]
                },

                
                ...supplyChain2,
                // supplyChain2[0],
               
        ]
    },
];
