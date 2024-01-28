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
                        route: 'acc.salary-heads.index',
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
                route: 'acc.bank-reconciliation.index',
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
                label: 'Loan Management',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'acc.loans.index',
                        label: 'Loans',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'acc.loan-received.index',
                        label: 'Loan Received',
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
                        route: 'acc.fixed-assets.index',
                        label: 'Assets',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'acc.depreciations.index',
                        label: 'Depreciations',
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
                route: 'acc.cash-requisitions.index',
                label: 'Cash Requisitions',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: 'acc.advance-adjustments.index',
                label: 'Advance Adjustments',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: 'acc.administrative-salaries.index',
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
                        route: 'acc.ais-reports.balance-sheet',
                        label: 'Balance Sheet',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'acc.ais-reports.income-statement',
                        label: 'Income Statement',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'acc.ais-reports.ledger',
                        label: 'Ledger',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'acc.ais-reports.trial-balance',
                        label: 'Trial Balance',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'acc.ais-reports.day-book',
                        label: 'Day Book',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'acc.ais-reports.fixed-asset-statement',
                        label: 'Fixed Asset Statement',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'acc.ais-reports.cost-center-summary',
                        label: 'Cost Center Summary',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    // {
                    //     route: 'acc.ais-reports.cost-center-breakup',
                    //     label: 'Cost Center Breakup',
                    //     preIcon: '',
                    //     postIcon: icons.DownArrow,
                    //     is_active: false,
                    //     is_open: false,
                    //     permissionKey: '',
                    //     subSubMenu: [],
                    // },
                    // {
                    //     route: 'acc.ais-reports.receipt-payment-statement',
                    //     label: 'Receipt & Payment',
                    //     preIcon: '',
                    //     postIcon: icons.DownArrow,
                    //     is_active: false,
                    //     is_open: false,
                    //     permissionKey: '',
                    //     subSubMenu: [],
                    // },
                    // {
                    //     route: 'crw.vesselRequiredCrews.index',
                    //     label: 'Cost Center Breakup',
                    //     preIcon: '',
                    //     postIcon: icons.DownArrow,
                    //     is_active: false,
                    //     is_open: false,
                    //     permissionKey: '',
                    //     subSubMenu: [],
                    // },
                    // {
                    //     route: 'crw.vesselRequiredCrews.index',
                    //     label: 'Receipt & Payment Statement',
                    //     preIcon: '',
                    //     postIcon: icons.DownArrow,
                    //     is_active: false,
                    //     is_open: false,
                    //     permissionKey: '',
                    //     subSubMenu: [],
                    // },
                ],
            },
        ]
    },
];