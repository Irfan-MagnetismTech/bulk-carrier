<template>
    <div class="py-2 text-gray-500 dark:text-gray-400">
        <ul class="mt-6">
          <li class="relative px-6 py-3 ml-2 flex rounded-md"  style="width: calc(100% - 14px);">
            <a class="inline-flex cursor-pointer items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
              <div v-html="icons.HomeSolid"></div>
              <span class="ml-4">Dashboard</span>
            </a>
          </li>
          <administration-menu></administration-menu>
        </ul>
    </div>
</template>
<script setup>
import {computed, ref} from 'vue';
import { useRouter } from 'vue-router';
import useHeroIcon from '../assets/heroIcon';
import AdministrationMenu from "./sidebar/AdministrationMenu.vue";
const router = useRouter();
const currentRoute = ref(computed(() => { return router.currentRoute.value; }));
const icons = useHeroIcon();
const isActive = ref('');
const childsVisible = ref(false);

function revealChilds() {
  childsVisible.value = !childsVisible.value
}

function addActiveClass(label){
  isActive.value = label;
}

</script>

<style lang="postcss" scoped>

</style>