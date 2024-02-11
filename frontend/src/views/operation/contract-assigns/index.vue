<script setup>
import {onMounted, ref, watchEffect, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import useContractAssign from '../../../composables/operations/useContractAssign';
import Store from "../../../store";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
const router = useRouter();
const debouncedValue = useDebouncedRef('', 800);


const { contractAssigns, getContractAssigns, deleteContractAssign, isLoading, isTableLoading, errors } = useContractAssign();
const icons = useHeroIcon();
const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Contract Assign List');


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
        deleteContractAssign(id);
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
  "business_unit": businessUnit.value,
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
      "label": "Vessel Name",
      "filter_type": "input"
    },
    
    {
      "rel_type": null,
      "relation_name": "opsVoyage",
      "field_name": "voyage_sequence",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Voyage No",
      "filter_type": "input"
    },

    
    {
      "rel_type": null,
      "relation_name": "opsCustomer",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Customer Name",
      "filter_type": "input"
    },

    
    {
      "rel_type": null,
      "relation_name": "opsChartererProfile",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Charterer Name",
      "filter_type": "input"
    },

    
    {
      "rel_type": null,
      "relation_name": "opsChartererContract",
      "field_name": "contract_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Contract Name",
      "filter_type": "input"
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
    getContractAssigns(filterOptions.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Contract Assign List</h2>
    <default-button :title="'Contract Assign'" :to="{ name: 'ops.contract-assigns.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <!-- <div class="flex items-center justify-between mb-2 select-none">
    <filter-with-business-unit v-model="businessUnit"></filter-with-business-unit>

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
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody v-if="contractAssigns?.data?.length" class="relative">
              <tr v-for="(contractAssign, index) in contractAssigns.data" :key="contractAssign?.id">
                  <td class="text-center">{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
                  <td>{{ contractAssign?.opsVessel?.name }}</td>
                  <td>{{ contractAssign?.opsVoyage?.voyage_sequence }}</td>
                  <td>{{ contractAssign?.opsCustomer?.name }}</td>
                  <td>{{ contractAssign?.opsChartererProfile?.name }}</td>
                  <td>{{ contractAssign?.opsChartererContract?.contract_name }}</td>
                  <td class="text-center">
                    <span :class="contractAssign?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ contractAssign?.business_unit }}</span>
                  </td>
                   <!-- <td>{{ contractAssign?.opsVessel?.name }}</td>
                  <td>{{ contractAssign?.opsVoyage?.voyage_no }}</td>
                  <td>{{ contractAssign?.opsVessel?.capacity }}</td>
                  <td>
                    {{ 
                  contractAssign?.opsVoyage?.opsVoyageSectors.reduce((accumulator, currentObject) => {
  return accumulator + currentObject.initial_survey_qty
}, 0)
                  }}
                  </td>
                  <td>
                    {{ 
                  contractAssign?.opsVoyage?.opsVoyageSectors.reduce((accumulator, currentObject) => {
  return accumulator + currentObject.final_received_qty
}, 0)
                  }}  
                  </td> -->
                  <td class="items-center text-center justify-center space-x-1 text-gray-600">
                    <nobr>
                      <action-button :action="'show'" :to="{ name: 'ops.contract-assigns.show', params: { contractAssignId: contractAssign.id } }"></action-button>
                      <action-button :action="'edit'" :to="{ name: 'ops.contract-assigns.edit', params: { contractAssignId: contractAssign.id } }"></action-button>
                      <action-button @click="confirmDelete(contractAssign.id)" :action="'delete'"></action-button>
                    </nobr>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
              </tr>
              <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && contractAssigns?.data?.length"></LoaderComponent>
          </tbody>
          
          <tfoot v-if="!contractAssigns?.data?.length" class="relative h-[250px]">
            <tr v-if="isLoading">
            </tr>
            <tr v-else-if="isTableLoading">
                <td colspan="8">
                  <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                </td>
            </tr>
            <tr v-else-if="!contractAssigns?.data?.length">
              <td colspan="8">No data found.</td>
            </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="contractAssigns" to="ops.contract-assigns.index" :page="page"></Paginate>
  </div>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<style>
  table > tbody> tr > td {
      text-align: left;
  }
</style>