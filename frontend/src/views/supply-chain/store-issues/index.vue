<script setup>
import {onMounted, watchEffect,watch,ref} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useStoreIssue from "../../../composables/supply-chain/useStoreIssue";
import useHelper from "../../../composables/useHelper.js";
import Title from "../../../services/title";
import useDebouncedRef from '../../../composables/useDebouncedRef';
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import { useRouter } from 'vue-router';

import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
const { getStoreIssues, storeIssues, deleteStoreIssue, isLoading } = useStoreIssue();
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
const table = "store_requisitions";

const icons = useHeroIcon();

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

setTitle('Store Issues');
// Code for global search starts here

watch(searchKey, newQuery => {
  getStoreIssues(props.page, columns, searchKey.value, table);
});


onMounted(() => {
  watchEffect(() => {
    getStoreIssues(props.page,businessUnit.value)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");
      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching SR:", error);
    });
});

});
// Code for global search end here

// const navigateToPOCreate = (storeIssueId) => {
//   const pr_id = storeIssueId; 
//   const cs_id = null;
//   const routeOptions = {
//     name: 'scm.store-orders.create',
//     query: {
//       pr_id: pr_id,
//       cs_id: cs_id
//     }
//   };
//   router.push(routeOptions);
// };  

// const navigateToMRRCreate = (storeIssueId) => {
//   const pr_id = storeIssueId; 
//   const po_id = null;
//   const routeOptions = {
//     name: 'scm.material-receipt-reports.create',
//     query: {
//       pr_id: pr_id,
//       po_id: po_id
//     }
//   };
//   router.push(routeOptions);
// };


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
            deleteStoreIssue(id);
          }
        })
      }
</script>

<template>
  <!-- Heading -->
 
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Store Issue List</h2>
    <!-- <default-button :title="'Create Store Issue'" :to="{ name: 'scm.store-issues.create' }" :icon="icons.AddIcon"></default-button> -->
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <!-- <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Showing {{ storeIssues?.from }}-{{ storeIssues?.to }} of {{ storeIssues?.total }}</span> -->
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
            <th>Ref No</th>
            <th>Date</th>
            <th>Warehouse</th>
            <th>Department</th>
            <th>Business Unit</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="(storeIssue,index) in (storeIssues?.data ? storeIssues?.data : storeIssues)" :key="index">
              <td>{{ storeIssues?.from + index }}</td>
              <td>{{ storeIssue?.ref_no }}</td>
              <td>{{ storeIssue?.date }}</td>
              <td>{{ storeIssue?.scmWarehouse?.name?? '' }}</td>
              <td>{{ storeIssue?.scmWarehouse?.name?? '' }}</td>
              <td>
                <span :class="storeIssue?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ storeIssue?.business_unit }}</span>
              </td>
              <td>
                <div class="grid grid-flow-col-dense gap-x-2">
                  <!-- <button @click="navigateToPOCreate(storeIssue.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700">Create PO</button>
                  <button @click="navigateToMRRCreate(storeIssue.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700">Create MRR</button> -->
                  <action-button :action="'edit'" :to="{ name: 'scm.store-issues.edit', params: { storeIssueId: storeIssue.id } }"></action-button>
                  <action-button @click="confirmDelete(storeIssue.id)" :action="'delete'"></action-button>
                </div>
              </td>
            </tr>
          </tbody>
          <tfoot v-if="!storeIssues?.data?.length" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="8">Loading...</td>
        </tr>
        <tr v-else-if="!storeIssues?.data?.length">
          <td colspan="8">No SI found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="storeIssues" to="scm.store-issues.index" :page="page"></Paginate>
  </div>
  <!-- Heading -->
  
  

  
</template>