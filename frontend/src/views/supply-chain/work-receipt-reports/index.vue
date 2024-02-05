<script setup>
import {onMounted, watchEffect,watch,ref, watchPostEffect} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useMaterialReceiptReport from "../../../composables/supply-chain/useMaterialReceiptReport";
import useWorkReceiptReport from "../../../composables/supply-chain/useWorkReceiptReport";
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

// const { getMaterialReceiptReports, materialReceiptReports, deleteMaterialReceiptReport, isLoading,isTableLoading } = useMaterialReceiptReport();
const { getWorkReceiptReports, workReceiptReport, deleteWorkReceiptReport, isLoading, isTableLoading } = useWorkReceiptReport();
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

const settled = ['No','Yes'];
// Code for global search start
const icons = useHeroIcon();

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

setTitle('Work Receipt Reports');
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
      "label": "MRR No",
      "filter_type": "input" 
    },
    {
      "relation_name": "scmPo",
      "field_name": "ref_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "PO No",
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
      "field_name": "date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Date",
      "filter_type": "input"
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
      router.push({ name: 'scm.work-receipt-reports.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getWorkReceiptReports(filterOptions.value)
      .then(() => {
        paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");
      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching Work Receipt Report:", error);
    });
});
});



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
            deleteWorkReceiptReport(id);
          }
        })
      }
</script>

<template>
  <!-- Heading -->
 
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Work Receipt Report List</h2>
    <default-button :title="'Create Work Receipt Report'" :to="{ name: 'scm.work-receipt-reports.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <!-- Table -->
  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
          <!-- <thead v-once>
          <tr class="w-full">
            <th>#</th>
            <th>MRR No</th>
            <th>PO No</th>
            <th>PR No</th>
            <th>Warehouse</th>
            <th>Date</th>
            <th>Business Unit</th>
            <th>Action</th>
          </tr>
          </thead> -->
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody>
            <tr v-for="(workReceiptReport,index) in (workReceiptReports?.data ? workReceiptReports?.data : workReceiptReports)" :key="index">
              <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
              <td>{{ workReceiptReport?.ref_no }}</td>
              <td>{{ workReceiptReport?.scmPo?.ref_no ?? "N/A" }}</td>
              <td>{{ workReceiptReport?.scmPr?.ref_no ?? "N/A" }}</td>
              <td>{{ workReceiptReport?.scmWarehouse?.name ?? "N/A" }}</td>
              <td>{{ workReceiptReport?.date }}</td>
              <td>
                <span :class="workReceiptReport?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ workReceiptReport?.business_unit }}</span>
              </td>
              <td>
                <nobr>
                <action-button :action="'show'" :to="{ name: 'scm.material-receipt-reports.show', params: { workReceiptReportId: workReceiptReport.id } }"></action-button>
                <action-button :action="'edit'" :to="{ name: 'scm.material-receipt-reports.edit', params: { workReceiptReportId: workReceiptReport.id } }"></action-button>
                <action-button @click="confirmDelete(workReceiptReport.id)" :action="'delete'"></action-button>
               </nobr>
              </td>
            </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && workReceiptReports?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!workReceiptReports?.data?.length" class="relative h-[250px]">
            <tr v-if="isLoading">
            </tr>
            <tr v-else-if="isTableLoading">
                <td colspan="7">
                  <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                </td>
            </tr>
            <tr v-else-if="!workReceiptReports?.data?.length">
              <td colspan="7">No Data found.</td>
            </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="workReceiptReports" to="scm.work-receipt-reports.index" :page="page"></Paginate>
  </div>
  <!-- Heading -->
  
  

  <ErrorComponent :errors="errors"></ErrorComponent>  
</template>