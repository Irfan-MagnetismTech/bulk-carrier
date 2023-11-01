<script setup>
import {onMounted, watchEffect,watch,ref} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import usePurchaseOrder from "../../../composables/supply-chain/usePurchaseOrder";
import useHelper from "../../../composables/useHelper.js";
import Title from "../../../services/title";
import useDebouncedRef from '../../../composables/useDebouncedRef';
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";

import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
const { getPurchaseOrders, purchaseOrders, deletePurchaseOrder, isLoading } = usePurchaseOrder();
const { numberFormat } = useHelper();
const { setTitle } = Title();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const critical = ['No','Yes'];
// Code for global search start
const columns = ["date"];
const searchKey = useDebouncedRef('', 600);
const table = "purchase_orders";

const icons = useHeroIcon();

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

setTitle('Purchase Orders');
// Code for global search starts here

watch(searchKey, newQuery => {
  getPurchaseOrders(props.page, columns, searchKey.value, table);
});


onMounted(() => {
  watchEffect(() => {
    getPurchaseOrders(props.page,businessUnit.value)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");
      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching PR:", error);
    });
});

});// Code for global search end here
function confirmDelete(id) {
        Swal.fire({
          title: 'Are you sure?',
          text: "You want to change delete this Unit!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {
            deletePurchaseOrder(id);
          }
        })
      }
</script>

<template>
  <!-- Heading -->
 
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Purchase Order List</h2>
    <!-- <default-button :title="'Create Purchase Order'" :to="{ name: 'scm.purchase-orders.create' }" :icon="icons.AddIcon"></default-button> -->
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <!-- <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Showing {{ purchaseOrders?.from }}-{{ purchaseOrders?.to }} of {{ purchaseOrders?.total }}</span> -->
    <filter-with-business-unit v-model="businessUnit"></filter-with-business-unit>
    <!-- Search -->
    <div class="relative w-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
      </svg>
      <input type="text" v-model="searchKey" placeholder="Search..." class="search" />
    </div>
  </div>
  <!-- Table -->
  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
          <thead v-once>
          <tr class="w-full">
            <th>#</th>
            <th>PR No</th>
            <th>Raised Date</th>
            <th>Is Critical</th>
            <th>Purchase Center</th>
            <th>Warehouse</th>
            <th>Business Unit</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="(purchaseOrder,index) in (purchaseOrders?.data ? purchaseOrders?.data : purchaseOrders)" :key="index">
              <td>{{ purchaseOrders?.from + index }}</td>
              <td>{{ purchaseOrder?.pr_no }}</td>
              <td>{{ purchaseOrder?.raised_date }}</td>
              <td>{{ critical[purchaseOrder?.is_critical] }}</td>
              <td>{{ purchaseOrder?.purchase_center }}</td>
              <td>{{ purchaseOrder?.scmWarehouse?.name?? '' }}</td>
              <td>
                <span :class="purchaseOrder?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ purchaseOrder?.business_unit }}</span>
              </td>
              <td>
                <action-button :action="'edit'" :to="{ name: 'scm.purchase-orders.edit', params: { purchaseOrderId: purchaseOrder.id } }"></action-button>
                <action-button @click="confirmDelete(purchaseOrder.id)" :action="'delete'"></action-button>
              </td>
            </tr>
          </tbody>
          <tfoot v-if="!purchaseOrders?.data?.length" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="7">Loading...</td>
        </tr>
        <tr v-else-if="!purchaseOrders?.data?.length">
          <td colspan="7">No PR found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="purchaseOrders" to="scm.opening-stock.index" :page="page"></Paginate>
  </div>
  <!-- Heading -->
  
  

  
</template>