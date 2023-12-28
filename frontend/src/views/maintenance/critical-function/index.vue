<script setup>
import {onMounted, ref, watch, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useCriticalFunction from "../../../composables/maintenance/useCriticalFunction";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from './../../../store/index.js';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
const router = useRouter();
const debouncedValue = useDebouncedRef('', 800);

const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { criticalFunctions, getCriticalFunctions, deleteCriticalFunction, isLoading, isTableLoading, errors } = useCriticalFunction();
const { setTitle } = Title();
setTitle('Critical Function List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete this critical function!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteCriticalFunction(id);
    }
  })
}

watch(
    () => businessUnit.value,
    (newBusinessUnit, oldBusinessUnit) => {
      if (newBusinessUnit !== oldBusinessUnit) {
        router.push({ name: "mnt.critical-functions.index", query: { page: 1 } })
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
      "field_name": "function_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Name",
      "filter_type": "input"
    },   
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": "notes",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Notes",
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
      router.push({ name: 'mnt.critical-functions.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
  getCriticalFunctions(filterOptions.value)
    .then(() => {
      paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching critical functions:", error);
    });
});
// filterOptions.value.filter_options.forEach((option, index) => {
//     filterOptions.value.filter_options[index].search_param = useDebouncedRef('', 800);
//   });
});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Critical Function List</h2>
    <default-button :title="'Create Critical Function'" :to="{ name: 'mnt.critical-functions.create' }" :icon="icons.AddIcon"></default-button>
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
      
      <table class="w-full whitespace-no-wrap" >
          <!-- <thead v-once>
          <tr class="w-full">
            <th class="w-2/12">#</th>
            <th class="w-4/12">Function Name</th>
            <th class="w-4/12">Notes</th>
            <th class="w-2/12">Action</th>
          </tr>
          </thead> -->
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">
          <tr v-for="(criticalFunction,index) in criticalFunctions?.data" :key="index">
            <td>{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
            <td>{{ criticalFunction?.function_name }}</td>
            <td>{{ criticalFunction?.notes }}</td>
            <!-- <td><span :class="criticalFunction?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ criticalFunction?.business_unit }}</span></td> -->
            <td>
              <nobr>
                <action-button :action="'edit'" :to="{ name: 'mnt.critical-functions.edit', params: { criticalFunctionId: criticalFunction?.id } }"></action-button>
                <action-button @click="confirmDelete(criticalFunction?.id)" :action="'delete'"></action-button>
              </nobr>
            </td>
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && criticalFunctions?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!criticalFunctions?.data?.length" class="relative h-[250px]">
          <tr v-if="isLoading">
            <td colspan="4">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="4">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
            </tr>
          <tr v-else-if="!criticalFunctions?.data?.length">
            <td colspan="4">No critical function found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="criticalFunctions" to="mnt.critical-functions.index" :page="page"></Paginate>
  </div>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>