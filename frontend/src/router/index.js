import { createRouter, createWebHistory } from "vue-router";
import administration from "./administration";
import maintenance from "./maintenance";
import operations from "./operations";
import supplyChain from "./supply-chain";
import supplyChain2 from "./supply-chain2";
import crew from "./crew";
import account from "./account";
import Swal from "sweetalert2";

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
                ...maintenance,
                ...operations,
                ...supplyChain,
                ...crew,
                ...account,
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
            Swal.fire({
                icon: "",
                title: "Forbidden!",
                html: `<ul class="text-left list-disc text-red-500 mb-2 px-2 text-base"> <li>You do not have permission to access this resource.</li></ul>`,
                customClass: "swal-width",
            });
            //next({ name: 'login' });
        }
    } else {
        if (IS_LOGGED_IN && to.name === 'login') router.push({ name: 'dashboard' });
        else next();
    }
});


export default router;