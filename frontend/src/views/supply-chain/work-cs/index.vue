<script setup>
import {onMounted, watchEffect,watch,ref, watchPostEffect} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useWorkCs from "../../../composables/supply-chain/useWorkCs";
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
import { formatDate } from '../../../utils/helper';


const { getWorkCs, workCs, workCsLists, deleteWorkCs, isLoading,errors,isTableLoading} = useWorkCs();
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
// Code for global search start

const icons = useHeroIcon();

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

setTitle('Work CS');


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
      "label": "Effective Date",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "expire_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Expire Date",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "supplier",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Supplier",
      "filter_type": "input" 
    },
    // {
    //   "relation_name": null,
    //   "field_name": "selection_ground",
    //   "search_param": "",
    //   "action": null,
    //   "order_by": null,
    //   "date_from": null,
    //   "label": "Selection Ground",
    //   "filter_type": "input"
    // },
    // {
    //   "relation_name": "selectedVendors",
    //   "field_name": "name",
    //   "search_param": "",
    //   "action": null,
    //   "order_by": null,
    //   "date_from": null,
    //   "label": "Selected Vendors",
    //   "filter_type": "input"
    // },
    // {
    //   "relation_name": "scmCsMaterials",
    //   "field_name": "scmMaterial.name",
    //   "search_param": "",
    //   "action": null,
    //   "order_by": null,
    //   "date_from": null,
    //   "label": "Material",
    //   "filter_type": "input"
    // },
    // {
    //   "relation_name": "scmWarehouse",
    //   "field_name": "name",
    //   "search_param": "",
    //   "action": null,
    //   "order_by": null,
    //   "date_from": null,
    //   "label": "Warehouse",
    //   "filter_type": "input" 
    // }
  ]
});



// Code for global search starts here
const currentPage = ref(1);
const paginatedPage = ref(1);

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);


onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'scm.work-cs.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getWorkCs(filterOptions.value)
      .then(() => {
        paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");
      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching work cs:", error);
    });
});

});
// Code for global search end here

const navigateToQuotation = (wcsId) => {
  const routeOptions = {
    name: 'scm.wcs-quotations.index',
    params: {
      wcsId: wcsId
    }
  };
  router.push(routeOptions);
};  

const navigateSupplierSelection = (csId) => {
  const routeOptions = {
    name: 'scm.supplier-selection',
    params: {
      csId: csId
    }
    // query: {
    //   csId: csId
    // }
  };
  router.push(routeOptions);
};

const navigateToPOCreate = (csId) => {
  const pr_id = null; 
  const cs_id = csId;
  const routeOptions = {
    name: 'scm.purchase-orders.create',
    query: {
      pr_id: null,
      cs_id: cs_id
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
            deleteWorkCs(id);
          }
        })
      }
</script>

<template>
  <!-- Heading -->
 
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Work CS List</h2>
    <default-button :title="'Create CS'" :to="{ name: 'scm.work-cs.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <!-- Table -->
  <div id="customDataTable">
    <div class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
          <!-- <thead v-once>
          <tr class="w-full">
            <th>#</th>
            <th>Ref No</th>
            <th>Date</th>
            <th>Warehouse</th>
            <th>Department</th>
            <th>Business Unit</th>
            <th>Action</th>
          </tr>
          </thead> -->
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody>
            <tr v-for="(workCs,index) in workCsLists?.data" :key="index">
              <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
              <td><nobr>{{ workCs?.ref_no }}</nobr></td>
              <td><nobr>{{ formatDate(workCs?.effective_date) }}</nobr></td>
              <td><nobr>{{ formatDate(workCs?.expire_date) }}</nobr></td>
              <td>
                <template v-if="workCs?.selectedSuppliers?.length">
                  <!-- <ul v-for="(vendor,index) in materialCsdata?.selectedVendors" :key="index">
                    <li>{{ vendor.scmVendor.name }}</li>
                  </ul> -->
                </template>
                <template v-else>
                  <span class="text-red-500">No Supplier Selected</span>
                </template>
              </td>

              <td><span :class="workCs?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ workCs?.business_unit }}</span></td>

              
              <td>
                <div class="flex items-center justify-center gap-2">
                  <!-- <button @click="navigateToPOCreate(materialCsdata.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700"><nobr>Create PO</nobr></button> -->
                  <button @click="navigateToQuotation(workCs.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700"><nobr>Quotations</nobr></button>
                  <!-- <button @click="navigateSupplierSelection(materialCsdata.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700" v-if="materialCsdata?.scmCsVendors?.length"><nobr>Select Supplier</nobr></button> -->
                  <action-button :action="'edit'" :to="{ name: 'scm.work-cs.edit', params: { workCsId: workCs.id } }"></action-button>
                  <action-button @click="confirmDelete(workCs.id)" :action="'delete'"></action-button>
                </div>
              </td>
            </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && workCsLists?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!workCsLists?.data?.length" class="relative h-[250px]">
              <tr v-if="isLoading">
              </tr>
              <tr v-else-if="isTableLoading">
                  <td colspan="7">
                    <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                  </td>
              </tr>
              <tr v-else-if="!workCsLists?.data?.length">
                <td colspan="7">No Data found.</td>
              </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="workCsLists" to="scm.work-cs.index" :page="page"></Paginate>
  </div>
  <!-- Heading -->
  
  

  <ErrorComponent :errors="errors"></ErrorComponent>  
</template>