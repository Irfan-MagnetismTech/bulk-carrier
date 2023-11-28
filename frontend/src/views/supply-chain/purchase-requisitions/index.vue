<script setup>
import {onMounted, watchEffect,watch,ref, watchPostEffect} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import usePurchaseRequisition from "../../../composables/supply-chain/usePurchaseRequisition";
import useHelper from "../../../composables/useHelper.js";
import Title from "../../../services/title";
import useDebouncedRef from '../../../composables/useDebouncedRef';
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";

import { useRouter } from 'vue-router';
const { getPurchaseRequisitions, purchaseRequisitions, deletePurchaseRequisition, isLoading ,isTableLoading, errors} = usePurchaseRequisition();
const { numberFormat } = useHelper();
const { setTitle } = Title();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const router = useRouter();
const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const critical = ['No','Yes'];
// Code for global search start
const columns = ["ref_no"];
const searchKey = useDebouncedRef('', 600);
const table = "purchase_requisitions";

const icons = useHeroIcon();

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

setTitle('Purchase Requisitions');
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
      "label": "PR No",
      "filter_type": "input" 
    },
    {
      "relation_name": null,
      "field_name": "raised_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Raised Date",
      "filter_type": "input" 
    },
    {
      "relation_name": null,
      "field_name": "is_critical",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Is Critical",
      "filter_type": "select",
      "select_options": [
        {
          "value": '',
          "label": "Select",
          "defaultSelected" : true
        },
        {
          "value": "0",
          "label": "No"
        },
        {
          "value": "1",
          "label": "Yes"
        }
      ]
    },
    {
      "relation_name": null,
      "field_name": "purchase_center",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Purchase Center",
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
      router.push({ name: 'scm.purchase-requisitions.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getPurchaseRequisitions(filterOptions.value)
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
});
// Code for global search end here

const navigateToPOCreate = (purchaseRequisitionId) => {
  const pr_id = purchaseRequisitionId; 
  const cs_id = null;
  const routeOptions = {
    name: 'scm.purchase-orders.create',
    query: {
      pr_id: pr_id,
      cs_id: cs_id
    }
  };
  router.push(routeOptions);
};  

const navigateToMRRCreate = (purchaseRequisitionId) => {
  const pr_id = purchaseRequisitionId; 
  const po_id = null;
  const routeOptions = {
    name: 'scm.material-receipt-reports.create',
    query: {
      pr_id: pr_id,
      po_id: po_id
    }
  };
  router.push(routeOptions);
};


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
            deletePurchaseRequisition(id);
          }
        })
      }
</script>

<template>
  <!-- Heading -->
 
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Purchase Requisition List</h2>
    <default-button :title="'Create Purchase Requisition'" :to="{ name: 'scm.purchase-requisitions.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <!-- Table -->
  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
          <!-- <thead v-once>
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
          </thead> -->
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody>
            <tr v-for="(purchaseRequisition,index) in (purchaseRequisitions?.data ? purchaseRequisitions?.data : purchaseRequisitions)" :key="index">
              <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
              <td>{{ purchaseRequisition?.ref_no }}</td>
              <td>{{ purchaseRequisition?.raised_date }}</td>
              <td>{{ critical[purchaseRequisition?.is_critical] }}</td>
              <td>{{ purchaseRequisition?.purchase_center }}</td>
              <td>{{ purchaseRequisition?.scmWarehouse?.name?? '' }}</td>
              <td>
                <span :class="purchaseRequisition?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ purchaseRequisition?.business_unit }}</span>
              </td>
              <td>
                <div class="grid grid-flow-col-dense gap-x-2">
                  <button @click="navigateToPOCreate(purchaseRequisition.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700">Create PO</button>
                  <button @click="navigateToMRRCreate(purchaseRequisition.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700">Create MRR</button>
                  <action-button :action="'edit'" :to="{ name: 'scm.purchase-requisitions.edit', params: { purchaseRequisitionId: purchaseRequisition.id } }"></action-button>
                  <action-button @click="confirmDelete(purchaseRequisition.id)" :action="'delete'"></action-button>
                </div>
              </td>
            </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && purchaseRequisitions?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!purchaseRequisitions?.data?.length" class="relative h-[250px]">
            <tr v-if="isLoading">
            </tr>
            <tr v-else-if="isTableLoading">
                <td colspan="7">
                  <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                </td>
            </tr>
            <tr v-else-if="!purchaseRequisitions?.data?.length">
              <td colspan="7">No Data found.</td>
            </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="purchaseRequisitions" to="scm.purchase-requisitions.index" :page="page"></Paginate>
  </div>
  <!-- Heading -->
  
  
  <ErrorComponent :errors="errors"></ErrorComponent> 
  
</template>