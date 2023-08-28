<script setup>
import useHeroIcon from "../../assets/heroIcon";
import {ref} from "vue";
const icons = useHeroIcon();
const sidebarElements = {
  role: "",
  routes: [
    {
      route: '',
      label: 'Maintenance',
      icon: icons.DownArrow,
      is_active: false,
      is_open: false,
      permissionKey: '',
      subMenu: [
        {
          route: '',
          label: 'Teams',
          icon: icons.User,
          is_active: false,
          is_open: false,
          permissionKey: '',
          subSubMenu: [
          {
              route: '',
              label: 'New Team',
              icon: icons.User,
              is_active: false,
              is_open: false,
              permissionKey: '',
              subSubMenu: [],
            },
            {
              route: '',
              label: 'List Team',
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
          label: 'Jobs',
          icon: icons.User,
          is_active: false,
          is_open: false,
          permissionKey: '',
          subSubMenu: [],
        }
      ]
    },
  ]
};

const childsVisible = ref(false);
const grandChildsVisible = ref(false)
const thisHeight = ref(0);

function revealChilds(items) {
  childsVisible.value = !childsVisible.value;
  thisHeight.value = items * 54;
}

function revealGrandChilds(items) {
  grandChildsVisible.value = !grandChildsVisible.value
  thisHeight.value = items * 54;
}


</script>

<template>
  <template v-for="element in sidebarElements.routes">
    <a @click="revealChilds(element.subMenu.length)" class="mx-2 flex cursor-pointer p-2 items-center mb-1 text-sm font-semibold text-purple-100 font-light shadow-md focus:outline-none rounded-md hover:bg-indigo-900 duration-200 ease-linear px-2">
      <div v-html="icons.User"></div>
      <span class="">{{ element.label }}</span>
      <div v-html="element.icon" class="duration-200 ease-linear absolute right-3 " :class="{ 'rotate-180': childsVisible }"></div>
    </a>
    <div class="collapse" :style="{ height: childsVisible ? thisHeight+'px' : '0' }">
    <!-- <div class="collapse" :style="{ height: childsVisible ? (54 * element.subMenu.length)+'px' : '0' }"> -->
      <template v-for="mntSubMenu in element.subMenu">
        <li class="relative ml-2 rounded-md"  style="width: calc(100% - 14px);">
          <div class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors rounded-md group group-hover:text-white duration-200 ease-linear p-2" aria-haspopup="true">
                      <span class="inline-flex items-center">
                        <router-link :to="{ name: mntSubMenu.route }" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-white">
                          <span class="ml-2 font-light ">{{ mntSubMenu.label }}</span>
                        </router-link>
                      </span>
         
            <div v-if="mntSubMenu.subSubMenu.length" @click="revealGrandChilds(element.subMenu.length+mntSubMenu.subSubMenu.length)" v-html="element.icon" class="duration-200 ease-linear" :class="{ 'rotate-180': grandChildsVisible }"></div>

          </div>
          <ul v-if="mntSubMenu.subSubMenu.length" :class="{ '': grandChildsVisible }" :style="{ height: grandChildsVisible ? (42 * mntSubMenu.subSubMenu.length)+'px' : '0' }" class="collapse overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-[#363657] dark:text-gray-400 dark:bg-gray-900 grand-child" aria-label="submenu">
            <li v-for="mntSubSubMenu in mntSubMenu.subSubMenu" class="p-2 transition-colors duration-150  dark:hover:text-gray-200 rounded-md hover:bg-indigo-900 duration-200 ease-linear p-2" :class="{ 'active': isActive === 'New Fixed Contract' }">
              <router-link :to="{ name: mntSubSubMenu.route }" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span class="ml-2">{{ mntSubSubMenu.label }}</span>
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