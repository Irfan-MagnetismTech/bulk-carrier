<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import useVesselCertificate from '../../../composables/operations/useVesselCertificate';
import env from '../../../config/env';
import Store from './../../../store/index.js';
import moment from 'moment';
import { useRoute } from 'vue-router';
import { formatDate } from "../../../utils/helper";
import FileExportButton from "../../../components/buttons/FileExportButton.vue";

const icons = useHeroIcon();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);
const { vesselCertificates, getVesselCertificateHistory, deleteVesselCertificate, isLoading } = useVesselCertificate();
const route = useRoute();

const vesselId = route.params.vesselId;
const certificateId = route.params.certificateId;

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
const rightAlign = [];
const leftAlign = [1,2];
function setBusinessUnit($el){
  businessUnit.value = $el.target.value;
}

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


onMounted(() => {
  watchEffect(() => {
    getVesselCertificateHistory(vesselId, certificateId)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching data.", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Vessel Certificate History</h2>
    <div class="flex gap-2">
      <default-button :title="'Vessel Certificate List'" :to="{ name: 'ops.vessel-certificates.index' }" :icon="icons.DataBase"></default-button>
      <file-export-button
        :businessUnit="businessUnit"
        :pageOrientation="'l'"
        :fileName="'Vessel Cerificate History List'"
        :tableId="'certificate-history-list'"
        :leftAlign="leftAlign"
        :rightAlign="rightAlign"
      ></file-export-button>
    </div>
  </div>
  <!-- <div class="flex items-center justify-between mb-2 select-none">

    <div class="relative w-full">
      <select @change="setBusinessUnit($event)" class="form-control business_filter_input border-transparent focus:ring-0"
      :disabled="defaultBusinessUnit === 'TSLL' || defaultBusinessUnit === 'PSML'"
      >
        <option value="ALL" :selected="businessUnit === 'ALL'">ALL</option>
        <option value="PSML" :selected="businessUnit === 'PSML'">PSML</option>
        <option value="TSLL" :selected="businessUnit === 'TSLL'">TSLL</option>
      </select>
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
      
      <table class="w-full whitespace-no-wrap" id="certificate-history-list">
          <thead v-once>
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
            <th>Action</th>
          </tr>
          </thead>
          <tbody v-if="vesselCertificates?.opsVesselCertificates?.length">
            <tr v-for="(item, itemIndex) in vesselCertificates.opsVesselCertificates">
                <td class="text-center">{{ itemIndex+1 }}</td>
                <td :rowspan="vesselCertificates.opsVesselCertificates.length" v-if="itemIndex == 0">{{ vesselCertificates?.name }}</td>
                <td>
                  {{ item?.opsMaritimeCertification?.name }}
                </td>
                <td>
                    <span v-if="item?.opsMaritimeCertification?.validity=='0'">Permanent</span>  
                    <span v-if="item?.opsMaritimeCertification?.validity=='3'">3 Months</span>  
                    <span v-if="item?.opsMaritimeCertification?.validity=='6'">6 Months</span>  
                    <span v-if="item?.opsMaritimeCertification?.validity=='12'">1 Years</span>  
                    <span v-if="item?.opsMaritimeCertification?.validity=='24'">2 Years</span>  
                    <span v-if="item?.opsMaritimeCertification?.validity=='36'">3 Years</span>  
                    <span v-if="item?.opsMaritimeCertification?.validity=='48'">4 Years</span>  
                    <span v-if="item?.opsMaritimeCertification?.validity=='60'">5 Years</span>  
                    <span v-if="item?.opsMaritimeCertification?.validity=='120'">10 Years</span>  
                </td>
                <td>
                  <nobr>{{ formatDate(item?.issue_date) }}</nobr>
                </td>
                <td>
                  <nobr>{{ formatDate(item?.expire_date) }}</nobr>
                </td>
                <td>
                  {{ (item?.expire_days < 0) ? 'Expired' : (item?.expire_days == 0 ? null : item?.expire_days) }}

                </td>
                <td>
                  {{ item?.reference_number }}
                </td>
                <td>
                  <div class="w-full text-center" v-if="item?.attachment">
                    <a :href="env.BASE_API_URL+item?.attachment" target="_blank" rel="noopener noreferrer">
                      <img :src="env.BASE_API_URL+item?.attachment"  alt="" srcset="" class="w-12 mx-auto">
                    </a>
                  </div>
                </td>
                <td class="items-center text-center justify-center space-x-2 text-gray-600">
                   <nobr>
                    <action-button :action="'edit'" :to="{ name: 'ops.vessel-certificates.edit', params: { vesselCertificateId: item?.id } }"></action-button>
                    <action-button @click="confirmDelete(vessel.id)" :action="'delete'"></action-button>
                   </nobr>
                </td>
              </tr>
          </tbody>
          
          <tfoot v-if="!vesselCertificates?.opsVesselCertificates?.length">
          <tr v-if="isLoading">
          </tr>
          <tr v-else-if="!vesselCertificates?.opsVesselCertificates?.length">
            <td colspan="14">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="vesselCertificates" to="ops.vessel-certificates.index" :page="page"></Paginate>
  </div>
</template>
<style>
  table > tbody> tr > td {
      text-align: left;
  }
</style>