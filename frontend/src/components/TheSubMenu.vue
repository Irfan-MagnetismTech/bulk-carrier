<script setup>
import {onMounted, ref} from 'vue';
import { useStore } from 'vuex';
const store = useStore();
import { useRouter } from 'vue-router';
import {computed} from "vue";

const activeMenu = ref('');
const activeRoute = ref('');
activeMenu.value = store.state.activeMenu;


function setActiveRoute(routeName){
  store.commit('setActiveRoute', routeName);
  activeRoute.value = store.state.activeMenu;
}

</script>

<template>
  <!--For Control Users Menu-->
  <div v-if="activeMenu.name === 'control-user'" class="flex items-center justify-start p-2 mt-2 text-sm text-purple-100 sm:rounded-lg" style="background-color: #369382">
    <ul class="flex items-center text-gray-700">
      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'authorization.user.index' }" @click="setActiveRoute('authorization.user.index')" class="flex items-center"
        :class="{ 'active_main_menu': activeRoute.routeName === 'authorization.user.index' }"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span>User</span>
        </router-link>
      </li>
      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'authorization.user.role.index' }" @click="setActiveRoute('authorization.user.role.index')" class="flex items-center"
        :class="{ 'active_main_menu': activeRoute.routeName === 'authorization.user.role.index' }"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span>Role</span>
        </router-link>
      </li>
      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'authorization.user.permission.index' }" @click="setActiveRoute('authorization.user.permission.index')" class="flex items-center"
        :class="{ 'active_main_menu': activeRoute.routeName === 'authorization.user.permission.index' }"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span>Permission</span>
        </router-link>
      </li>
    </ul>
  </div>

  <!--For Configuration Menu-->
  <div v-if="activeMenu.name === 'configuration'" class="flex items-center justify-start p-2 mt-2 text-sm text-purple-100 sm:rounded-lg" style="background-color: #369382">
    <ul class="flex items-center text-gray-700">
