<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
const icons = useHeroIcon();
import useVesselCertificate from '../../../composables/operations/useVesselCertificate';
const { vesselCertificates, getVesselCertificateHistory, deleteVesselCertificate, isLoading } = useVesselCertificate();
import Store from './../../../store/index.js';
import moment from 'moment';
import { useRoute } from 'vue-router';
const route = useRoute();


const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);
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
    <default-button :title="'Vessel Certificate List'" :to="{ name: 'ops.vessel-certificates.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <!-- Search -->
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
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
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
                <td>{{ itemIndex+1 }}</td>
                <td :rowspan="vesselCertificates.opsVesselCertificates.length" v-if="itemIndex == 0">{{ vesselCertificates?.name }}</td>
                <td>
                  {{ item?.opsMaritimeCertification?.name }}
                </td>
                <td>
                  {{ item?.opsMaritimeCertification?.validity }}
                </td>
                <td>
                  <nobr>{{ item?.issue_date ? moment(item?.issue_date).format('DD-MM-YYYY') : null }}</nobr>
                </td>
                <td>
                  <nobr>{{ item?.expire_date ? moment(item?.expire_date).format('DD-MM-YYYY') : null }}</nobr>
                </td>
                <td></td>
                <td>
                  {{ item?.reference_number }}
                </td>
                <td>
                  {{ item?.attachment }}
                </td>
                <td class="items-center justify-center space-x-2 text-gray-600">
                    <action-button :action="'edit'" :to="{ name: 'ops.vessel-certificates.edit', params: { vesselCertificateId: item?.id } }"></action-button>
                    <action-button @click="confirmDelete(vessel.id)" :action="'delete'"></action-button>

                </td>
              </tr>
          </tbody>
          
          <tfoot v-if="!vesselCertificates?.opsVesselCertificates?.length">
          <tr v-if="isLoading">
            <td colspan="14">Loading...</td>
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
