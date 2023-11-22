<script setup>
import {onMounted, watchEffect,watch,ref} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useLcRecord from "../../../composables/supply-chain/useLcRecord";
import useHelper from "../../../composables/useHelper.js";
import Title from "../../../services/title";
import useDebouncedRef from '../../../composables/useDebouncedRef';
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";

import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
const { getLcRecords, lcRecords, deleteLcRecord, isLoading } = useLcRecord();
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
const table = "scm_lc_records";

const icons = useHeroIcon();

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

setTitle('Lc Records');
// Code for global search starts here

watch(searchKey, newQuery => {
  getLcRecords(props.page, columns, searchKey.value, table);
});


onMounted(() => {
  watchEffect(() => {
    getLcRecords(props.page,businessUnit.value)
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
          text: "You want to change delete this data!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {
            deleteLcRecord(id);
          }
        })
      }
</script>

<template>
  <!-- Heading -->
 
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">LC Records List</h2>
    <default-button :title="'Create LC Records'" :to="{ name: 'scm.lc-records.create' }" :icon="icons.AddIcon"></default-button>
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
            <th>LC No</th>
            <th>LC Date</th>
            <th>Expire Date</th>
            <th>Weight</th>
            <th>Invoice Value</th>
            <th>Assessment Value</th>
            <th>Business Unit</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="(lcRecord,index) in (lcRecords?.data ? lcRecords?.data : lcRecords)" :key="index">
              <td>{{ lcRecords?.from + index }}</td>
              <td>{{ lcRecord?.lc_no }}</td>
              <td>{{ lcRecord?.lc_date }}</td>
              <td>{{ lcRecord?.expire_date }}</td>
              <td>{{ lcRecord?.weight }}</td>
              <td>{{ lcRecord?.invoice_value }}</td>
              <td>{{ lcRecord?.assessment_value }}</td>
              <td>
                <span :class="lcRecord?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ lcRecord?.business_unit }}</span>
              </td>
              <td>
                <action-button :action="'edit'" :to="{ name: 'scm.lc-records.edit', params: { lcRecordId: lcRecord.id } }"></action-button>
                <action-button @click="confirmDelete(lcRecord.id)" :action="'delete'"></action-button>
              </td>
            </tr>
          </tbody>
          <tfoot v-if="!lcRecords?.data?.length" class="bg-white ">
        <tr v-if="isLoading">
          <td colspan="9">Loading...</td>
        </tr>
        <tr v-else-if="!lcRecords?.data?.length">
          <td colspan="9">No LC Record found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="lcRecord" to="scm.lc-records.index" :page="page"></Paginate>
  </div>
  <!-- Heading -->
  
  

  
</template>