<script setup>
import {onMounted, watchEffect,watch,ref, watchPostEffect} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useMaterialCosting from "../../../composables/supply-chain/useMaterialCosting";
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
import moment from 'moment';
import { formatDate } from '../../../utils/helper';

import { useRouter } from 'vue-router';
const { getMaterialCostings, materialCostings, deleteMaterialCosting, isLoading,isTableLoading ,errors,approveMaterialCosting} = useMaterialCosting();
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

const icons = useHeroIcon();

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

setTitle('Store Requisitions');
// Code for global search starts here

let filterOptions = ref({
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": "scmPo",
      "field_name": "ref_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Po Ref",
      "filter_type": "input"
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
      "relation_name": 'scmWarehouse',
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
      "filter_type": "date"
    },{
      "relation_name": null,
      "field_name": "status",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Status",
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
        router.push({ name: 'scm.material-costings.index', query: { page: filterOptions.value.page } });
      } else {
        filterOptions.value.page = props.page;
      }
      currentPage.value = props.page;
      if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
        filterOptions.value.isFilter = true;
      }
      getMaterialCostings(filterOptions.value)
        .then(() => {
          paginatedPage.value = filterOptions.value.page;
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

function approveData(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to approve this data!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      approveMaterialCosting(id);
    }
  })
}


function confirmDelete(id) {
        Swal.fire({
          title: 'Are you sure?',
          text: "You want delete this data!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {
            deleteMaterialCosting(id);
          }
        })
      }
</script>

<template>
  <!-- Heading -->
 
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Material Costing List</h2>
    <default-button :title="'Create Material Costing'" :to="{ name: 'scm.material-costings.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <!-- Table -->
  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody>
            <tr v-for="(materialCosting,index) in (materialCostings?.data ? materialCostings?.data : materialCostings)" :key="index">
              <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
              <td>{{ materialCosting?.scmPo?.ref_no?? '' }}</td>
              <td>{{ materialCosting.purchase_center }}</td>
              <td>{{ materialCosting.scmWarehouse.name }}</td>
              <td><no-br>{{ formatDate(materialCosting?.date) }}</no-br></td>
              <td>{{ materialCosting.status }}</td>
              <td>
                <!-- <span :class="materialCosting?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ materialCosting?.business_unit }}</span> -->
              </td>
              <td>
                <nobr>
                <div class="grid grid-flow-col-dense gap-x-2">
                  <!-- <button @click="navigateToSICreate(materialCosting.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700">Create SI</button> -->
                  <!-- <button @click="navigateToPOCreate(materialCosting.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700">Create PO</button>
                  <button @click="navigateToMRRCreate(materialCosting.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700">Create MRR</button> -->
                  <action-button :action="'approve'" @click="approveData(materialCosting.id)" v-if="materialCosting.status"></action-button>
                  <action-button :action="'show'" :to="{ name: 'scm.material-costings.show', params: { materialCostingId: materialCosting.id } }"></action-button>
                  <!-- <action-button :action="'edit'" :to="{ name: 'scm.material-costings.edit', params: { materialCostingId: materialCosting.id } }" v-if="(materialCosting?.scmSis.length <= 0)"></action-button> -->
                   <action-button :action="'edit'" :to="{ name: 'scm.material-costings.edit', params: { materialCostingId: materialCosting.id } }" v-if="materialCosting.status"></action-button>
                  <action-button @click="confirmDelete(materialCosting.id)" :action="'delete'" v-if="materialCosting.status"></action-button>
                </div>
              </nobr>
              </td>
            </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && materialCostings?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!materialCostings?.data?.length" class="relative h-[250px]">
            <tr v-if="isLoading">
            </tr>
            <tr v-else-if="isTableLoading">
                <td colspan="7">
                  <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                </td>
            </tr>
            <tr v-else-if="!materialCostings?.data?.length">
              <td colspan="7">No Data found.</td>
            </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="materialCostings" to="scm.store-requisitions.index" :page="page"></Paginate>
  </div>
  <!-- Heading -->
  
  

  <ErrorComponent :errors="errors"></ErrorComponent>  
</template>