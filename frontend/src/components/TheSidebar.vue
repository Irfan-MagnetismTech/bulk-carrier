<template>
    <div class="ml-2 py-2 text-gray-500 dark:text-gray-400">
        <ul class="mt-6">
          <a class="mx-2 flex cursor-pointer p-2 items-center mb-1 text-sm font-semibold text-purple-100 font-light shadow-md focus:outline-none rounded-md hover:bg-indigo-900 duration-200 ease-linear px-2">
            <div v-html="icons.HomeSolid" class="w-6"></div>
            <span class="">Dashboard</span>
            <span class="w-6"></span>
          </a>
          <administration-menu></administration-menu>
          <crew-menu></crew-menu>
          <operation-menu></operation-menu>
          <supply-chain-menu></supply-chain-menu>
          <maintenance-menu></maintenance-menu>
          <accounts-menu></accounts-menu>
        </ul>
    </div>
</template>
<script setup>
import {computed, ref} from 'vue';
import { useRouter } from 'vue-router';
import useHeroIcon from '../assets/heroIcon';
import AdministrationMenu from "./sidebar/AdministrationMenu.vue";
import OperationMenu from "./sidebar/OperationMenu.vue";
import SupplyChainMenu from "./sidebar/SupplyChainMenu.vue";
import MaintenanceMenu from "./sidebar/MaintenanceMenu.vue";
import AccountsMenu from "./sidebar/AccountMenu.vue";
import CrewMenu from "./sidebar/CrewMenu.vue";
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