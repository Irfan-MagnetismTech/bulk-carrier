<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import useBulkNoonReport from '../../../composables/operations/useBulkNoonReport';
import Store from "../../../store";
import moment from 'moment';

const { bulkNoonReports, getBulkNoonReports, deleteBulkNoonReport, isLoading } = useBulkNoonReport();
const icons = useHeroIcon();
const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Bulk Noon Report List');

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
        deleteBulkNoonReport(id);
    }
  })
}

onMounted(() => {
  watchEffect(() => {
    getBulkNoonReports(props.page, businessUnit.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Bulk Noon Report List</h2>
    <default-button :title="'Create Bulk Noon Report'" :to="{ name: 'ops.bulk-noon-reports.create' }" :icon="icons.AddIcon"></default-button>
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
            <th>Action</th>
          </tr>
          </thead>
          <tbody v-if="bulkNoonReports?.data?.length">
              <tr v-for="(bulkNoonReport, index) in bulkNoonReports.data" :key="bulkNoonReport?.id">
                  <td>{{ bulkNoonReports.from + index }}</td>
                  <td>
                    <nobr>{{ bulkNoonReport?.date ? moment(bulkNoonReport?.date).format('DD-MM-YYYY hh:mm A') : null }}</nobr>
                  </td>
                  <td>{{ bulkNoonReport?.opsVessel?.name }}</td>
                  <td>{{ bulkNoonReport?.opsVoyage?.voyage_no }}</td>
                  <td>{{ bulkNoonReport?.opsVoyage?.opsCargoType?.cargo_type }}</td>
                  <td><nobr>{{ bulkNoonReport?.last_port }}</nobr></td>
                  <td><nobr>{{ bulkNoonReport?.next_port }}</nobr></td>
                  <td><nobr>{{ bulkNoonReport?.lat_long }}</nobr></td>
                  <td><nobr>{{ bulkNoonReport?.ship_master }}</nobr></td>
                  <td><nobr>{{ bulkNoonReport?.chief_engineer }}</nobr></td>
                  <td class="items-center justify-center space-x-1 text-gray-600">
                      <nobr>
                        <action-button :action="'copy'" :to="{ name: 'ops.bulk-noon-reports.copy', params: { bulkNoonReportId: bulkNoonReport.id } }"></action-button>
                        <action-button :action="'show'" :to="{ name: 'ops.bulk-noon-reports.show', params: { bulkNoonReportId: bulkNoonReport.id } }"></action-button>
                        <action-button :action="'edit'" :to="{ name: 'ops.bulk-noon-reports.edit', params: { bulkNoonReportId: bulkNoonReport.id } }"></action-button>
                        <action-button @click="confirmDelete(bulkNoonReport.id)" :action="'delete'"></action-button>
                      </nobr>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
              </tr>
          </tbody>
          
          <tfoot v-if="!bulkNoonReports?.length">
          <tr v-if="isLoading">
            <td colspan="11">Loading...</td>
          </tr>
          <tr v-else-if="!bulkNoonReports?.data?.length">
            <td colspan="11">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="bulkNoonReports" to="ops.bulk-noon-reports.index" :page="page"></Paginate>
  </div>
</template>
