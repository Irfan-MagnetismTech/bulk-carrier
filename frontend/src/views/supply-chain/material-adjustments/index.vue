<script setup>
import {onMounted, watchEffect,watch,ref, watchPostEffect} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useMaterialAdjustment from "../../../composables/supply-chain/useMaterialAdjustment";
import useHelper from "../../../composables/useHelper.js";
import Title from "../../../services/title";
import useDebouncedRef from '../../../composables/useDebouncedRef';
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import { useRouter } from 'vue-router';
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";


const { getMaterialAdjustments, materialAdjustments, deleteMaterialAdjustment, isLoading } = useMaterialAdjustment();
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

setTitle('Material Adjustments');
// Code for global search starts here

let filterOptions = ref({
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Date",
      "filter_type": "date" 
    },
    {
      "relation_name": null,
      "field_name": "type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Adjustment Type",
      "filter_type": "dropdown",
      "select_options": [
          { value: "", label: "All" ,defaultSelected: true},
          { value: "Addition", label: "Addition" ,defaultSelected: false},
          { value: "Deduction", label: "Deduction",defaultSelected: false},
        ]
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
      "field_name": null,
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Adjusted By",
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
      router.push({ name: 'scm.adjustments.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getMaterialAdjustments(filterOptions.value)
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
// Code for global search end here

// const navigateToPOCreate = (materialAdjustmentId) => {
//   const pr_id = materialAdjustmentId; 
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

// const navigateToMRRCreate = (materialAdjustmentId) => {
//   const pr_id = materialAdjustmentId; 
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
          text: "You want to delete this data!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {
            deleteMaterialAdjustment(id);
          }
        })
      }

      const navigateToSICreate = (SrId) => {
        const sr_id = SrId;
        const routeOptions = {
          name: 'scm.store-issues.create',
          query: {
            sr_id: sr_id,
          }
        };
        router.push(routeOptions);
      };
</script>

<template>
  <!-- Heading -->
 
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Material Adjustment List</h2>
    <default-button :title="'Create Material Adjustment'" :to="{ name: 'scm.material-adjustments.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <!-- Table -->
  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >

          <FilterComponent :filterOptions = "filterOptions"/>

          <tbody>
            <tr v-for="(materialAdjustment,index) in (materialAdjustments?.data ? materialAdjustments?.data : materialAdjustments)" :key="index">
              <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
              <td>{{ materialAdjustment?.date }}</td>
              <td>{{ materialAdjustment?.type ?? '' }}</td>
              <td>{{ materialAdjustment?.scmWarehouse?.name?? '' }}</td>
              <td>{{ materialAdjustment?.scmWarehouse?.name?? '' }}</td>
              <td>
                <span :class="materialAdjustment?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ materialAdjustment?.business_unit }}</span>
              </td>
              <td>
                <div class="grid grid-flow-col-dense gap-x-2">

                  <action-button :action="'edit'" :to="{ name: 'scm.material-adjustments.edit', params: { materialAdjustmentId: materialAdjustment.id } }"></action-button>
                  <action-button @click="confirmDelete(materialAdjustment.id)" :action="'delete'"></action-button>
                  
                </div>
              </td>
            </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && materialAdjustments?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!materialAdjustments?.data?.length" class="relative h-[250px]">
              <tr v-if="isLoading">
              </tr>
              <tr v-else-if="isTableLoading">
                  <td colspan="7">
                    <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                  </td>
              </tr>
              <tr v-else-if="!materialAdjustments?.data?.length">
                <td colspan="7">No Data found.</td>
              </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="materialAdjustments" to="scm.material-adjustments.index" :page="page"></Paginate>
  </div>
  <!-- Heading -->
  
  

  <ErrorComponent :errors="errors"></ErrorComponent>  
</template>