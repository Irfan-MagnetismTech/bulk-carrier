<script setup>
import {onMounted, ref, watch, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import usePort from '../../../composables/operations/usePort';
import Store from './../../../store/index.js';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Port List');

const icons = useHeroIcon();
const { ports, getPorts, deletePort, isLoading, isTableLoading } = usePort();
const debouncedValue = useDebouncedRef('', 800);

const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;


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
        deletePort(id);
    }
  })
}

let showFilter = ref(false);

function swapFilter() {
  showFilter.value = !showFilter.value;
}

watch(
    () => businessUnit.value,
    (newBusinessUnit, oldBusinessUnit) => {
      if (newBusinessUnit !== oldBusinessUnit) {
        router.push({ name: "ops.configurations.ports.index", query: { page: 1 } })
      }
    }
);


let filterOptions = ref({
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "code",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Port/Ghat Code",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Port/Ghat Name",
      "filter_type": "input"
    },
  
  ]
});


const currentPage = ref(1);
const paginatedPage = ref(1);
let stringifiedFilterOptions = JSON.stringify(filterOptions.value);


onMounted(() => {
  watchPostEffect(() => {
    
      if(currentPage.value == props.page && currentPage.value != 1) {
        filterOptions.value.page = 1;
        router.push({ name: 'ops.ports.index', query: { page: filterOptions.value.page } });
      } else {
        filterOptions.value.page = props.page;
      }
      currentPage.value = props.page;
      if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
        filterOptions.value.isFilter = true;
      }

      getPorts(filterOptions.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Port List</h2>
    <default-button :title="'Create Port'" :to="{ name: 'ops.configurations.ports.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <div id="customDataTable" class="mb-6">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >        
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody v-if="ports?.data?.length" class="relative">
              <tr v-for="(port, index) in ports.data" :key="port?.id">
                  <td class="text-center">{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>

                  <td>{{ port?.code }}</td>
                  <td>{{ port?.name }}</td>
                  <td class="text-center">
                    <span :class="port?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ port?.business_unit }}</span>
                  </td>
                  <td class="items-center text-center justify-center space-x-1 text-gray-600">
                    <nobr>
                      <action-button :action="'show'" :to="{ name: 'ops.configurations.ports.show', params: { portId: port.id } }"></action-button>
                      <action-button :action="'edit'" :to="{ name: 'ops.configurations.ports.edit', params: { portId: port.id } }"></action-button>
                      <action-button @click="confirmDelete(port.id)" :action="'delete'"></action-button>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                    </nobr>  
                  </td>
                </tr>
                <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && ports?.data?.length"></LoaderComponent>
          </tbody>
          
          <tfoot v-if="!ports?.data?.length" class="relative h-[250px]">
          <tr v-if="isLoading">
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="6">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
            </tr>
          <tr v-else-if="!ports?.data?.length">
            <td colspan="6">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="ports" to="ops.configurations.ports.index" :page="page"></Paginate>
  </div>
</template>
<style>
  table > tbody> tr > td {
      text-align: left;
  }
</style>