import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();

export default [
    {
        route: '',
        label: 'Crew Management',
        preIcon: icons.CrewDriver,
        postIcon: icons.DownArrow,
        is_active: false,
        is_open: false,
        permissionKey: '',
        subMenu: [
            {
                route: 'crw.ranks.index',
                label: 'Ranks',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: 'crw.vesselParticulars.index',
                label: 'Vessel Particulars',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: 'crw.policies.index',
                label: 'Policies',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: 'crw.checklists.index',
                label: 'Onboard Checklist',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: 'crw.vesselRequiredCrews.index',
                label: 'Vessel Crew Manning',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: 'crw.crewRequisitions.index',
                label: 'Crew Requisitions',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: 'crw.recruitmentApprovals.index',
                label: 'Recruitment Approvals',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: '',
                label: 'Agency',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'crw.agencies.index',
                        label: 'Profiles',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'crw.agencyContracts.index',
                        label: 'Contracts',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'crw.agencyBills.index',
                        label: 'Bills',
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
                route: 'crw.profiles.index',
                label: 'Crew Profiles',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [],
            },
            {
                route: '',
                label: 'Crew Documents',
                preIcon: '',
                postIcon: icons.DownArrow,
                is_active: false,
                is_open: false,
                permissionKey: '',
                subSubMenu: [
                    {
                        route: 'crw.documents.create',
                        label: 'Add Document',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                    {
                        route: 'crw.renew-schedules.index',
                        label: 'Renew Schedule',
                        preIcon: '',
                        postIcon: icons.DownArrow,
                        is_active: false,
                        is_open: false,
                        permissionKey: '',
                        subSubMenu: [],
                    },
                ],
            },
            // {
            //     route: 'crw.crewAssigns.index',
            //     label: 'Crew Assignment',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: [],
            // },
            // {
            //     route: 'crw.crwAttendances.index',
            //     label: 'Crew Attendance',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: [],
            // },
            // {
            //     route: 'crw.incidentRecords.index',
            //     label: 'Incidents Records',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: [],
            // },
            // {
            //     route: 'administration.users.index',
            //     label: 'Attendance Records',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: [],
            // },
            // {
            //     route: 'crw.crewSalaryStructures.index',
            //     label: 'Crew Salary Structure',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: [],
            // },
            // {
            //     route: 'crw.crewBankAccounts.index',
            //     label: 'Crew Bank Accounts',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: [],
            // },
            // {
            //     route: 'administration.users.index',
            //     label: 'Salary',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: [
            //         {
            //             route: 'administration.users.index',
            //             label: 'Generate Salary',
            //             preIcon: '',
            //             postIcon: icons.DownArrow,
            //             is_active: false,
            //             is_open: false,
            //             permissionKey: '',
            //             subSubMenu: [],
            //         },
            //         {
            //             route: 'administration.users.index',
            //             label: 'Salary List',
            //             preIcon: '',
            //             postIcon: icons.DownArrow,
            //             is_active: false,
            //             is_open: false,
            //             permissionKey: '',
            //             subSubMenu: [],
            //         },
            //     ],
            // },
            // {
            //     route: 'administration.users.index',
            //     label: 'Appraisal Records',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: [],
            // },
            // {
            //     route: 'administration.users.index',
            //     label: 'Rest Hour Records',
            //     preIcon: '',
            //     postIcon: icons.DownArrow,
            //     is_active: false,
            //     is_open: false,
            //     permissionKey: '',
            //     subSubMenu: [],
            // },
        ]
    },
];