<template>
    <div class="py-2 text-gray-500 dark:text-gray-400">
<!--        <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="#">-->
<!--          <img aria-hidden="true" class="object-cover w-full align-middle dark:block w-32" src="/hr-line-test.png" alt="Office">-->
<!--        </a>-->
        <ul class="mt-6">
            <span>
                <li class="relative px-6 py-3" v-for="sidebarElement in sidebarElements.routes" @click="togglePagesMenu(0)" :class="{ 'active': isActive === sidebarElement.label }" :key="sidebarElement.route">
                    <span v-if="sidebarElement.route === currentRoute.name" class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: sidebarElement.route }" @click="addActiveClass(sidebarElement.label)" :class="{ ' dark:text-gray-100': sidebarElement.route === currentRoute.name }" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                        <div v-html="sidebarElement.icon"></div>
                        <span class="ml-4">{{ sidebarElement.label }}</span>
                    </router-link>
                </li>
            </span>

          <template v-for="priceM in priceMenu.routes">
            <a class="flex cursor-pointer p-2 justify-between items-center mb-1 text-sm font-semibold text-purple-100 bg-purple-600 shadow-md focus:outline-none focus:shadow-outline-purple">
              <div class="flex ml-2">
                <span class="">{{ priceM.label }}</span>
              </div>
              <div v-html="priceM.icon"></div>
            </a>
            <template v-for="priceSubMenu in priceM.subMenu">
              <li class="relative px-6 py-3">
                <button class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="ml-2">{{ priceSubMenu.label }}</span>
                    </span>
                  <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
                <ul v-if="priceSubMenu.subSubMenu.length" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner sub-menu bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                  <li v-for="priceSubSubMenu in priceSubMenu.subSubMenu" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'New Fixed Contract' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.contracts.create' }" @click="addActiveClass('New Fixed Contract')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">{{ priceSubSubMenu.label }}</span>
                    </router-link>
                  </li>
                </ul>
              </li>
            </template>
          </template>



          <a @click="toggleModuleMenu(7)" v-if="checkSidebarParentMenuPermission(['documentation-bl-generate','commercial-freight-manifest-report'])" class="flex cursor-pointer p-2 justify-between items-center mb-1 text-sm font-semibold text-purple-100 bg-purple-600 shadow-md focus:outline-none focus:shadow-outline-purple">
            <div class="flex ml-2">
              <span class="">Pricing & Invoicing</span>
            </div>
            <svg class="w-6 h-6 cursor-pointer" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" data-v-46e9fe5b=""><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" data-v-46e9fe5b=""></path></svg>
          </a>

          <template v-if="isPricingInvoicingMenuOpen">
            <li v-if="checkSidebarParentMenuPermission(['commercial-contract-create','commercial-open-slot-contract-show','commercial-force-load-contract-show','commercial-contract-show'])" class="relative px-6 py-3">
              <button @click="togglePagesMenu(5)" class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="ml-2">Contract</span>
                    </span>
                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <template v-if="isPriceInvoiceContractMenuOpen">
                <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner sub-menu bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                  <li v-if="checkSidebarMenuPermission('commercial-contract-create')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'New Fixed Contract' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.contracts.create' }" @click="addActiveClass('New Fixed Contract')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">New Fixed Contract</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('commercial-open-slot-contract-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'New Open Contract' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.open-slot-contract.create' }" @click="addActiveClass('New Open Contract')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">New Open Contract</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('commercial-force-load-contract-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Force Load Contract' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.force-load-contract.create' }" @click="addActiveClass('Force Load Contract')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Force Load Contract</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('commercial-contract-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Contract List' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.contracts.index' }" @click="addActiveClass('Contract List')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Contract List</span>
                    </router-link>
                  </li>
                </ul>
              </template>
            </li>

            <li v-if="checkSidebarMenuPermission('commercial-contract-assign-voyages')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Contract Assign' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'commercial.contract-assigns.voyages' }" @click="addActiveClass('Contract Assign')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span class="ml-2">Contract Assign</span>
              </router-link>
            </li>

            <li v-if="checkSidebarParentMenuPermission(['commercial-advanced-invoice-create','commercial-pending-invoice-create','commercial-invoice-list'])" class="relative px-6 py-3">
              <button @click="togglePagesMenu(6)" class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                        </svg>
                        <span class="ml-2">Invoicing</span>
                    </span>
                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <template v-if="isPriceInvoiceListMenuOpen">
                <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner sub-menu bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                  <li v-if="checkSidebarMenuPermission('commercial-advanced-invoice-create')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Debit Note (Adv.)' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.advance.invoice.create' }" @click="addActiveClass('Debit Note (Adv.)')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Debit Note (Adv.)</span>
                    </router-link>
                  </li>

                  <li v-if="checkSidebarMenuPermission('commercial-invoice-create')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Freight Invoice' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.pending.invoice' }" @click="addActiveClass('Freight Invoice')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Freight Invoice</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('commercial-invoice-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Invoice List' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.invoice.list' }" @click="addActiveClass('Invoice List')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Invoice List</span>
                    </router-link>
                  </li>
                </ul>
              </template>
            </li>
          </template>
        </ul>
    </div>
