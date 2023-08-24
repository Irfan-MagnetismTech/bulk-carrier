<script setup>
import TheHeader from "../../components/TheHeader.vue";
import TheSidebar from "../../components/TheSidebar.vue";
import useSidebarProfilemenu from '../../services/sidebarProfileMenu';
const { sidebar, isSidebarOpen } = useSidebarProfilemenu();
</script>
<template>
    <div>
        <div class="flex h-screen bg-gray-50" :class="{ 'overflow-hidden': true }">
            <!-- Sidebar -->
            <aside class="sidebar relative bg-[#151529] z-20 hidden w-64 overflow-y-auto md:block flex-shrink-0 shadow-lg" style="transition: all 0.5s ease 0s; left: 0px">
                <the-sidebar v-once></the-sidebar>
            </aside>
            <!-- Mobile menu -->
            <div v-show="isSidebarOpen" class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center" style="display: none;"></div>

            <aside ref="sidebar" @keydown.esc="close_side_menu()" class="sidebar fixed bg-[#151529] inset-y-0 z-20 flex-shrink-0 w-64 mt-10 overflow-y-auto md:hidden" style="transition: all 0.5s ease 0s; left: -260px">
                <the-sidebar></the-sidebar>
            </aside>
            <!-- Container -->
            <div class="flex flex-col flex-1 w-full">
                <the-header v-once></the-header>
                <main class="h-full overflow-y-auto main_boby">
                    <div class="w-full px-6 mx-auto grid">
                        <!-- Main content here -->
                        <router-view></router-view>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
<style>
    .sidebar span, .sidebar a{
        color: #a3a6b7 !important;
        @apply duration-200 ease-linear px-2
    }
    .sidebar li:hover a,.sidebar li:hover span{
        color: #fff !important;
        @apply duration-200 ease-linear px-2
    }
    .main_boby{
        background-color: #f2f2f7;
    }
</style>

<style lang="postcss">
@tailwind components;

@layer components {
  .tab {
    @apply p-1.5 text-xs;
  }
  thead tr {
    @apply font-semibold text-gray-600;
  }
  th {
    @apply tab text-center;
  }
  tbody {
    @apply bg-white;
  }
  tbody tr td {
    @apply tab text-center;
  }
  tfoot td {
    @apply tab text-center;
  }
}

/* sidebar css start */
.active{
  background: #5c5c9596;
  color: #fff;
}
.active button span {
  color: #fff !important;
}
body {
  font-family: 'Inter', sans-serif;
}
.collapsing {
  height: 0;
  overflow: hidden;
  -webkit-transition: height .35s ease;
  transition: height .35s ease
}
.sidebar-auto-height {
  height: auto;
  overflow: visible;
}
.dropdown {
  transition: height 1s;
}
.collapse {
  height: 0;
  overflow: hidden;
  transition: height .6s ease-in-out;
}
/* sidebar css end */

.input-group {
  @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
}
.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
table, th,td{
  @apply border border-collapse;
}
.search-result {
  @apply px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-300;
}
.search {
  @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
}
>>> {
  --vs-controls-color: #374151;
  --vs-border-color: #4b5563;

  --vs-dropdown-bg: #282c34;
  --vs-dropdown-color: #eeeeee;
  --vs-dropdown-option-color: #eeeeee;

  --vs-selected-bg: #664cc3;
  --vs-selected-color: #374151;

  --vs-search-input-color: #4b5563;
  --vs-dropdown-option--active-bg: #664cc3;
  --vs-dropdown-option--active-color: #eeeeee;
}

    .sidebar::-webkit-scrollbar {
        width: 8px;
    }

    /* Track */
    /* aside::-webkit-scrollbar-track {
        background: #151529;
    } */

    /* Handle */
    .sidebar::-webkit-scrollbar-thumb {
        background: #c8c8c85e;
        border-radius: 10px;
    }

    /* Handle on hover */
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: #f8f8f866;
    }

    .sidebar {
        scrollbar-gutter: stable;
    }
</style>