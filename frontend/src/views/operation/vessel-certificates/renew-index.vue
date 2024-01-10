<script setup>
import {onMounted, ref, watchEffect, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import useVesselCertificate from '../../../composables/operations/useVesselCertificate';
import Store from './../../../store/index.js';
import moment from 'moment';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import { formatDate } from "../../../utils/helper";

import FilterComponent from "../../../components/utils/FilterComponent.vue";
const router = useRouter();
const debouncedValue = useDebouncedRef('', 800);

// const filterDays = ref(60);
// const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);

const { vesselCertificates, getRenewableVesselCertificates, deleteVesselCertificate, isLoading, isTableLoading, errors } = useVesselCertificate();
const icons = useHeroIcon();
const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Custom Renew Schedule List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

// function setBusinessUnit($el){
//   businessUnit.value = $el.target.value;
// }

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
        deleteVesselCertificate(id);
    }
  })
}


watch(
    () => businessUnit.value,
    (newBusinessUnit, oldBusinessUnit) => {
      if (newBusinessUnit !== oldBusinessUnit) {
        router.push({ name: "ops.vessel-certificates.index", query: { page: 1 } })
      }
    }
);


let filterOptions = ref( {
  "business_unit": null,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
  
    {
      "rel_type": null,
      "relation_name": "opsVessel",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Vessel",
      "filter_type": "input"
    },
    
    {
      "rel_type": null,
      "relation_name": "opsMaritimeCertification",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Certificate Name",
      "filter_type": "input"
    },

    
    {
      "rel_type": null,
      "relation_name": "opsMaritimeCertification",
      "field_name": "validity",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Validity Period",
      "filter_type": "input"
    },

    
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": "issue_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Issue Date",
      "filter_type": "input"
    },

    
    {
      "rel_type": null,
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
      "rel_type": null,
      "relation_name": null,
      "field_name": null,
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": 'Left Days',
      "filter_type": null
    },
  
  ]
});
let stringifiedFilterOptions = JSON.stringify(filterOptions.value);
const currentPage = ref(1);
const paginatedPage = ref(1);

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'ops.vessel-certificates.renew-list', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getRenewableVesselCertificates(filterOptions.value)
    .then(() => {
      paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching data.", error);
    });
  });  
  filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = useDebouncedRef('', 800);
  });
});

function getFilteredResult() {
  getRenewableVesselCertificates(props.page, businessUnit.value, filterDays.value)
}
</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Custom Renew Schedule List</h2>
  </div>
  <!-- <div class="flex items-center justify-between mb-2 select-none ">
    
    <div class="relative w-full">
      <select @change="setBusinessUnit($event)" class="form-control business_filter_input border-transparent focus:ring-0"
      :disabled="defaultBusinessUnit === 'TSLL' || defaultBusinessUnit === 'PSML'"
      >
        <option value="ALL" :selected="businessUnit === 'ALL'">ALL</option>
        <option value="PSML" :selected="businessUnit === 'PSML'">PSML</option>
        <option value="TSLL" :selected="businessUnit === 'TSLL'">TSLL</option>
      </select>

      
    </div>
    <div class="relative w-full flex justify-center">
      <label class="block text-sm mb-2 w-28">
        <input type="number" v-model="filterDays" placeholder="Filter Days" class="form-input" autocomplete="off" />
        <Error v-if="filterDays" :errors="filterDays" />
      </label>
      <button type="button" @click="getFilteredResult()" :disabled="isLoading" class="w-28 mt-1 flex items-center justify-center text-sm text-white bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple mx-3 mb-2">Filter</button>

    </div>
    <div class="relative w-full">
      
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
      </svg>
      <input type="text" placeholder="Search..." class="search" />
    </div>
  </div> -->

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
         <!-- <thead v-once>
           <tr class="w-full">
            <th>#</th>
            <th>Vessel Name</th>
            <th>Certificate Name</th>
            <th>Validity Period</th>
            <th>Issue Date</th>
            <th>Expire Date</th>
            <th>Left Days</th>
            <th>Action</th>
          </tr>
          </thead> -->
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody v-if="Object.keys(vesselCertificates).length" class="relative">
            
            <template v-for="(certificates, key, index) in vesselCertificates">
              <tr v-for="(item, itemIndex) in certificates">
                <td>{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
                <td >{{ item?.opsVessel?.name }}</td>
                <!-- <td :rowspan="certificates.length" v-if="itemIndex == 0">{{ index+1 }}</td>
                <td :rowspan="certificates.length" v-if="itemIndex == 0">{{ certificates[0].opsVessel?.name }}</td> -->
                <td>
                  {{ item?.opsMaritimeCertification?.name }}
                </td>
                <td>
                  {{ item?.opsMaritimeCertification?.validity }}
                </td>
                <td>
                  {{ formatDate(item?.issue_date) }}
                </td>
                <td>
                  {{ formatDate(item?.expire_date) }}
                </td>
                <td>
                  {{ (item?.expire_days < 0) ? 'Expired' : item?.expire_days }}
                </td>
                <td>
                  <button type="button" class="bg-blue-500 hover:bg-blue-700 duration-150 text-white p-1 text-xs rounded-md">
                    <router-link :to="{ name: 'ops.vessel-certificates.renew', params: { vesselId: certificates[0].opsVessel?.id, marineCertificateId: item.opsMaritimeCertification.id } }" >
                    Renew
                    </router-link>
                  </button>
                </td>
              </tr>
              <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && Object.keys(vesselCertificates).length"></LoaderComponent>
            </template>
              
          </tbody>
          
          <tfoot v-if="!Object.keys(vesselCertificates)?.length"  class="relative h-[250px]">
          <tr v-if="isLoading">
            <td colspan="14">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="8">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
          </tr>
          <tr v-else-if="!Object.keys(vesselCertificates)?.length">
            <td colspan="14">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="vesselCertificates" to="ops.vessel-certificates.index" :page="page"></Paginate>
  </div>
</template>
