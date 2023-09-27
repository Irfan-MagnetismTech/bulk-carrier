<template>
  <header class="z-10 py-1 bg-white shadow-md dark:bg-gray-800">
    <div class="flex items-center justify-between h-full px-6 mx-auto text-purple-600 dark:text-purple-300">
      <svg @click="toggleSidebar" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sidebar-open-close-toggle inline-flex float-right mr-2 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
      <button v-show="!isSidebarOpen" class="p-1 mr-5 -ml-1 rounded-md md:hidden outline-none" @click="openSidebar()" aria-label="Menu">
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
        </svg>
      </button>

      <!-- Search input -->
      <div class="HeaderLogoWrapper">
        <img aria-hidden="true" class="object-cover align-middle dark:block" style="width: 100px" src="/torony-small-logo.png" alt="Torony Logo">
      </div>

      <div class="flex items-center mr-2">
        <span>{{ username }} </span>
      </div>
      <ul class="flex items-center flex-shrink-0 space-x-6">
        <!-- App version -->
        <!--                <p v-once class="text-sm font-semibold text-purple-600 dark:text-gray-200">v0.0.5</p>-->
        <!-- Theme toggler -->
        <!--                <li class="flex">-->
        <!--                    <button @click="toggleDark()" class="rounded-md focus:outline-none focus:shadow-outline-purple" aria-label="Toggle color mode">-->
        <!--                        <template v-if="!isDark">-->
        <!--                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">-->
        <!--                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />-->
        <!--                            </svg>-->
        <!--                        </template>-->
        <!--                        <template v-if="isDark">-->
        <!--                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">-->
        <!--                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />-->
        <!--                            </svg>-->
        <!--                        </template>-->
        <!--                    </button>-->
        <!--                </li>-->
        <!-- Profile menu -->
        <li class="relative" @click="isProfileMenuOpen = !isProfileMenuOpen" ref="profileMenu">
          <button class="align-middle rounded-full focus:shadow-outline-purple focus:outline-none" aria-label="Account" aria-haspopup="true">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
          </button>
          <template v-if="isProfileMenuOpen">
            <ul class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 dark:bg-gray-700" aria-label="submenu">
<!--              <li class="flex">-->
<!--                <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">-->
<!--                  <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">-->
<!--                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />-->
<!--                  </svg>-->
<!--                  <span>{{ username }}</span>-->
<!--                </a>-->
<!--              </li>-->
              <li class="flex mt-2">
                <router-link :to="{ name: 'administration.user.password.update' }" style="cursor: pointer" class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
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
  </header>
</template>
<script setup>
import { useDark, useToggle } from '@vueuse/core';
import useAuth from '../services/auth';
import useSidebarProfileMenu from '../services/sidebarProfileMenu';
import {ref} from "vue";

const isDark = useDark();
const toggleDark = useToggle(isDark);
const { profileMenu, isProfileMenuOpen, isSidebarOpen } = useSidebarProfileMenu();
const { username, userRole, userPort, logout } = useAuth();

const isLeftSidebarOpen = ref(true);

function toggleSidebar(){
  isLeftSidebarOpen.value = !isLeftSidebarOpen.value;

  if(isLeftSidebarOpen.value) {
    document.getElementsByTagName('aside')[0].style.left = "0px";
    document.getElementsByTagName('aside')[0].style.width = "260px";
  }
  else{
    document.getElementsByTagName('aside')[0].style.left = "-260px";
    document.getElementsByTagName('aside')[0].style.width = "0px";
  }
}

function openSidebar() {
  isLeftSidebarOpen.value = !isLeftSidebarOpen.value;

  if(!isLeftSidebarOpen.value) {
    document.getElementsByTagName('aside')[1].style.left = "0px";
  }
  else{
    document.getElementsByTagName('aside')[1].style.left = "-260px";
  }
}
</script>

<style>
/* media query */
@media (max-width: 768px) {
  .sidebar-open-close-toggle {
    display: none;
  }
}

/* scroll bar width thin */
::-webkit-scrollbar {
  width: 0.5rem;
}
</style>
