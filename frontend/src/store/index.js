import { createApp } from "vue";
import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";
import App from "../App.vue";
import { auth } from './modules/auth';
import { dropZone } from './modules/dropZone';
import { sidebar } from './modules/sidebar';
import * as notification from './modules/notification';

const Store = createStore({
    plugins: [createPersistedState()],
    modules: {
        auth,
        sidebar,
        dropZone,
        notification,
    },
});

const app = createApp(App);
app.use(Store);

export default Store;