export const sidebar = {
    state: {
        isSidebarOpen: false,
    },
    mutations: {
        openSidebar(state) {
            state.isSidebarOpen = true;
        },
        closeSidebar(state) {
            state.isSidebarOpen = false;
        }
    },

    getters: {
        getSidebarState(state) {
            return state.isSidebarOpen;
        }
    }
};