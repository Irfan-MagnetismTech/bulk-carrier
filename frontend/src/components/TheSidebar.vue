<script setup>
import {computed, ref} from 'vue';
import { useRouter } from 'vue-router';
import useHeroIcon from '../assets/heroIcon';
const router = useRouter();
const currentRoute = ref(computed(() => { return router.currentRoute.value; }));
const icons = useHeroIcon();
const isActive = ref('');

import mainSidebar from '../services/sidebar/mainSidebar.js';

const childVisible = ref(false);
const grandChildsVisible = ref(false)
const thisHeight = ref(0);

function revealChild(items,elementIndex) {
  mainSidebar.value.forEach((element, index) => {
    //only element is_show is true when elementIndex is equal to index else false
    if(elementIndex === index){
      element.is_open= !element.is_open;
      element.is_active = !element.is_active;
    }else{
      element.is_open= false;
      element.is_active = false;
    }
  });
  //childVisible.value = !childVisible.value;
  thisHeight.value = items * 54;
}

function revealGrandChild(items,elementIndex,elementSubIndex) {
  mainSidebar.value[elementIndex].subMenu.forEach((element, index) => {
    //only element is_show is true when elementIndex is equal to index else false
    if(elementSubIndex === index){
      element.is_open= !element.is_open;
      element.is_active = !element.is_active;
    }else{
      element.is_open= false;
      element.is_active = false;
    }
  });
  //grandChildsVisible.value = !grandChildsVisible.value
  thisHeight.value = items * 54;
}

function toggleActiveClass(elementIndex,elementSubIndex,elementGrandSubIndex) {
  mainSidebar.value[elementIndex].subMenu[elementSubIndex].subSubMenu.forEach((element, index) => {
    //only element is_show is true when elementIndex is equal to index else false
    if(elementGrandSubIndex === index){
      element.is_active = !element.is_active;
    }else{
      element.is_active = false;
    }
  });
}

</script>
<template>
    <div class="ml-2 py-2 text-gray-500 dark:text-gray-400">
        <ul class="mt-6">
          <a class="mx-2 flex cursor-pointer p-2 items-center mb-1 text-sm font-semibold text-purple-100 font-light shadow-md focus:outline-none rounded-md hover:bg-indigo-900 duration-200 ease-linear px-2">
            <div v-html="icons.HomeSolid" class="w-6"></div>
            <span class="">Dashboard</span>
            <span class="w-6"></span>
          </a>
          <template v-for="(element,elementIndex) in mainSidebar">
            <a @click="revealChild(element.subMenu.length,elementIndex)" class="mx-2 flex cursor-pointer p-2 items-center mb-1 text-sm font-semibold text-purple-100 font-light shadow-md focus:outline-none rounded-md hover:bg-indigo-900 duration-200 ease-linear px-2">
              <div v-html="element.preIcon"></div>
              <span :class="{'active_menu': element.is_active}">{{ element.label }}</span>
              <div v-html="element.postIcon" class="duration-200 ease-linear absolute right-3 " :class="{ 'rotate-180': element.is_open }"></div>
            </a>
              <div v-show="element.is_open" class="collapse" :class="{ 'slide_down': element.is_open }">
                <!-- <div class="collapse" :style="{ height: childsVisible ? (54 * element.subMenu.length)+'px' : '0' }"> -->
                <template v-for="(elementSubMenu,elementSubIndex) in element.subMenu">
                  <li class="relative ml-2 rounded-md"  style="width: calc(100% - 12.5px);">
                    <a @click="revealGrandChild(element.subMenu.length+elementSubMenu.subSubMenu.length,elementIndex,elementSubIndex)" class="flex cursor-pointer items-center justify-between w-full text-sm font-semibold transition-colors rounded-md group group-hover:text-white duration-200 ease-linear p-2" aria-haspopup="true">
                      <span class="inline-flex items-center">
                        <router-link :to="{ name: elementSubMenu.route }" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-white">
                          -<span class="ml-1 font-light" :class="{'active_menu': elementSubMenu.is_active}">{{ elementSubMenu.label }}</span>
                        </router-link>
                      </span>
                      <div v-if="elementSubMenu.subSubMenu.length" v-html="element.postIcon" class="duration-200 ease-linear" :class="{ 'rotate-180': elementSubMenu.is_open }"></div>
                    </a>
                    <ul v-if="elementSubMenu.subSubMenu.length && elementSubMenu.is_open" :class="{ '': grandChildsVisible }" :style="{ height: elementSubMenu.is_open ? (42 * elementSubMenu.subSubMenu.length)+'px' : '0' }" class="collapse overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner dark:text-gray-400 dark:bg-gray-900 grand-child" aria-label="submenu">
                      <li @click="toggleActiveClass(elementIndex,elementSubIndex,elementSubSubIndex)" v-for="(elementSubSubMenu,elementSubSubIndex) in elementSubMenu.subSubMenu" class="p-2 transition-colors duration-150  dark:hover:text-gray-200 rounded-md duration-200 ease-linear p-2" :class="{ 'active': isActive === 'New Fixed Contract' }">
                        <router-link :to="{ name: elementSubSubMenu.route }" class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                          -<span class="ml-1" :class="{'active_menu': elementSubSubMenu.is_active}">{{ elementSubSubMenu.label }}</span>
                        </router-link>
                      </li>
                    </ul>
                  </li>
                </template>
              </div>
          </template>
        </ul>
    </div>
</template>

<style lang="postcss" scoped>
.active_menu{
  font-weight: bold;
  color: #fff !important;
}
/*if element.is_open true then slide down class which down slowly and up slowly */
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
.slide_down{
  transition: height 0.5s ease-in-out;
  height: 100%;
}
</style>