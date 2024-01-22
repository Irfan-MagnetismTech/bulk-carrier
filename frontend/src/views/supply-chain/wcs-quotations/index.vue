<script setup>
import {onMounted, watchEffect,watch,ref, watchPostEffect} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useMaterialCs from "../../../composables/supply-chain/useMaterialCs";
import useWorkCs from '../../../composables/supply-chain/useWorkCs';
import useHelper from "../../../composables/useHelper.js";
import Title from "../../../services/title";
import useDebouncedRef from '../../../composables/useDebouncedRef';
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import { useRouter , useRoute} from 'vue-router';
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import useQuotation from '../../../composables/supply-chain/useQuotation';
import useWcsQuotation from '../../../composables/supply-chain/useWcsQuotation';
import { formatDate } from '../../../utils/helper';

// const { getMaterialCs, materialCs, materialCsLists, deleteMaterialCs, isLoading, errors, isTableLoading,showMaterialCs} = useMaterialCs();
const { getWorkCs, workCs, workCsLists, deleteWorkCs, isLoading, errors, isTableLoading,showWorkCs} = useWorkCs();

// const {getQuotations,quotations,deleteQuotations} = useQuotation();
const {getWcsQuotations,wcsQuotations,deleteWcsQuotations} = useWcsQuotation();
const { numberFormat } = useHelper();
const { setTitle } = Title();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const router = useRouter();
const route = useRoute();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});
// Code for global search start
const WCSID = route.params.wcsId;
const icons = useHeroIcon();
onMounted(() => {
  watchPostEffect(() => {
    getWcsQuotations(WCSID)
      .then(() => {
        const customDataTable = document.getElementById("customDataTable");
        if (customDataTable) {
          tableScrollWidth.value = customDataTable.scrollWidth;
        }
      })
      .catch((error) => {
        console.error("Error fetching Quotations:", error);
      });
  });
  showWorkCs(WCSID);
});

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

setTitle('Work Quotations');


let filterOptions = ref({
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "quotation_ref_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Ref No",
      "filter_type": "input" 
    },
    {
      "relation_name": null,
      "field_name": "effective_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Date",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "expire_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Date",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "purchase_center",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Warehouse",
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



// Code for global search starts here
const currentPage = ref(1);
const paginatedPage = ref(1);

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);


// onMounted(() => {
//   watchPostEffect(() => {
//     // if(currentPage.value == props.page && currentPage.value != 1) {
//     //   filterOptions.value.page = 1;
//     //   router.push({ name: 'scm.material-cs.index', query: { page: filterOptions.value.page } });
//     // } else {
//     //   filterOptions.value.page = props.page;
//     // }
//     // currentPage.value = props.page;
//     // if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
//     //   filterOptions.value.isFilter = true;
//     // }
//     getQuotation(filterOptions.value)
//       .then(() => {
//         // paginatedPage.value = filterOptions.value.page;
//       const customDataTable = document.getElementById("customDataTable");
//       if (customDataTable) {
//         tableScrollWidth.value = customDataTable.scrollWidth;
//       }
//     })
//     .catch((error) => {
//       console.error("Error fetching SR:", error);
//     });
// });

// });
// Code for global search end here



// const navigateToMRRCreate = (materialCsId) => {
//   const pr_id = materialCsId; 
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


function confirmDelete(wcs_id, id) {
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
            deleteWcsQuotations(wcs_id,id);
          }
        })
      }
</script>

<template>
  <!-- Heading -->
 
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Quotations List</h2>
    <div class="flex gap-3">
      <default-button :title="'WCS List'" :to="{ name: 'scm.work-cs.index' }" :icon="icons.DataBase"></default-button>
      <default-button v-if="!workCs?.scmWcsVendors?.find(scmWcsVendor => scmWcsVendor.is_selected == 1)" :title="'Create Quotation'" :to="{ name: 'scm.wcs-quotations.create', params: { wcsId: WCSID }  }" :icon="icons.AddIcon"></default-button>
    </div>

  </div>
  <!-- Table -->
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div class="input-group mb-10 mt-5">
      <label class="label-group">
          <span class="label-item-title">CS Ref<span class="text-red-500"> : </span></span>
          <span class="label-item-title">{{ workCs?.ref_no }}</span>  
      </label>
      <label class="label-group">
          <span class="label-item-title">Warehouse<span class="text-red-500"> : </span></span>
          <span class="label-item-title">{{ workCs.scmWarehouse?.name }}</span>
      </label>
      <label class="label-group">
          <span class="label-item-title">Effective Date<span class="text-red-500"> : </span></span>
          <!-- <td>{{ workCs }}</td> -->
          <span class="label-item-title">{{ formatDate(workCs.effective_date) }}</span>
      </label>
      <label class="label-group">
          <span class="label-item-title">Selection Criteria<span class="text-red-500"> : </span></span>
          <span class="label-item-title">{{ workCs.selection_ground }}</span>
      </label>
    </div>
  </div>
 
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
          <thead v-once>
          <tr class="w-full">
            <th>#</th>
            <th>Vendor Name</th>
            <th>Contact Person</th>
            <th>Contact</th>
            <!-- <th>Origin</th> -->
            <th>Quotation No</th>
            <th>Quotation Date</th>
            <th>Action</th>
          </tr>
          </thead>
          <!-- <FilterComponent :filterOptions = "filterOptions"/> -->
          <tbody>
            <tr v-for="(wcsQuotation,index) in (wcsQuotations?.data ? wcsQuotations?.data : wcsQuotations)" :key="index">
              <td>{{ index + 1 }}</td>
              <td>{{ wcsQuotation?.scmVendor?.name }}</td>
              <td>{{ wcsQuotation?.scmVendor?.scmVendorContactPerson?.name }}</td>
              <td>{{ wcsQuotation?.scmVendor?.scmVendorContactPerson?.phone }}</td>
              <!-- <td>{{ quotation?.scmVendor?.country_name }}</td> -->
              <td>{{ wcsQuotation?.quotation_ref_no }}</td>
              <td>{{ formatDate(wcsQuotation?.quotation_date) }}</td>
              <td>
                <div class="">                 
                  <action-button :action="'edit'" :to="{ name: 'scm.wcs-quotations.edit', params: { wcsId: wcsQuotation.scm_wcs_id, wcsQuotationId: wcsQuotation.id } }"></action-button>
                  <action-button @click="confirmDelete(wcsQuotation.scm_wcs_id, wcsQuotation.id)" :action="'delete'" v-if="!wcsQuotation.is_selected"></action-button>
                </div>
              </td>
            </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && wcsQuotations?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!wcsQuotations?.length" class="relative h-[250px]">
              <tr v-if="isLoading">
              </tr>
              <tr v-else-if="isTableLoading">
                  <td colspan="7">
                    <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                  </td>
              </tr>
              <tr v-else-if="!wcsQuotations?.length">
                <td colspan="7">No Data found.</td>
              </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="wcsQuotations" to="scm.wcs-quotations-cs.index" :page="page"></Paginate>
  </div>
  </div>
 
  <!-- Heading -->
  
  

  <!-- <ErrorComponent :errors="errors"></ErrorComponent>   -->
</template>