<!--      <li class="flex items-center mr-2">-->
<!--        <router-link :to="{ name: 'configuration.branch.index' }" class="flex items-center" @click="setActiveRoute('configuration.branch.index')"-->
<!--        :class="{ 'active_main_menu': activeRoute.routeName === 'configuration.branch.index' }"-->
<!--        >-->
<!--          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">-->
<!--            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />-->
<!--          </svg>-->
<!--          <span>Branch</span>-->
<!--        </router-link>-->
<!--      </li>-->
      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'configuration.bank.index' }" class="flex items-center" @click="setActiveRoute('configuration.bank.index')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'configuration.bank.index' }"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span>Bank</span>
        </router-link>
      </li>
      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'configuration.material-opening-stocks.index' }" class="flex items-center" @click="setActiveRoute('configuration.material-opening-stocks.index')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'configuration.material-opening-stocks.index' }"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span>Material Opening Stock</span>
        </router-link>
      </li>
    </ul>
  </div>

  <!--For Revenue Menu-->
  <div v-if="activeMenu.name === 'revenue'" class="flex items-center justify-start p-2 mt-2 text-sm text-purple-100 sm:rounded-lg" style="background-color: #369382">
    <ul class="flex items-center text-gray-700">
      <li class="main_sub_menu_two">
        <router-link :to="'#'" class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span class="mr-1"><nobr>Configuration</nobr></span>
        </router-link>
        <ul class="fixed sub_sub_menu_two z-50">
          <li>
            <router-link :to="{ name: 'revenue.service.index' }" class="py-2 px-4 block" @click="setActiveRoute('revenue.service.index')"
            :class="{ 'active_main_menu': activeRoute.routeName === 'revenue.service.index' }"
            >
              <span>Services</span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'revenue.purchase-rate.index' }" class="py-2 px-4 block" @click="setActiveRoute('revenue.purchase-rate.index')"
            :class="{ 'active_main_menu': activeRoute.routeName === 'revenue.purchase-rate.index' }"
            >
             
              <span>Vendor Tariff</span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'revenue.sale-rate.index' }" class="py-2 px-4 block" @click="setActiveRoute('revenue.sale-rate.index')"
            :class="{ 'active_main_menu': activeRoute.routeName === 'revenue.sale-rate.index' }"
            >
              
              <span>Sale Tariff</span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'revenue.service-rate.index' }" class="py-2 px-4 block" @click="setActiveRoute('revenue.service-rate.index')"
            :class="{ 'active_main_menu': activeRoute.routeName === 'revenue.service-rate.index' }"
            >
              
              <span>Service Tariff</span>
            </router-link>
          </li>
        </ul>
      </li>
      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'configuration.dispenser.index' }" class="flex items-center" @click="setActiveRoute('configuration.dispenser.index')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'configuration.dispenser.index' }"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span>Dispensers</span>
        </router-link>
      </li>
  
      
      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'revenue.cash-sale.create' }" class="flex items-center" @click="setActiveRoute('revenue.cash-sale.create')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'revenue.cash-sale.create' }"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span>Cash Sale</span>
        </router-link>
      </li>

      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'revenue.shift-performance.create' }" class="flex items-center" @click="setActiveRoute('revenue.shift-performance.create')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'revenue.shift-performance.create' }"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span>Shift Closing</span>
        </router-link>
      </li>

      <li class="main_sub_menu_two">
        <router-link :to="'#'" class="flex items-center" @click="setActiveRoute('revenue.credit-sale.create')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'revenue.credit-sale.create' }">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span class="mr-1"><nobr>Credit Sale</nobr></span>
        </router-link>
        <ul class="fixed sub_sub_menu_two  z-50">
          <li>
            <router-link :to="{ name: 'revenue.credit-sale.create' }" class="py-2 px-4 block">
              <span><nobr>New Entry</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'configuration.customer.index' }" @click="setActiveRoute('configuration.customer.index')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'configuration.customer.index' }" class="py-2 px-4 block">
              <span><nobr>Customer</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'configuration.customer-vehicle.index' }" @click="setActiveRoute('configuration.customer-vehicle.index')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'configuration.customer-vehicle.index' }" class="py-2 px-4 block">
              <span><nobr>Customer Vehicles</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'revenue.billing.index' }" @click="setActiveRoute('revenue.billing.index')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'revenue.billing.index' }" class="py-2 px-4 block">
              <span><nobr>Customer Bill</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'revenue.money-receipt.index' }" @click="setActiveRoute('revenue.money-receipt.index')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'revenue.money-receipt.index' }" class="py-2 px-4 block">
              <span><nobr>Money Receipt</nobr></span>
            </router-link>
          </li>
         
          
        </ul>
      </li>

      <li class="main_sub_menu_two">
        <router-link :to="'#'" class="flex items-center" @click="setActiveRoute('revenue.reports.sales-reports.index')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'revenue.reports.sales-reports.index' }">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span class="mr-1"><nobr>Report</nobr></span>
        </router-link>
        <ul class="fixed sub_sub_menu_two  z-50">
          <li>
            <router-link :to="{ name: 'revenue.reports.sales-reports.index' }" class="py-2 px-4 block">
              <span><nobr>Sales Report</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'revenue.reports.full-shifts-summary.index' }" class="py-2 px-4 block">
              <span><nobr>Daily Shift Report</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'revenue.reports.customer-reports.index' }" @click="setActiveRoute('revenue.reports.customer-reports.index')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'revenue.reports.customer-reports.index' }" class="py-2 px-4 block">
              <span><nobr>Customer Report</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'revenue.reports.aging-schedule.index' }" @click="setActiveRoute('revenue.reports.aging-schedule.index')"
                         :class="{ 'active_main_menu': activeRoute.routeName === 'revenue.reports.aging-schedule.index' }" class="py-2 px-4 block">
              <span><nobr>Aging Schedule</nobr></span>
            </router-link>
          </li>
          
        </ul>
      </li>

     
    </ul>
  </div>

  <!--For Accounts Menu-->
  <div v-if="activeMenu.name === 'accounts'" class="flex items-center justify-start p-2 mt-2 text-sm text-purple-100 sm:rounded-lg" style="background-color: #369382">
    <ul class="flex items-center text-gray-700">

      <li class="main_sub_menu_two">
        <router-link :to="'#'" class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span class="mr-1"><nobr>Configuration</nobr></span>
        </router-link>
        <ul class="fixed sub_sub_menu_two  z-50">
          <li>
            <router-link :to="{ name: 'accounts.balance-and-income-lines.index' }" class="py-2 px-4 block">
              <span><nobr>Balance Income Lines</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'accounts.accounts.index' }" class="py-2 px-4 block">
              <span><nobr>Accounts</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'accounts.cash-accounts.index' }" class="py-2 px-4 block">
              <span><nobr>Cash Accounts</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'accounts.cost-centers.index' }" class="py-2 px-4 block">
              <span><nobr>Cost Centers</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'accounts.opening-balances.index' }" class="py-2 px-4 block">
              <span><nobr>Opening Balance</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="'#'" class="py-2 px-4 block">
              <span><nobr>Cash Flow Lines</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="'#'" class="py-2 px-4 block">
              <span><nobr>Chart of Accounts</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="'#'" class="py-2 px-4 block">
              <span><nobr>Bank Accounts</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="'#'" class="py-2 px-4 block">
              <span><nobr>Salary Heads</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="'#'" class="py-2 px-4 block">
              <span><nobr>Fixed Assets</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="'#'" class="py-2 px-4 block">
              <span><nobr>Fixed Asset Depreciation</nobr></span>
            </router-link>
          </li>
        </ul>
      </li>

      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'accounts.transactions.index' }" class="flex items-center" @click="setActiveRoute('accounts.transactions.index')"
                     :class="{ 'active_main_menu': activeRoute.routeName === 'accounts.transactions.index' }">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span><nobr>Vouchers</nobr></span>
        </router-link>
      </li>
      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'accounts.bank-reconciliations.index' }" class="flex items-center" @click="setActiveRoute('accounts.bank-reconciliations.index')"
                     :class="{ 'active_main_menu': activeRoute.routeName === 'accounts.bank-reconciliations.index' }">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span><nobr>Bank Reconciliation</nobr></span>
        </router-link>
      </li>
      <li class="flex items-center mr-2">
        <router-link :to="'#'" class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span><nobr>Loan</nobr></span>
        </router-link>
      </li>
      <li class="flex items-center mr-2">
        <router-link :to="'#'" class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span><nobr>Loan Receipt</nobr></span>
        </router-link>
      </li>
      <li class="main_sub_menu_two">
        <router-link :to="'#'" class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span><nobr>AIS Report</nobr></span>
        </router-link>
        <ul class="fixed sub_sub_menu_two  z-50">
          <li>
            <router-link :to="{ name: 'accounts.ais-reports.balance-sheet' }" class="py-2 px-4 block">
              <span><nobr>AIS - Balance Sheet</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'accounts.ais-reports.income-statement' }" class="py-2 px-4 block">
              <span><nobr>AIS - Income Statement</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'accounts.ais-reports.ledgers' }" class="py-2 px-4 block">
              <span><nobr>AIS - Ledger</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'accounts.ais-reports.trial-balance' }" class="py-2 px-4 block">
              <span><nobr>AIS - Trial Balance</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'accounts.ais-reports.day-book' }" class="py-2 px-4 block">
              <span><nobr>AIS - Day Book</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="'#'" class="py-2 px-4 block">
              <span><nobr>AIS - Fixed Asset Statement</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="'#'" class="py-2 px-4 block">
              <span><nobr>AIS - Ledger Group Report</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="'#'" class="py-2 px-4 block">
              <span><nobr>AIS - Cost Center</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="'#'" class="py-2 px-4 block">
              <span><nobr>AIS - Cost Center Summary</nobr></span>
            </router-link>
          </li>
        </ul>
      </li>
    </ul>
  </div>

  <!--For Payroll Menu-->
  <div v-if="activeMenu.name === 'payroll'" class="flex items-center justify-start p-2 mt-2 text-sm text-purple-100 sm:rounded-lg" style="background-color: #369382">
    <ul class="flex items-center text-gray-700">
      <li class="flex items-center mr-2">
        <router-link :to="'#'" class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span>Home</span>
        </router-link>
      </li>
    </ul>
  </div>

  <!--For Supply Chain Menu-->
  <div v-if="activeMenu.name === 'supply-chain'" class="flex items-center justify-start p-2 mt-2 text-sm text-purple-100 sm:rounded-lg" style="background-color: #369382">
    <ul class="flex items-center text-gray-700">
      <li class="main_sub_menu_two">
        <router-link :to="'#'" class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span class="mr-1"><nobr>Configuration</nobr></span>
        </router-link>
        <ul class="fixed sub_sub_menu_two  z-50">
          <li>
            <router-link :to="{ name: 'configuration.warehouse.index' }" class="py-2 px-4 block">
              <span><nobr>Warehouse</nobr></span>
            </router-link>
          </li>
