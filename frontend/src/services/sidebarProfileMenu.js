import { onClickOutside, onKeyStroke } from "@vueuse/core";
import { computed, onMounted, ref } from 'vue';
import { useStore } from "vuex";

export default function useSidebarProfileMenu() {
    const store = useStore();
    const profileMenu = ref(null);
    const isProfileMenuOpen = ref(false);
    const sidebar = ref(null);
    const isSidebarOpen = ref(computed(() => store.getters.getSidebarState));

    function openSidebar() {
        store.commit('openSidebar');
    }

    function closeSidebar() {
        store.commit('closeSidebar');
    }

    function closeProfileMenu() {
        isProfileMenuOpen.value = false;
    }

    onClickOutside(sidebar, (event) => {
        if (isSidebarOpen.value) {
            closeSidebar();
        }
    });

    onClickOutside(profileMenu, () => {
        closeProfileMenu();
    });

    onKeyStroke('Escape', (e) => {
        closeSidebar();
        closeProfileMenu();
    });

    onMounted(() => {
        closeSidebar();
    });

    return {
        profileMenu,
        isProfileMenuOpen,
        sidebar,
        isSidebarOpen,
        openSidebar,
        closeSidebar
    }
}