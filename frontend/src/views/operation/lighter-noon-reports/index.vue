<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import useLighterNoonReport from '../../../composables/operations/useLighterNoonReport';
import Store from "../../../store";
import moment from 'moment';

const { lighterNoonReports, getLighterNoonReports, deleteLighterNoonReport, isLoading } = useLighterNoonReport();
const icons = useHeroIcon();
const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Lighter Noon Report List');

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
        deleteLighterNoonReport(id);
    }
  })
}

onMounted(() => {
  watchEffect(() => {
    getLighterNoonReports(props.page, businessUnit.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Lighter Noon Report List</h2>
    <default-button :title="'Create Lighter Noon Report'" :to="{ name: 'ops.lighter-noon-reports.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <filter-with-business-unit v-model="businessUnit"></filter-with-business-unit>

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
            <th>Date and Time</th>
            <th>Vessel</th>
            <th>Voyage</th>
            <th>Cargo Type</th>
            <th>Last Port</th>
            <th>Next Port</th>
            <th>Lat / Long</th>
            <th>Master</th>
            <th>Chief Engineer</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody v-if="lighterNoonReports?.data?.length">
              <tr v-for="(lighterNoonReport, index) in lighterNoonReports.data" :key="lighterNoonReport?.id">
                  <td>{{ lighterNoonReports.from + index }}</td>
                  <td>
                    <nobr>{{ lighterNoonReport?.date ? moment(lighterNoonReport?.date).format('DD-MM-YYYY hh:mm A') : null }}</nobr>
                  </td>
                  <td>{{ lighterNoonReport?.opsVessel?.name }}</td>
                  <td>{{ lighterNoonReport?.opsVoyage?.voyage_no }}</td>
                  <td>{{ lighterNoonReport?.opsVoyage?.opsCargoType?.cargo_type }}</td>
                  <td><nobr>{{ lighterNoonReport?.last_port }}</nobr></td>
                  <td><nobr>{{ lighterNoonReport?.next_port }}</nobr></td>
                  <td><nobr>{{ lighterNoonReport?.lat_long }}</nobr></td>
                  <td><nobr>{{ lighterNoonReport?.ship_master }}</nobr></td>
                  <td><nobr>{{ lighterNoonReport?.chief_engineer }}</nobr></td>
                  <td class="items-center justify-center space-x-2 text-gray-600">
                      <nobr>
                        <action-button :action="'copy'" :to="{ name: 'ops.lighter-noon-reports.copy', params: { lighterNoonReportId: lighterNoonReport.id } }"></action-button>
                        <action-button :action="'show'" :to="{ name: 'ops.lighter-noon-reports.show', params: { lighterNoonReportId: lighterNoonReport.id } }"></action-button>
                        <action-button :action="'edit'" :to="{ name: 'ops.lighter-noon-reports.edit', params: { lighterNoonReportId: lighterNoonReport.id } }"></action-button>
                        <action-button @click="confirmDelete(lighterNoonReport.id)" :action="'delete'"></action-button>
                      </nobr>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
              </tr>
          </tbody>
          
          <tfoot v-if="!lighterNoonReports?.length">
          <tr v-if="isLoading">
            <td colspan="8">Loading...</td>
          </tr>
          <tr v-else-if="!lighterNoonReports?.data?.length">
            <td colspan="8">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="lighterNoonReports" to="ops.lighter-noon-reports.index" :page="page"></Paginate>
  </div>
</template>
