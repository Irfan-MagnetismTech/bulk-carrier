<script setup>
import {onMounted, ref, watchEffect, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import useMaritimeCertificate from '../../../composables/operations/useMaritimeCertificate';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import FileExportButton from "../../../components/buttons/FileExportButton.vue";

const router = useRouter();
const debouncedValue = useDebouncedRef('', 800);

const { maritimeCertificates, getMaritimeCertificates, deleteMaritimeCertificate, isLoading , isTableLoading, errors} = useMaritimeCertificate();
const icons = useHeroIcon();
const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Maritime Certificate List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const rightAlign = [];
const leftAlign = [1,2];

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
        deleteMaritimeCertificate(id);
    }
  })
}

watch(
    () => businessUnit.value,
    (newBusinessUnit, oldBusinessUnit) => {
      if (newBusinessUnit !== oldBusinessUnit) {
        router.push({ name: "ops.contract-assigns.index", query: { page: 1 } })
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
      "relation_name": null,
      "field_name": "authority",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Certificate Authority",
      "filter_type": "input"
    },    
    {
      "rel_type": null,
      "relation_name": null,
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
      "relation_name": null,
      "field_name": "type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Certificate Type",
      "filter_type": "input"
    },    
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": "validity",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Validity Period",
      "filter_type": "dropdown",
      "select_options": [
          { value: "", label: "All" ,defaultSelected: true},
          { value: "3", label: "3 Months" ,defaultSelected: false},
          { value: "6", label: "6 Months" ,defaultSelected: false},
          { value: "12", label: "1 Year" ,defaultSelected: false},
          { value: "24", label: "2 Years" ,defaultSelected: false},
          { value: "36", label: "3 Years" ,defaultSelected: false},
          { value: "48", label: "4 Years" ,defaultSelected: false},
          { value: "60", label: "5 Years" ,defaultSelected: false},
          { value: "120", label: "10 Years" ,defaultSelected: false},
          { value: "0", label: "Permanent" ,defaultSelected: false},
         
        ]
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
      router.push({ name: 'ops.maritime-certifications.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getMaritimeCertificates(filterOptions.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Maritime Certificate List</h2>
    <div class="flex gap-2">
      <default-button :title="'Create Maritime Certificate'" :to="{ name: 'ops.maritime-certifications.create' }" :icon="icons.AddIcon"></default-button>
      <file-export-button
        :businessUnit="businessUnit"
        :pageOrientation="'l'"
        :fileName="'Maritime Certificates List'"
        :tableId="'maritime-certificate-list'"
        :leftAlign="leftAlign"
        :rightAlign="rightAlign"
      ></file-export-button>
    </div>
  </div>
  <!-- <div class="flex items-center justify-between mb-2 select-none">

    <div class="relative w-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
      </svg>
      <input type="text" placeholder="Search..." class="search" />
    </div>
  </div> -->

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" id="maritime-certificate-list">
          <!-- <thead v-once>
          <tr class="w-full">
            <th>#</th>
            <th>Certificate Authority</th>
            <th>Certificate Name</th>
            <th>Certificate Type</th>
            <th>Validity Period</th>
            <th>Action</th>
          </tr>
          </thead> -->
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody v-if="maritimeCertificates?.data?.length" class="relative">
              <tr v-for="(maritimeCertificate, index) in maritimeCertificates.data" :key="maritimeCertificate?.id">
                  <td class="text-center">{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
                  <td>{{ maritimeCertificate?.authority }}</td>
                  <td>{{ maritimeCertificate?.name }}</td>
                  <td>{{ maritimeCertificate?.type }}</td>
                  <td>
                    <span v-if="maritimeCertificate?.validity=='0'">Permanent</span>  
                    <span v-if="maritimeCertificate?.validity=='3'">3 Months</span>  
                    <span v-if="maritimeCertificate?.validity=='6'">6 Months</span>  
                    <span v-if="maritimeCertificate?.validity=='12'">1 Years</span>  
                    <span v-if="maritimeCertificate?.validity=='24'">2 Years</span>  
                    <span v-if="maritimeCertificate?.validity=='36'">3 Years</span>  
                    <span v-if="maritimeCertificate?.validity=='48'">4 Years</span>  
                    <span v-if="maritimeCertificate?.validity=='60'">5 Years</span>  
                    <span v-if="maritimeCertificate?.validity=='120'">10 Years</span>  
                  </td>
                  <td class="items-center text-center justify-center space-x-1 text-gray-600">
                    <nobr>
                      <action-button :action="'edit'" :to="{ name: 'ops.maritime-certifications.edit', params: { maritimeCertificateId: maritimeCertificate.id } }"></action-button>
                      <action-button @click="confirmDelete(maritimeCertificate.id)" :action="'delete'"></action-button>
                    </nobr>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
              </tr>
              <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && maritimeCertificates?.data?.length"></LoaderComponent>
          </tbody>
          
          <tfoot v-if="!maritimeCertificates?.data?.length" class="relative h-[250px]">
            <tr v-if="isLoading">
            </tr>
            <tr v-else-if="isTableLoading">
                <td colspan="8">
                  <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                </td>
            </tr>
            <tr v-else-if="!maritimeCertificates?.data?.length">
              <td colspan="6">No data found.</td>
            </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="maritimeCertificates" to="ops.configurations.maritime-certificates.index" :page="page"></Paginate>
  </div>
</template>
<style>
  table > tbody> tr > td {
      text-align: left;
  }
</style>