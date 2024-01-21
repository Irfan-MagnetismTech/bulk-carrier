<script setup>
import {onMounted, watchEffect,watch,ref, watchPostEffect} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import usePurchaseOrder from "../../../composables/supply-chain/usePurchaseOrder";
import useHelper from "../../../composables/useHelper.js";
import Title from "../../../services/title";
import useDebouncedRef from '../../../composables/useDebouncedRef';
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";

import { useRouter } from 'vue-router';
const { getPurchaseOrders, purchaseOrders, deletePurchaseOrder, isLoading,isTableLoading,errors} = usePurchaseOrder();
const { numberFormat } = useHelper();
const { setTitle } = Title();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const router = useRouter();


// Code for global search start

const icons = useHeroIcon();

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

setTitle('Purchase Orders');
// Code for global search starts here

let filterOptions = ref({
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "ref_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "PO No",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "PO Date",
      "filter_type": "date"
    },
    {
      "relation_name": "scmWarehouse",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Warehouse",
      "filter_type": "input"
    },
    {
      "relation_name": "scmVendor",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Vendor Name",
      "filter_type": "input"
    },
    {
      "relation_name": "scmPr",
      "field_name": "ref_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "PR No",
      "filter_type": "input"
    },
    {
      "relation_name": "scmCs",
      "field_name": "ref_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "CS No",
      "filter_type": "input"
    }
  ]
});

const currentPage = ref(1);
const paginatedPage = ref(1);

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);



onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'scm.purchase-orders.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getPurchaseOrders(filterOptions.value)
      .then(() => {
        paginatedPage.value = filterOptions.value.page;
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
          text: "You want to delete this data!",
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

          
const navigateToMRRCreate = (purchaseOrderId) => {
        const pr_id = null; 
        const po_id = purchaseOrderId;
        const routeOptions = {
          name: 'scm.material-receipt-reports.create',
          query: {
            pr_id: pr_id,
            po_id: po_id
          }
        };
        router.push(routeOptions);
      };
</script>

<template>
  <!-- Heading -->
 
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Purchase Order List</h2>
    <default-button :title="'Create Purchase Order'" :to="{ name: 'scm.purchase-orders.create' }" :icon="icons.AddIcon"></default-button>
  </div>
 
  <!-- Table -->
  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
          <!-- <thead v-once>
          <tr class="w-full">
            <th>#</th>
            <th>PO No</th>
            <th>PO Date</th>
            <th>Warehouse Name</th>
            <th>Vendor Name</th>
            <th>PR No</th>
            <th>CS No</th>
            <th>Business Unit</th>
            <th>Action</th>
          </tr>
          </thead> -->
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody>
            <tr v-for="(purchaseOrder,index) in (purchaseOrders?.data ? purchaseOrders?.data : purchaseOrders)" :key="index">
              <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
              <td>{{ purchaseOrder?.ref_no }}</td>
              <td>{{ purchaseOrder?.date }}</td>
              <td>{{ purchaseOrder?.scmWarehouse?.name }}</td>
              <td>{{ purchaseOrder?.scmVendor?.name }}</td>
              <td>{{ purchaseOrder?.scmPr?.ref_no }}</td>
              <td>{{ purchaseOrder?.scmCs?.ref_no ?? 'N/A' }}</td>
              <td>
                <span :class="purchaseOrder?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ purchaseOrder?.business_unit }}</span>
              </td>
              <td>
                <nobr>
                <div class="grid grid-flow-col-dense gap-x-2">
                  <!-- <button @click="navigateToMRRCreate(purchaseOrder.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700">Create MRR</button> -->
                  <action-button :action="'show'" :to="{ name: 'scm.purchase-orders.show', params: { purchaseOrderId: purchaseOrder.id } }"></action-button>
                  <template>
                  </template>
                  <action-button :action="'edit'" :to="{ name: 'scm.purchase-orders.edit', params: { purchaseOrderId: purchaseOrder.id } }"></action-button>
                 
                  <action-button @click="confirmDelete(purchaseOrder.id)" :action="'delete'"></action-button>
                </div>
              </nobr>
              </td>
            </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && purchaseOrders?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!purchaseOrders?.data?.length" class="relative h-[250px]">
            <tr v-if="isLoading">
            </tr>
            <tr v-else-if="isTableLoading">
                <td colspan="7">
                  <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                </td>
            </tr>
            <tr v-else-if="!purchaseOrders?.data?.length">
              <td colspan="7">No Data found.</td>
            </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="purchaseOrders" to="scm.purchase-orders.index" :page="page"></Paginate>
  </div>
  <!-- Heading -->
  
  

  <ErrorComponent :errors="errors"></ErrorComponent>  
</template>