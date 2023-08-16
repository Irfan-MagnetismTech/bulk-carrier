import axios from 'axios';
import NProgress from 'nprogress';
import 'nprogress/nprogress.css';
import 'vue-loading-overlay/dist/vue-loading.css';
import { createApp } from 'vue';
import App from './App.vue';
import './assets/css/tailwind.css';
import './assets/css/custom.css';
import './assets/js/custom.js';
import env from './config/env';
import Router from './router/index.js';
import Store from './store/index.js';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import VueToast from 'vue-toast-notification';
// import 'vue-toast-notification/dist/theme-default.css';
import 'vue-toast-notification/dist/theme-sugar.css';
import JsonExcel from "vue-json-excel3";

NProgress.configure({ showSpinner: false });

// baseURL(server) for axios 
axios.defaults.baseURL = env.BASE_API_URL;

// allowing axios, auth api and store to be used in the global scope
window.axios = axios;
window.Store = Store;

if (Store.getters.getAccessToken)
    axios.defaults.headers.common["Authorization"] = `Bearer ${Store.getters.getAccessToken}`;

// creating the app
const app = createApp(App);
app.use(Router);
app.use(Store);
app.mount("#app");
app.component('v-select', vSelect);
app.component("downloadExcel", JsonExcel);