<!--          <li>-->
<!--            <router-link :to="{ name: 'configuration.tank.index' }" class="py-2 px-4 block">-->
<!--              <span><nobr>Tank</nobr></span>-->
<!--            </router-link>-->
<!--          </li>-->
          <li>
            <router-link :to="{ name: 'configuration.vendor.index' }" class="py-2 px-4 block">
              <span><nobr>Vendor</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'revenue.purchase-rate.index' }" class="py-2 px-4 block">
              <span><nobr>Vendor Tariff</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'scm.material-category.index' }" class="py-2 px-4 block">
              <span><nobr>Material Categories</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'scm.material.index' }" class="py-2 px-4 block">
              <span><nobr>Materials</nobr></span>
            </router-link>
          </li>
        </ul>
      </li>

      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'scm.material-requisitions.index' }" class="flex items-center" @click="setActiveRoute('scm.material-requisitions.index')"
                     :class="{ 'active_main_menu': activeRoute.routeName === 'scm.material-requisitions.index' }">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span><nobr>Material Requisition</nobr></span>
        </router-link>
      </li>
      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'scm.comparative-statement.index' }" class="flex items-center" @click="setActiveRoute('scm.comparative-statement.index')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'scm.comparative-statement.index' }"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span><nobr>Comparative Statements</nobr></span>
        </router-link>
      </li>
      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'scm.purchase-order.index' }" class="flex items-center" @click="setActiveRoute('scm.purchase-order.index')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'scm.purchase-order.index' }"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span><nobr>Purchase Order</nobr></span>
        </router-link>
      </li>
      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'scm.supplier-bills.index' }" class="flex items-center" @click="setActiveRoute('scm.supplier-bills.index')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'scm.supplier-bills.index' }"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span><nobr>Supplier Bill</nobr></span>
        </router-link>
      </li>
      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'scm.material-receives.index' }" class="flex items-center" @click="setActiveRoute('scm.material-receives.index')"
                     :class="{ 'active_main_menu': activeRoute.routeName === 'scm.material-receives.index' }">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span><nobr>Material Receives</nobr></span>
        </router-link>
      </li>
      <li class="flex items-center mr-2">
        <router-link :to="{ name: 'scm.material-adjustments.index' }" class="flex items-center" @click="setActiveRoute('scm.material-adjustments.index')"
                     :class="{ 'active_main_menu': activeRoute.routeName === 'scm.material-adjustments.index' }">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span><nobr>Material Adjustments</nobr></span>
        </router-link>
      </li>
      <li class="flex items-center mr-2">
        <router-link :to="'#'" class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span><nobr>Material Issue</nobr></span>
        </router-link>
      </li>

      <li class="main_sub_menu_two">
        <router-link :to="'#'" class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span class="mr-1"><nobr>Movements</nobr></span>
        </router-link>
        <ul class="fixed sub_sub_menu_two  z-50">
          <li>
            <router-link :to="{ name: 'scm.material-movement-requisitions.index' }" class="py-2 px-4 block">
              <span><nobr>Movement Requisition</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'scm.material-movements.index', params: { movementType: 'movementIn' } }" class="py-2 px-4 block">
              <span><nobr>Movement In</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'scm.material-movements.index', params: { movementType: 'movementOut' } }" class="py-2 px-4 block">
              <span><nobr>Movement Out</nobr></span>
            </router-link>
          </li>
        </ul>
      </li>
      <li class="main_sub_menu_two">
        <router-link :to="'#'" class="flex items-center" @click="setActiveRoute('scm.reports.material-ledger')"
        :class="{ 'active_main_menu': activeRoute.routeName === 'scm.reports.material-ledger' }">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
          </svg>
          <span class="mr-1"><nobr>Report</nobr></span>
        </router-link>
        <ul class="fixed sub_sub_menu_two  z-50">
          <li>
            <router-link :to="{ name: 'scm.reports.material-ledger' }" class="py-2 px-4 block">
              <span><nobr>Material Ledger Summary</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'scm.reports.material-ledger.single' }" class="py-2 px-4 block">
              <span><nobr>Single Material Ledger</nobr></span>
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'scm.reports.material-summary' }" class="py-2 px-4 block">
              <span><nobr>Material Summary Report</nobr></span>
            </router-link>
          </li>
          
        </ul>
      </li>

    </ul>
  </div>

</template>

<style scoped>
a{
  font-family: 'Calibri', Times, serif;
  color: #fff;
  font-size: 12px;
}

a:hover{
  color: yellow;
}

li > svg{
  color: #fff;
}

.main_sub_menu_two:hover .sub_sub_menu_two {
  display: block;
}

.sub_sub_menu_two {
  display: none;
}

/*.sub_sub_menu_two {*/
/*  min-width: 10.2rem;*/
/*}*/

</style>
