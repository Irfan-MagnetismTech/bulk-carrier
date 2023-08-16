<script setup>
import TheHeader from "../../components/TheHeader.vue";
import TheSidebar from "../../components/TheSidebar.vue";
import useSidebarProfilemenu from '../../services/sidebarProfileMenu';
import TheSubMenu from "../../components/TheSubMenu.vue";

const { sidebar, isSidebarOpen } = useSidebarProfilemenu();
</script>

<template>
    <div>
        <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': true }">
            <!-- Sidebar -->
<!--            <aside class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0 shadow-lg">-->
<!--                <the-sidebar v-once></the-sidebar>-->
<!--            </aside>-->
<!--            &lt;!&ndash; Mobile menu &ndash;&gt;-->
<!--            <div v-show="isSidebarOpen" class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center" style="display: none;"></div>-->
<!--            <aside v-show="isSidebarOpen" ref="sidebar" @keydown.esc="close_side_menu()" class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white dark:bg-gray-800 md:hidden" style="display: none;">-->
<!--                <the-sidebar></the-sidebar>-->
<!--            </aside>-->
            <!-- Container -->
            <div class="flex flex-col flex-1 w-full">
                <the-header v-once></the-header>
                <main class="h-full overflow-y-auto">
                    <div class="w-full px-6 mx-auto grid">
                        <!-- Main content here -->
                        <the-sub-menu></the-sub-menu>
                        <router-view></router-view>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>