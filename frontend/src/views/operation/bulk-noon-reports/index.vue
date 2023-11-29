<script setup>
import {onMounted, ref, watchPostEffect} from "vue";
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
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";

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

let filterOptions = ref({
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
       "label": "Date",
       "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "ref_no",
      "search_param": "",
       "action": null,
       "order_by": null,
       "date_from": null,
       "label": "Vessel",
       "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "ref_no",
      "search_param": "",
       "action": null,
       "order_by": null,
       "date_from": null,
       "label": "Voyage",
       "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "ref_no",
      "search_param": "",
       "action": null,
       "order_by": null,
       "date_from": null,
       "label": "Latitude",
       "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "Master",
      "search_param": "",
       "action": null,
       "order_by": null,
       "date_from": null,
       "label": "Master",
       "filter_type": "input"
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
        router.push({ name: 'ops.bulk-noon-reports.index', query: { page: filterOptions.value.page } });
      } else {
        filterOptions.value.page = props.page;
      }
      
      currentPage.value = props.page;
      
      if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
        filterOptions.value.isFilter = true;
      }

      getBulkNoonReports(filterOptions.value)
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
        <FilterComponent :filterOptions = "filterOptions"/>
          <tbody v-if="bulkNoonReports?.data?.length" class="relative">
              <tr v-for="(bulkNoonReport, index) in bulkNoonReports.data" :key="bulkNoonReport?.id">
                  <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>

                  <td>
                    <nobr>{{ bulkNoonReport?.date_time ? moment(bulkNoonReport?.date_time).format('DD-MM-YYYY hh:mm A') : null }}</nobr>
                  </td>
                  <td>{{ bulkNoonReport?.opsVessel?.name }}</td>
                  <td>{{ bulkNoonReport?.opsVoyage?.voyage_no }}</td>
                  <td><nobr>{{ bulkNoonReport?.latitude }}</nobr></td>
                  <td><nobr>{{ bulkNoonReport?.ship_master }}</nobr></td>
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
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && bulkNoonReports?.data?.length"></LoaderComponent>

          </tbody>
          
          <tfoot v-if="!bulkNoonReports?.data?.length"  class="relative h-[250px]">
          <tr v-if="isLoading">
            <td colspan="7">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="7">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
          </tr>
          <tr v-else-if="!bulkNoonReports?.data?.length">
            <td colspan="7">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="bulkNoonReports" to="ops.bulk-noon-reports.index" :page="page"></Paginate>
  </div>
</template>
