<template>
  
    <div class="py-2 text-gray-500 dark:text-gray-400">
        <ul class="mt-6">
          <li class="relative px-6 py-3">
            <a class="inline-flex cursor-pointer items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
              <div v-html="icons.HomeSolid"></div>
              <span class="ml-4">Dashboard</span>
            </a>
          </li>
          <template v-for="element in sidebarElements.routes">
            <a class="flex cursor-pointer p-2 justify-between items-center mb-1 text-sm font-semibold text-purple-100 bg-purple-600 shadow-md focus:outline-none focus:shadow-outline-purple">
              <div class="flex ml-2">
                <span class="">{{ element.label }}</span>
              </div>
              <div v-html="element.icon"></div>
            </a>
            <template v-for="priceSubMenu in element.subMenu">
              <li class="relative px-6 py-3">
                <button class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="ml-2">{{ priceSubMenu.label }}</span>
                    </span>
                  <svg v-if="priceSubMenu.subSubMenu.length" class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
                <ul v-if="priceSubMenu.subSubMenu.length" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner sub-menu bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                  <li v-for="priceSubSubMenu in priceSubMenu.subSubMenu" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'New Fixed Contract' }">
                    <router-link :to="{ name: 'commercial.contracts.create' }" @click="addActiveClass('New Fixed Contract')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">{{ priceSubSubMenu.label }}</span>
                    </router-link>
                  </li>
                </ul>
              </li>
            </template>
          </template>
<!--          <a @click="toggleModuleMenu(1)" v-if="checkSidebarParentMenuPermission(['user-show','role-show','permission-show','approval-management-show'])" class="flex p-2 mb-1 cursor-pointer justify-between text-sm items-center font-semibold shadow-md focus:outline-none ">-->
<!--            <div class="flex ml-2">-->
<!--              <span class="">Control Users</span>-->
<!--            </div>-->
<!--            <svg class="w-6 h-6 ml-2 cursor-pointer" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" data-v-46e9fe5b=""><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" data-v-46e9fe5b=""></path></svg>-->
<!--          </a>-->
<!--          <template v-if="isControlUserMenuOpen">-->
<!--            <li v-if="checkSidebarMenuPermission('user-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'User' }">-->
<!--              -->
<!--              <router-link :to="{ name: 'authorization.user.index' }" @click="addActiveClass('User')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">-->
<!--                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">-->
<!--                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />-->
<!--                </svg>-->
<!--                <span class="ml-2">User</span>-->
<!--              </router-link>-->
<!--            </li>-->
<!--            <li v-if="checkSidebarMenuPermission('role-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Role' }">-->
<!--              -->
<!--              <router-link :to="{ name: 'authorization.user.role.index' }" @click="addActiveClass('Role')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">-->
<!--                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">-->
<!--                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />-->
<!--                </svg>-->
<!--                <span class="ml-2">Role</span>-->
<!--              </router-link>-->
<!--            </li>-->
<!--            <li v-if="checkSidebarMenuPermission('permission-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Permission' }">-->
<!--              <router-link :to="{ name: 'authorization.user.permission.index' }" @click="addActiveClass('Permission')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">-->
<!--                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">-->
<!--                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />-->
<!--                </svg>-->
<!--                <span class="ml-2">Permission</span>-->
<!--              </router-link>-->
<!--            </li>-->
<!--          </template>-->
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
          route: '',
          label: 'User',
          icon: icons.User,
          is_active: false,
          is_open: false,
          permissionKey: '',
          subSubMenu: [],
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

</script>

<style lang="postcss" scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700&display=swap');
.active{
  color: blueviolet;
}

body {
  font-family: 'Inter', sans-serif; 
}
</style>