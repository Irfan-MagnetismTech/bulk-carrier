import { createRouter, createWebHistory } from "vue-router";
import commercial from "./commercial";
import dataencoding from "./dataencoding";
import documentation from "./documentation";
import finance from "./finance";
import operation from "./operation";
import authorization from "./authorization";
import stevedorage from "./stevedorage";

const router = createRouter({

    history: createWebHistory('/'),
    mode: 'history',
    routes: [
        {
            path: "/login",
            name: "login",
            component: () =>
                import ("../views/login.vue"),
        },
        {
            path: "/",
            component: () =>
                import ("../views/layouts/main.vue"),
            children: [
                {
                    path: "",
                    name: "dashboard",
                    component: () => import ("../views/dashboard.vue"),
                    meta: { requiresAuth: true, role: "all", permission: "dashboard" },
                },
                {
                    path: "/user/activity/log/:subject_type/:subject_id",
                    name: "user.activity.log",
                    component: () => import ("../views/authorization/activity-log/index.vue"),
                    meta: { requiresAuth: true, role: "all", permission: "commercial-money-receipt-show" },
                },
                {
                    path: "/money-receipts",
                    name: "money.receipt.index",
                    component: () => import ("../views/moneyreceipt/list.vue"),
                    meta: { requiresAuth: true, role: "all", permission: "commercial-money-receipt-show" },
                },
                {
                    path: "/money-receipts/create",
                    name: "money.receipt.create",
                    component: () => import ("../views/moneyreceipt/create-money-receipt.vue"),
                    meta: { requiresAuth: true, role: "all", permission: "commercial-money-receipt-show" },
                },
                {
                    path: `/money-receipts/:receiptId/edit`,
                    name: `money.receipt.edit`,
                    component: () => import ("../views/moneyreceipt/edit.vue"),
                    meta: { requiresAuth: true, role: "all", permission: "commercial-money-receipt-show" },
                },
                {
                    path: `/money-receipts/:receiptId`,
                    name: `money.receipt.show`,
                    component: () => import(`../views/moneyreceipt/show.vue`),
                    meta: { requiresAuth: true, role: "all", permission: 'commercial-money-receipt-show'  },
                },
                {
                    path: "/container-types",
                    name: "containertype.index",
                    component: () => import ("../views/containertypes/index.vue"),
                    meta: { requiresAuth: true, role: "all", permission: "dataencoding-container-type-show" },
                    props: (route) => ({ page: parseInt(route.query.page) || 1 }),
                },
                {
                    path: "/container-type/create",
                    name: "containertype.create",
                    component: () => import ("../views/containertypes/create.vue"),
                    meta: { requiresAuth: true, role: "all", permission: "commercial-money-receipt-show" },
                },
                {
                    path: "/container-type/:containerTypeId/edit",
                    name: "containertype.edit",
                    component: () => import ("../views/containertypes/edit.vue"),
                    meta: { requiresAuth: true, role: "all", permission: "commercial-money-receipt-show" },
                },
                {
                    path: '/ports',
                    name: 'ports.index',
                    component: () => import('../views/ports/index.vue'),
                    meta: { requiresAuth: true, role: "all", permission: "dataencoding-port-show" },
                    props: (route) => ({ page: parseInt(route.query.page) || 1 }),
                },
                {
                    path: "/ports/create",
                    name: "ports.create",
                    component: () => import ("../views/ports/create.vue"),
                    meta: { requiresAuth: true, role: "all", permission: "dataencoding-port-create" },
                },
                {
                    path: "/ports/:portId/edit",
                    name: "ports.edit",
                    component: () => import ("../views/ports/edit.vue"),
                    meta: { requiresAuth: true, role: "all", permission: "dataencoding-port-edit" },
                },
                {
                    path: "/banks",
                    name: "banks.index",
                    component: () => import ("../views/banks/index.vue"),
                    meta: { requiresAuth: true, role: "all", permission: "dataencoding-bank-show" },
		            props: (route) => ({ page: parseInt(route.query.page) || 1 }),
                },
                {
                    path: "/banks/create",
                    name: "banks.create",
                    component: () => import ("../views/banks/create.vue"),
                    meta: { requiresAuth: true, role: "all", permission: "commercial-money-receipt-show" },
                },
                {
                    path: "/banks/:bankId/edit",
                    name: "banks.edit",
                    component: () => import ("../views/banks/edit.vue"),
                    meta: { requiresAuth: true, role: "all", permission: "commercial-money-receipt-show" },
                },
                ...authorization,
                ...operation,
                ...commercial,
                ...documentation,
                ...finance,
                ...dataencoding,
                ...stevedorage,
                { path: '/:pathMatch(.*)*', component: () => import ("../views/404.vue"), },
            ]
        },
    ],
});

// guard
router.beforeEach((to, from, next) => {
	const USER = Store.getters.getCurrentUser;
	const ROLE = USER?.role ?? null;
	const IS_LOGGED_IN = USER?.email ?? false;
    const PERMISSIONS = USER?.permissions ?? [];

    // set interval and automatic token expired logout if no activity in 1 minutes even tab is closed or browser is closed
    // if (IS_LOGGED_IN) {
    //
    //     const tokenResponse = Store.getters.getTokenResponse;
    //     let currentTime = new Date().getTime();
    //
    //     if(tokenResponse.auto_logout_at < currentTime) {
    //         Store.dispatch('logout');
    //         router.go({ name: 'login' });
    //     } else{
    //         tokenResponse.auto_logout_at = currentTime + 7200000;
    //     }
    //
    //     let idleInterval = setInterval(checkIdleTime, 60000);
    //     let idleTime = 0;
    //     function checkIdleTime() {
    //         idleTime++;
    //         if (idleTime > 1) {
    //             Store.dispatch('logout');
    //             clearInterval(idleInterval);
    //             //router go to login page
    //             router.go({ name: 'login' });
    //         }
    //     }
    //
    //     let interval = setInterval(() => {
    //         Store.dispatch('logout');
    //         clearInterval(interval);
    //         //router go to login page
    //         router.go({ name: 'login' });
    //     }, 60000);
    // }

	if (to.meta.requiresAuth) {
        const hasRole = to?.meta?.role?.includes(ROLE);
        const isCommon = to?.meta?.role?.includes("all");
        const currentRoutePermission = to?.meta?.permission;
        const hasPermission = PERMISSIONS.includes(currentRoutePermission);
		if ((IS_LOGGED_IN && hasPermission) || (IS_LOGGED_IN && ROLE == 'super-admin')) next();
		else
            if(!IS_LOGGED_IN) {
                next({ name: 'login' });
            } else{
                alert('You are not authorized to access this page');
                next({ name: 'login' });
            }
	} else {
		if (IS_LOGGED_IN && to.name === 'login') router.push({ name: 'dashboard' });
		else next();
	}
});


export default router;