</template>
<script setup>
import {computed, onMounted, ref, watch} from 'vue';
import { useRouter } from 'vue-router';
import useHeroIcon from '../assets/heroIcon';
const router = useRouter();
const currentRoute = ref(computed(() => { return router.currentRoute.value; }));
const icons = useHeroIcon();
const isActive = ref('');

const permissionKey = ref('');

const priceMenu = {
  role: "",
  routes: [
    {
      route: '',
      label: 'Pricing & Invoicing',
      icon: icons.HomeSolid,
      is_active: false,
      is_open: false,
      permissionKey: '',
      subMenu: [
        {
          route: '',
          label: 'Contract',
          icon: icons.HomeSolid,
          is_active: false,
          is_open: false,
          permissionKey: '',
          subSubMenu: [
            {
              route: '',
              label: 'New Fixed Contract',
              icon: icons.HomeSolid,
              is_active: false,
              is_open: false,
              permissionKey: '',
            },
            {
              route: '',
              label: 'New Open Contract',
              icon: icons.HomeSolid,
              is_active: false,
              is_open: false,
              permissionKey: '',
            },
            {
              route: '',
              label: 'New Force Contract',
              icon: icons.HomeSolid,
              is_active: false,
              is_open: false,
              permissionKey: '',
            },
          ]
        },
      ]
    },
  ]
};

function checkSidebarParentMenuPermission(permissionArray){
  let count = 0;
  let role = Store?.getters?.getCurrentUser?.role;
  if(role == 'super-admin'){
    return true;
  }
  permissionArray.map(function(value, key) {
    if(count == 0){
      if(checkSidebarMenuPermission(value)){
        count = count+1;
      } else{
        count = 0;
      }
    }
  });
  if(count == 0){
    return false;
  }else{
    return true;
  }
}

function checkSidebarMenuPermission(permissionKey){
  let role = Store?.getters?.getCurrentUser?.role;
  if(role == 'super-admin'){
    return true;
  }

  if(Store?.getters?.getCurrentUser?.permissions.indexOf(permissionKey) > -1){
    return true;
  }else{
    return false;
  }
}

const role = ref(computed(() => {
    return Store?.getters?.getCurrentUser?.role ?? null;
}));

const checkRole = (obj) => {
    return obj.role.includes(role.value) || obj.role === '';
}

const isCostExpenditureMenuOpen = ref(0); // Data encoding menu
const isSpecialContainerMenuOpen = ref(0); // vessel & cargo operation menu
const isCommercialScheduleMenuOpen = ref(0); // commercial menu
const isCommercialReportsMenuOpen = ref(0); // commercial menu
const isPriceInvoiceContractMenuOpen = ref(0); // pricing & invoicing menu
const isPriceInvoiceListMenuOpen = ref(0); // pricing & invoicing menu
const isFinanceCostBudgetMenuOpen = ref(0); // finance menu
const isFinanceMoneyReceiptMenuOpen = ref(0); // finance menu
const isFinanceReportsMenuOpen = ref(0); // finance menu


