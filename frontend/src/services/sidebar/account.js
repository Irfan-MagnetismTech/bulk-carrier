import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();

export default [
    {
        route: '',
        label: 'Accounts',
        preIcon: icons.Calculator,
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
                        route: 'acc.cost-centers.index',
                        label: 'Cost Centers',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                    },
                    {
                        route: 'acc.balance-income-lines.index',
                        label: 'Balance Income Lines',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                    },
                    {
                        route: 'acc.chart-of-accounts.index',
                        label: 'Chart of Accounts',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                    },
                    {
                        route: 'acc.opening-balances.index',
                        label: 'Opening Balance',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                    },
                    {
                        route: 'acc.bank-accounts.index',
                        label: 'Bank Accounts',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                    },
                    {
                        route: '',
                        label: 'Salary Heads',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                    },
                    {
                        route: 'acc.cash-accounts.index',
                        label: 'Cash Accounts',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                    },
                ],
            },
            {
                route: 'acc.transactions.index',
                label: 'Vouchers',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: 'crw.policies.index',
                label: 'Bank Reconciliation',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: '',
                label: 'Loans',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'crw.vesselRequiredCrews.index',
                        label: 'Loans',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'crw.vesselRequiredCrews.index',
                        label: 'Loan Receipts',
                        preIcon: '',
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
                label: 'Fixed Assets',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'crw.vesselRequiredCrews.index',
                        label: 'Assets',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'crw.vesselRequiredCrews.index',
                        label: 'Depreciation',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                ],
            },
            {
                route: 'crw.vesselRequiredCrews.index',
                label: 'Cash Requisitions',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: 'crw.vesselRequiredCrews.index',
                label: 'Advance Adjustments',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: 'crw.vesselRequiredCrews.index',
                label: 'Salary',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: '',
                label: 'AIS Reports',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'crw.vesselRequiredCrews.index',
                        label: 'Balance Sheet',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'crw.vesselRequiredCrews.index',
                        label: 'Income Statement',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'crw.vesselRequiredCrews.index',
                        label: 'Ledger',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'crw.vesselRequiredCrews.index',
                        label: 'Trial Balance',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'crw.vesselRequiredCrews.index',
                        label: 'Day Book',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'crw.vesselRequiredCrews.index',
                        label: 'Fixed Asset Statement',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'crw.vesselRequiredCrews.index',
                        label: 'Cost Center Summary',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'crw.vesselRequiredCrews.index',
                        label: 'Cost Center Breakup',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'crw.vesselRequiredCrews.index',
                        label: 'Receipt & Payment Statement',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                ],
            },
        ]
    },
];