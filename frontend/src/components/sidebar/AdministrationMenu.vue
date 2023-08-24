<script setup>
import useHeroIcon from "../../assets/heroIcon";
import {ref} from "vue";
const icons = useHeroIcon();
const sidebarElements = {
  role: "",
  routes: [
    {
      route: '',
      label: 'Control User',
      icon: icons.DownArrow,
      is_active: false,
      is_open: false,
      permissionKey: '',
      subMenu: [
        {
          route: 'administration.users.index',
          label: 'User',
          icon: icons.User,
          is_active: false,
          is_open: false,
          permissionKey: '',
          subSubMenu: [
            {
              route: '',
              label: 'New User',
              icon: icons.User,
              is_active: false,
              is_open: false,
              permissionKey: '',
              subSubMenu: [],
            },
            {
              route: '',
              label: 'List User',
              icon: icons.User,
              is_active: false,
              is_open: false,
              permissionKey: '',
              subSubMenu: [],
            },
          ],
        },
        {
          route: '',
          label: 'Role',
          icon: icons.User,
          is_active: false,
          is_open: false,
          permissionKey: '',
          subSubMenu: [],
        },
        {
          route: '',
          label: 'Permission',
          icon: icons.User,
          is_active: false,
          is_open: false,
          permissionKey: '',
          subSubMenu: [],
        },
      ]
    },
  ]
};

const childsVisible = ref(false);

function revealChilds() {
  childsVisible.value = !childsVisible.value
}

</script>

<template>
  <template v-for="element in sidebarElements.routes">
    <a @click="revealChilds" class="mx-2 flex cursor-pointer p-2 justify-between items-center mb-1 text-sm font-semibold text-purple-100 font-light shadow-md focus:outline-none">
      <div v-html="icons.User"></div>
      <span class="">{{ element.label }}</span>
      <div v-html="element.icon" class="duration-200 ease-linear" :class="{ 'rotate-180': childsVisible }"></div>
    </a>
    <div class="collapse" :style="{ height: childsVisible ? (54 * element.subMenu.length)+'px' : '0' }">
      <template v-for="priceSubMenu in element.subMenu">
        <li class="relative px-6 py-3 ml-2 flex rounded-md"  style="width: calc(100% - 14px);">
          <button class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" aria-haspopup="true">
                      <span class="inline-flex items-center">
                        <router-link :to="{ name: priceSubMenu.route }" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
<!--                          <div v-html="priceSubMenu.icon"></div>-->
                          <span class="ml-2 font-light">{{ priceSubMenu.label }}</span>
                        </router-link>
                      </span>
            <svg v-if="priceSubMenu.subSubMenu.length" class="revealGrandChilds w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
          <ul v-if="priceSubMenu.subSubMenu.length" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner sub-menu bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
            <li v-for="priceSubSubMenu in priceSubMenu.subSubMenu" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'New Fixed Contract' }">
              <router-link :to="{ name: priceSubSubMenu.route }" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span class="ml-2">{{ priceSubSubMenu.label }}</span>
              </router-link>
            </li>
          </ul>
        </li>
      </template>
    </div>
  </template>
</template>

<style scoped>

</style>