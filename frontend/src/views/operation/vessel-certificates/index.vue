<script setup>
import {onMounted, ref, watchEffect, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import useVesselCertificate from '../../../composables/operations/useVesselCertificate';
import Store from './../../../store/index.js';
import moment from 'moment';
import env from '../../../config/env';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
const router = useRouter();
const debouncedValue = useDebouncedRef('', 800);


const { vesselCertificates, getVesselCertificates, deleteVesselCertificate, isLoading, isTableLoading, errors } = useVesselCertificate();
const icons = useHeroIcon();
const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Vessel Certificate List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

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
      router.push({ name: 'ops.contract-assigns.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getVesselCertificates(filterOptions.value)
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

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Vessel Certificate List</h2>
    <default-button :title="'Create Vessel Certificate'" :to="{ name: 'ops.vessel-certificates.create' }" :icon="icons.AddIcon"></default-button>
  </div>

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
            <th>Reference Number</th>
            <th>Certificate Image</th>
            <th>History</th>
          </tr>
          </thead> -->
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody v-if="Object.keys(vesselCertificates).length" class="relative">
            
            <template v-for="(certificates, key, index) in vesselCertificates">
              <tr v-for="(item, itemIndex) in certificates">
                <td :rowspan="certificates.length" v-if="itemIndex == 0">{{ index+1 }}</td>
                <!-- <td>{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td> -->
                <td :rowspan="certificates.length" v-if="itemIndex == 0">{{ item?.opsVessel?.name }}</td>
                <td>
                  {{ item?.opsMaritimeCertification?.name }}
                </td>
                <td>
                  {{ item?.opsMaritimeCertification?.validity }}
                </td>
                <td>
                  <nobr>{{ item?.expire_date ? moment(item?.expire_date).format('MM-DD-YYYY') : null }}</nobr>
                </td>
                <td>
                  {{ (item?.expire_days < 0) ? 'Expired' : item?.expire_days }}
                </td>
                <!-- <td>
                  <div class="w-full text-center">
                    <a :href="env.BASE_API_URL+item?.attachment" target="_blank" rel="noopener noreferrer">
                      <img :src="env.BASE_API_URL+item?.attachment"  alt="" srcset="" class="w-12 mx-auto">
                    </a>
                  </div>
                </td> -->
                <td>
                  <nobr>
                    <button type="button" class="bg-blue-500 hover:bg-blue-700 duration-150 text-white p-1 text-xs rounded-md">
                    <router-link :to="{ name: 'ops.vessel-certificates.history', params: { vesselId: certificates[0].opsVessel?.id, certificateId: item.opsMaritimeCertification.id } }" >
                    History
                    </router-link>
                  </button>
                  <button type="button" class="bg-blue-500 ml-2 hover:bg-blue-700 duration-150 text-white p-1 text-xs rounded-md">
                    <router-link :to="{ name: 'ops.vessel-certificates.renew', params: { vesselId: certificates[0].opsVessel?.id, marineCertificateId: item.opsMaritimeCertification.id } }" >
                    Renew
                    </router-link>
                  </button>
                  </nobr>
                </td>
              </tr>
              <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && Object.keys(vesselCertificates).length"></LoaderComponent>
            </template>
              
          </tbody>
          
          <tfoot v-if="!Object.keys(vesselCertificates)?.length" class="relative h-[250px]">
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
