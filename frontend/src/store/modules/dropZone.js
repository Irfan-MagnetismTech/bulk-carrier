export const dropZone = {
    state: {
        dropZoneFile: null,
    },

    mutations: {
        addDropZoneFile(state, file) {
            state.dropZoneFile = file;
        },
    },

    actions: {
        addDropZoneFile({ commit }, payload) {
            commit("addDropZoneFile", payload);
        },
    },

    getters: {
        getDropZoneFile(state) {
            return state.dropZoneFile;
        },
    }
};