<script setup>
import {onMounted, watchEffect,watch,ref, watchPostEffect} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
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
import RemarksComponent from '../../../components/utils/RemarksComponent.vue';

import { useRouter } from 'vue-router';
import usePurchaseOrder from '../../../composables/supply-chain/usePurchaseOrder';

const { getPurchaseOrders, purchaseOrders, deletePurchaseOrder, isLoading,isTableLoading,errors,closePo} = usePurchaseOrder();

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

const isModalOpen = ref(0);
const details = ref([{type: ''}]);
const currentIndex = ref(null);
const closingRemarks = ref(null);

function showModal(id) {
  isModalOpen.value = 1
  currentIndex.value = id;
}

function closeModel() {
  isModalOpen.value = 0
  closingRemarks.value = null;
  currentIndex.value = null;
}

function closePurchaseOrder() {
  closePo(currentIndex.value,closingRemarks.value).then(() => {
            closeModel()
          }).catch((error) => {
            console.error("Error closing PO:", error);
          });
}

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
      "relation_name": "scmPoItems.scmMaterial",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Items",
      "filter_type": "input",
      "no_short" : true
    },
    {
      "relation_name": "scmPoItems.scmMaterial",
      "field_name": "material_code",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Code",
      "filter_type": "input",
      "no_short" : true
    },
    {
      "relation_name": "scmPoItems",
      "field_name": "quantity",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Qty",
      "filter_type": "input",
      "no_short" : true
    },
    {
      "relation_name": "scmPoItems",
      "field_name": "unit",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Unit",
      "filter_type": "input",
      "no_short" : true
    },
    {
      "relation_name": "scmVendor",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Supplier",
      "filter_type": "input"
    },
    {
      "relation_name": "scmVendor",
      "field_name": "country_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Origin",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "net_amount",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Value",
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
      "relation_name": null,
      "field_name": "status",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Status",
      "filter_type": "dropdown",
      "select_options": [
        {
          value: '',
          label: "ALL",
          defaultSelected : true
        },
        {
          value: "Pending",
          label: "Pending"
        },
        {
          value: "WIP",
          label: "WIP"
        },
        {
          value: "Closed",
          label: "Closed"
        },

      ]
    },
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

          
// const navigateToMRRCreate = (purchaseOrderId) => {
//         const pr_id = null; 
//         const po_id = purchaseOrderId;
//         const routeOptions = {
//           name: 'scm.material-receipt-reports.create',
//           query: {
//             pr_id: pr_id,
//             po_id: po_id
//           }
//         };
//         router.push(routeOptions);
//       };
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
              <td style="text-align: center !important;">
                <table class="w-full">
                  <tr v-for="(line,index) in purchaseOrder?.scmPoItems" :key="index">
                    <td><nobr>{{ line?.scmMaterial.name ?? '' }}</nobr></td>
                  </tr>
                </table>
              </td>
              <td style="text-align: center !important;">
                <table class="w-full">
                  <tr v-for="(line,index) in purchaseOrder?.scmPoItems" :key="index">
                    <td><nobr>{{ line?.scmMaterial.material_code ?? '' }}</nobr></td>
                  </tr>
                </table>
              </td>
              <td style="text-align: center !important;">
                <table class="w-full">
                  <tr v-for="(line,index) in purchaseOrder?.scmPoItems" :key="index">
                    <td><nobr>{{ line?.quantity ?? '' }}</nobr></td>
                  </tr>
                </table>
              </td>
              <td style="text-align: center !important;">
                <table class="w-full">
                  <tr v-for="(line,index) in purchaseOrder?.scmPoItems" :key="index">
                    <td><nobr>{{ line?.scmMaterial.unit ?? '' }}</nobr></td>
                  </tr>
                </table>
              </td>
              <td>{{ purchaseOrder?.scmVendor?.name }}</td>
              <td>{{ purchaseOrder?.scmVendor?.country_name }}</td>
              <td>{{ purchaseOrder?.net_amount }}</td>
              <td>{{ purchaseOrder?.scmCs?.ref_no ?? 'N/A' }}</td>
              <td>{{ purchaseOrder?.scmWarehouse?.name }}</td>
              <td>
                <!-- <button v-if="purchaseRequisition.is_closed == 0" @click="showModal(purchaseRequisition.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700">Close</button>
                <span v-else :class="purchaseRequisition?.is_closed === 0 ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ purchaseRequisition?.is_closed === 0 ? 'Open' : 'Closed' }}</span> -->
                <span :class="purchaseOrder?.status === 'Pending' ? 'text-yellow-700 bg-yellow-100' : (purchaseOrder?.status == 'WIP' ? 'text-blue-700 bg-blue-100' : 'text-red-700 bg-red-100') " class="px-2 py-1 font-semibold leading-tight rounded-full">{{ purchaseOrder?.status ?? 'Closed' }}</span>
              </td>
              
              <td>
                <span :class="purchaseOrder?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ purchaseOrder?.business_unit }}</span>
              </td>
              <td>
                <nobr>
                <div class="grid grid-flow-col-dense gap-x-2">
                  <action-button v-show="purchaseOrder.status !== 'Closed'" @click="showModal(purchaseOrder?.id)" :action="'close'"></action-button>
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
                <td colspan="14">
                  <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                </td>
            </tr>
            <tr v-else-if="!purchaseOrders?.data?.length">
              <td colspan="14">No Data found.</td>
            </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="purchaseOrders" to="scm.purchase-orders.index" :page="page"></Paginate>
  </div>
  <!-- Heading -->
  
  

  <ErrorComponent :errors="errors"></ErrorComponent>  


  <div v-show="isModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark-disabled:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark-disabled:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeModel">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
              <path
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <!-- Modal body -->
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead v-once>
          <tr style="background-color: #04AA6D;color: white"
              class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark-disabled:border-gray-700 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th colspan="5">Remarks</th>
          </tr>
          </thead>
        </table>
        <div class="dt-responsive table-responsive">
          <table id="dataTable" class="w-full table table-striped table-bordered">
            <tbody>
              <tr>
                <td>
                <RemarksComponent v-model="closingRemarks" :maxlength="300" :fieldLabel="'Closing Remarks'" isRequired="true" hideLebel="true"></RemarksComponent>
                </td>
              </tr>
           </tbody>
          </table>
        </div>
        <footer class="flex flex-col items-center justify-between px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark-disabled:bg-gray-800">
          <button type="button" @click="closeModel" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            CLOSE
          </button>
          <button type="button" @click="closePurchaseOrder" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            CONFIRM
          </button>
        </footer>
      </div>
    </form>
    </div>


</template>