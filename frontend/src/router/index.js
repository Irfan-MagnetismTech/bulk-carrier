import { createRouter, createWebHistory } from "vue-router";
import administration from "./administration";
import operations from "./operations";
import supplyChain from "./supply-chain";

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
                ...administration,
                ...operations,
                ...supplyChain,
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
		//if ((IS_LOGGED_IN && hasPermission) || (IS_LOGGED_IN && ROLE == 'super-admin')) next();
		if ((IS_LOGGED_IN) || (IS_LOGGED_IN && ROLE == 'super-admin')) next();
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