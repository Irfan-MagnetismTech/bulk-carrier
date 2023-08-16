import { createApp } from "vue";
import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";
import App from "../App.vue";
import { auth } from './modules/auth';
import { dropZone } from './modules/dropZone';
import { sidebar } from './modules/sidebar';
import { activeMenu } from './modules/activeMenu';
import * as notification from './modules/notification';
import { erpConfiguration } from './modules/erpConfiguration';

const Store = createStore({
    plugins: [createPersistedState()],
    modules: {
        auth,
        sidebar,
        dropZone,
        notification,
        activeMenu,
        erpConfiguration
    },
});

const app = createApp(App);
app.use(Store);

export default Store;