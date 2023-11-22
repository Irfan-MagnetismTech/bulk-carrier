<script setup>
import {onMounted, watchEffect,watch,ref} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useMaterialReceiptReport from "../../../composables/supply-chain/useMaterialReceiptReport";
import useHelper from "../../../composables/useHelper.js";
import Title from "../../../services/title";
import useDebouncedRef from '../../../composables/useDebouncedRef';
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import { useRouter } from 'vue-router';

import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
const { getMaterialReceiptReports, materialReceiptReports, deleteMaterialReceiptReport, isLoading } = useMaterialReceiptReport();
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
const columns = ["ref_no"];
const searchKey = useDebouncedRef('', 600);
const table = "material_receipt_reports";

const icons = useHeroIcon();

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

setTitle('Material Receipt Reports');
// Code for global search starts here

watch(searchKey, newQuery => {
  getMaterialReceiptReports(props.page, columns, searchKey.value, table);
});


onMounted(() => {
  watchEffect(() => {
    getMaterialReceiptReports(props.page,businessUnit.value)
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

});



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
            deleteMaterialReceiptReport(id);
          }
        })
      }
</script>

<template>
  <!-- Heading -->
 
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Material Receipt Report List</h2>
    <!-- <default-button :title="'Create Material Receipt Report'" :to="{ name: 'scm.material-receipt-reports.create' }" :icon="icons.AddIcon"></default-button> -->
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
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
            <th>MRR No</th>
            <th>PO No</th>
            <th>PR No</th>
            <th>Warehouse</th>
            <th>Date</th>
            <th>Business Unit</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="(materialReceiptReport,index) in (materialReceiptReports?.data ? materialReceiptReports?.data : materialReceiptReports)" :key="index">
              <td>{{ materialReceiptReports?.from + index }}</td>
              <td>{{ materialReceiptReport?.ref_no }}</td>
              <td>{{ materialReceiptReport?.scmPo?.ref_no }}</td>
              <td>{{ materialReceiptReport?.scmPr?.ref_no }}</td>
              <td>{{ materialReceiptReport?.scmWarehouse?.name }}</td>
              <td>{{ materialReceiptReport?.date }}</td>
              <td>
                <span :class="materialReceiptReport?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ materialReceiptReport?.business_unit }}</span>
              </td>
              <td>
                <action-button :action="'edit'" :to="{ name: 'scm.material-receipt-reports.edit', params: { materialReceiptReportId: materialReceiptReport.id } }"></action-button>
                <action-button @click="confirmDelete(materialReceiptReport.id)" :action="'delete'"></action-button>
              </td>
            </tr>
          </tbody>
          <tfoot v-if="!materialReceiptReports?.data?.length" class="bg-white ">
        <tr v-if="isLoading">
          <td colspan="8">Loading...</td>
        </tr>
        <tr v-else-if="!materialReceiptReports?.data?.length">
          <td colspan="8">No MRR found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="materialReceiptReports" to="scm.material-receipt-reports.index" :page="page"></Paginate>
  </div>
  <!-- Heading -->
  
  

  
</template>