export const activeMenu = {
    state: {
        name: 'dashboard',
        routeName: 'dashboard',
    },
    mutations: {
        setActiveMenu(state, newValue) {
            state.name = newValue;
        },
        setActiveRoute(state, newValue) {
            state.routeName = newValue;
        },
    },

    getters: {
        getActiveMenuState(state) {
            return state.activeMenu;
        },
        getActiveRouteState(state) {
            return state.activeMenu;
        }
    }
};