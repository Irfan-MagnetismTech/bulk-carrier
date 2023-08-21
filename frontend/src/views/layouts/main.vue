<template>
    <div>
        <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': true }">
            <!-- Sidebar -->
            <aside class="sidebar relative bg-[#151529] z-20 hidden w-64 overflow-y-auto dark:bg-gray-800 md:block flex-shrink-0 shadow-lg" style="transition: all 0.5s ease 0s; left: 0px">
                <the-sidebar v-once></the-sidebar>
            </aside>
            <!-- Mobile menu -->
            <div v-show="isSidebarOpen" class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center" style="display: none;"></div>

            <aside ref="sidebar" @keydown.esc="close_side_menu()" class="sidebar fixed bg-[#151529] inset-y-0 z-20 flex-shrink-0 w-64 mt-8 overflow-y-auto dark:bg-gray-800 md:hidden" style="transition: all 0.5s ease 0s; left: -260px">
                <the-sidebar></the-sidebar>
            </aside>
            <!-- Container -->
            <div class="flex flex-col flex-1 w-full">
                <the-header v-once></the-header>
                <main class="h-full overflow-y-auto">
                    <div class="w-full px-6 mx-auto grid">
                        <!-- Main content here -->
                        <router-view></router-view>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>

<script setup>
import TheHeader from "../../components/TheHeader.vue";
import TheSidebar from "../../components/TheSidebar.vue";
import useSidebarProfilemenu from '../../services/sidebarProfileMenu';

const { sidebar, isSidebarOpen } = useSidebarProfilemenu();
</script>

<style>
    .sidebar span, .sidebar a{
        color: #a3a6b7 !important;
        @apply duration-200 ease-linear px-2
    }

    .sidebar li:hover a,.sidebar li:hover span{
        color: #fff !important;
        @apply duration-200 ease-linear px-2
    }
</style>