const isControlUserMenuOpen = ref(0); // control user menu
const isDataEncodingMenuOpen = ref(0); // Data encoding menu
const isContactMenuOpen = ref(0); // contact menu
const isVesselCargoMenuOpen = ref(0); // vessel & cargo operation menu
const isCommercialMenuOpen = ref(0); // commercial menu
const isDocumentationMenuOpen = ref(0); // documentation menu
const isPricingInvoicingMenuOpen = ref(0); // pricing & invoicing menu
const isFinanceMenuOpen = ref(0); // finance menu
const isSteveDorageMenuOpen = ref(0); // finance menu


function toggleModuleMenu(moduleKey){
  if(moduleKey === 1) {
    isControlUserMenuOpen.value = !isControlUserMenuOpen.value;
    isDataEncodingMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
    isSteveDorageMenuOpen.value = false;
  } else if(moduleKey === 2){
    isDataEncodingMenuOpen.value = !isDataEncodingMenuOpen.value;
    isControlUserMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
    isSteveDorageMenuOpen.value = false;
  } else if(moduleKey === 3){
    isContactMenuOpen.value = !isContactMenuOpen.value;
    isControlUserMenuOpen.value = false;
    isDataEncodingMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
    isSteveDorageMenuOpen.value = false;
  } else if(moduleKey === 4){
    isVesselCargoMenuOpen.value = !isVesselCargoMenuOpen.value;
    isControlUserMenuOpen.value = false;
    isDataEncodingMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
    isSteveDorageMenuOpen.value = false;
  } else if(moduleKey === 5){
    isCommercialMenuOpen.value = !isCommercialMenuOpen.value;
    isControlUserMenuOpen.value = false;
    isDataEncodingMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
    isSteveDorageMenuOpen.value = false;
  } else if(moduleKey === 6){
    isDocumentationMenuOpen.value = !isDocumentationMenuOpen.value;
    isControlUserMenuOpen.value = false;
    isDataEncodingMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
    isSteveDorageMenuOpen.value = false;
  } else if(moduleKey === 7){
    isPricingInvoicingMenuOpen.value = !isPricingInvoicingMenuOpen.value;
    isControlUserMenuOpen.value = false;
    isDataEncodingMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
    isSteveDorageMenuOpen.value = false;
  } else if(moduleKey === 8){
    isFinanceMenuOpen.value = !isFinanceMenuOpen.value;
    isControlUserMenuOpen.value = false;
    isDataEncodingMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
    isSteveDorageMenuOpen.value = false;
  } else if((moduleKey === 9)) {
    isSteveDorageMenuOpen.value = !isSteveDorageMenuOpen.value;
    isFinanceMenuOpen.value = false;
    isControlUserMenuOpen.value = false;
    isDataEncodingMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
  } else {
    isSteveDorageMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
    isControlUserMenuOpen.value = false;
    isDataEncodingMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
  }
}


function togglePagesMenu(sidebarElement) {
  if(sidebarElement === 1) {
    isCostExpenditureMenuOpen.value = !isCostExpenditureMenuOpen.value;
    isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 2){
    isSpecialContainerMenuOpen.value = !isSpecialContainerMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 3){
    isCommercialScheduleMenuOpen.value = !isCommercialScheduleMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 4) {
    isCommercialReportsMenuOpen.value = !isCommercialReportsMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 5) {
    isPriceInvoiceContractMenuOpen.value = !isPriceInvoiceContractMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 6) {
    isPriceInvoiceListMenuOpen.value = !isPriceInvoiceListMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 7) {
    isFinanceCostBudgetMenuOpen.value = !isFinanceCostBudgetMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 8) {
    isFinanceMoneyReceiptMenuOpen.value = !isFinanceMoneyReceiptMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 9) {
    isFinanceReportsMenuOpen.value = !isFinanceReportsMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;
  } else{
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  }
}

function addActiveClass(label){
  isActive.value = label;
}

const sidebarElements = {
    role: "",
    routes: [
        {
            route: 'dashboard',
            label: 'Dashboard',
            icon: icons.HomeSolid,
        },
    ]
};

</script>
<style lang="postcss" scoped>
.active{
  color: blueviolet;
}
</style>