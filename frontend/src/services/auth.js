import axios from "axios";
import { computed, ref } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

export default function useAuth() {
    const store = useStore();
    const router = useRouter();
    const username = computed(() => store.getters.getCurrentUser ? store.getters.getCurrentUser.name : '');
    const userPort = computed(() => store.getters.getCurrentUser ? store.getters.getCurrentUser.port : '');
    const userRole = computed(() => store.getters.getCurrentUser ? store.getters.getCurrentUser.role : '');
    const userBusinessUnit = computed(() => store.getters.getCurrentUser ? store.getters.getCurrentUser.business_unit : '');
    const isLoading = ref(false);
    const errors = ref(null);

    function errorAndLoader(response) {
        errors.value = response.response.data;
        isLoading.value = false;
    }

    function login(user) {
        delete axios.defaults.headers.common["Authorization"];
        isLoading.value = true;

        store.dispatch("login", user)
            .then(() => {
                store.dispatch("getCurrentUser").then((currentUserResponse) => {
                    router.go({ name: "dashboard" });
                }).catch((catchCurrentUserError) => {
                    errorAndLoader(catchCurrentUserError);
                });
                errors.value = null;
            })
            .catch((catchLoginError) => {
                errorAndLoader(catchLoginError);
            });
    }

    function logout() {
        store.dispatch("logout")
            .then((response) => {
                router.go({ name: "login" });
            })
            .catch((error) => {
                alert(error);
            });
    }

    return {
        username,
        userRole,
        userPort,
        userBusinessUnit,
        login,
        logout,
        isLoading,
        errors,
    }
};