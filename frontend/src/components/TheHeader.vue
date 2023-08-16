<script setup>
import { useDark, useToggle } from '@vueuse/core';
import useAuth from '../services/auth';
import useSidebarProfileMenu from '../services/sidebarProfileMenu';
import {ref, computed, onMounted} from "vue";
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();

const router = useRouter();
const currentRoute = ref(computed(() => { return router.currentRoute.value.path.split('/') }));

const isDark = useDark();
const toggleDark = useToggle(isDark);
const { profileMenu, isProfileMenuOpen, isSidebarOpen, openSidebar } = useSidebarProfileMenu();
const { username, shift, userRole, userPort, logout } = useAuth();
let activeMenu = ref('');

const isLeftSidebarOpen = ref(true);

function toggleSidebar(){
  isLeftSidebarOpen.value = !isLeftSidebarOpen.value;

  if(isLeftSidebarOpen.value) {
    document.getElementsByTagName('aside')[0].style.display = "block";
  }
  else{
    document.getElementsByTagName('aside')[0].style.display = "none";
  }
}

function setActiveMenu(menuName){
  store.commit('setActiveMenu', menuName);
  activeMenu.value = store.state.activeMenu;
}

</script>

<template>
  <header class="z-10 py-1 bg-white shadow-md dark:bg-gray-800">

    <!-- Main menu -->

    <div class="flex items-center px-6 mt-1">
      <div class="w-full">
        <nav class="flex items-center space-between">
          <div class="hidden w-full md:block md:w-auto">
            <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 md:flex-row md:space-x-8 md:mt-0 md:border-0">
              <li class="main_menu">
                <router-link :to="{ name: 'dashboard' }" @click="setActiveMenu('dashboard')" class="flex block py-2 pl-3 pr-4 text-white rounded md:bg-transparent md:p-0 dark:text-white main_menu_link"
                :class="{ 'active_main_menu': activeMenu.name === 'dashboard' }"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-2.25z" />
                  </svg>
                  Dashboard
                </router-link>
              </li>
              <li class="main_menu">
                <a href="#" @click="setActiveMenu('control-user')" class="flex block py-2 pl-3 pr-4 text-white rounded md:bg-transparent md:p-0 dark:text-white main_menu_link"
                   :class="{ 'active_main_menu': activeMenu.name === 'control-user' }"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                  </svg>
                  Control Users
                </a>
              </li>
              <li class="main_menu">
                <a href="#" @click="setActiveMenu('configuration')" class="flex block py-2 pl-3 pr-4 text-white rounded md:bg-transparent md:p-0 dark:text-white main_menu_link" aria-current="page"
                   :class="{ 'active_main_menu': activeMenu.name === 'configuration' }"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  Configuration
                </a>
              </li>
              <li class="main_menu">
                <a href="#" @click="setActiveMenu('revenue')" class="flex block py-2 pl-3 pr-4 text-white rounded md:bg-transparent md:p-0 dark:text-white main_menu_link"
                   :class="{ 'active_main_menu': activeMenu.name === 'revenue' }"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Revenue
                </a>
              </li>
              <li class="main_menu">
                <a href="#" @click="setActiveMenu('accounts')"
                   class="flex block py-2 pl-3 pr-4 text-white rounded md:bg-transparent md:p-0 dark:text-white main_menu_link"
                   :class="{ 'active_main_menu': activeMenu.name === 'accounts' }"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25z" />
                  </svg>
                  Accounts
                </a>
              </li>
              <li class="main_menu">
                <a href="#" @click="setActiveMenu('payroll')"
                   class="flex block py-2 pl-3 pr-4 text-white rounded md:bg-transparent md:p-0 dark:text-white main_menu_link"
                   :class="{ 'active_main_menu': activeMenu.name === 'payroll' }"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                  </svg>
                  Payroll
                </a>
              </li>
              <li class="main_menu">
                <a href="#" @click="setActiveMenu('supply-chain')" class="flex block py-2 pl-3 pr-4 text-white rounded md:bg-transparent md:p-0 dark:text-white main_menu_link"
                   :class="{ 'active_main_menu': activeMenu.name === 'supply-chain' }"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                  </svg>
                  Supply Chain
                </a>
              </li>
            </ul>
          </div>
        </nav>
      </div>

      <div class="flex items-center text-purple-600 dark:text-purple-300">
        <div class="flex items-center ml-auto">
          <span class="block py-2 pl-3 pr-4 text-xs text-white rounded md:bg-transparent md:p-0 dark:text-white"><nobr>{{ username }} <strong>({{ shift }} SHIFT)</strong></nobr></span>
        </div>
        <ul class="flex items-center flex-shrink-0 space-x-6">
          <!-- Profile menu -->
          <li class="relative" @click="isProfileMenuOpen = !isProfileMenuOpen" ref="profileMenu">
            <button class="align-middle rounded-full" aria-label="Account" aria-haspopup="true">
              <svg class="w-5 h-5 block py-2 pl-3 pr-4 text-white rounded md:bg-transparent md:p-0 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </button>
            <template v-if="isProfileMenuOpen">
              <ul class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 dark:bg-gray-700" aria-label="submenu">
                <li class="flex mt-2">
                  <router-link :to="{ name: 'authorization.user.password.update' }" style="cursor: pointer" class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                    </svg>
                    <span>Update Password</span>
                  </router-link>
                </li>
                <li class="flex">
                  <a href="#" @click.prevent="logout()" class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                    <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                      <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>Log out</span>
                  </a>
                </li>
              </ul>
            </template>
          </li>
        </ul>
      </div>
    </div>

  </header>
</template>

<style>

body {
  font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
}

header {
  /*background-color: #26374C !important;*/
  background-color: #0F6B61 !important;
}

/* media query */
@media (max-width: 768px) {
  .sidebar-open-close-toggle {
    display: none;
  }
}

.sub_menu{
  min-width: 10.2rem;
}

/* scroll bar width thin */
::-webkit-scrollbar {
  width: 0.5rem;
}

.main_menu:hover .sub_menu {
  display: block;
}

.main_sub_menu:hover .sub_sub_menu {
  display: block;
}

.sub_menu, .sub_sub_menu {
  display: none;
  padding-top: 6px;
}

/* Arrow */

/*.sub_menu:after {*/
/*  content: "";*/
/*  position: absolute;*/
/*  top: 0;*/
/*  left: 50%;*/
/*  transform: translateX(-50%) rotate(45deg);*/
/*  width: 14px;*/
/*  height: 14px;*/
/*  background-color: #E5E7EB;*/
/*  border-bottom: none;*/
/*  border-right: none;*/
/*}*/

/*.sub_menu:before {*/
/*  content: "";*/
/*  position: absolute;*/
/*  top: 0;*/
/*  left: 50%;*/
/*  transform: translateX(-50%) rotate(-45deg);*/
/*  width: 14px;*/
/*  height: 14px;*/
/*  background-color: #E5E7EB;*/
/*  border-bottom: none;*/
/*  border-left: none;*/
/*}*/

</style>
