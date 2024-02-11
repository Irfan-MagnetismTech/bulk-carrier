<script setup>
import {onMounted, ref, watchEffect, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
// import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import useBunkerRequisition from '../../../composables/operations/useBunkerRequisition';
import Store from "../../../store";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
const router = useRouter();
const debouncedValue = useDebouncedRef('', 800);


const { bunkerRequisitions, getBunkerRequisitions, deleteBunkerRequisition, isLoading, isTableLoading, errors } = useBunkerRequisition();
const icons = useHeroIcon();
const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Bunker Requisition List');

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
      deleteBunkerRequisition(id);
    }
  })
}

watch(
    () => businessUnit.value,
    (newBusinessUnit, oldBusinessUnit) => {
      if (newBusinessUnit !== oldBusinessUnit) {
        router.push({ name: "ops.bunker-requisitions.index", query: { page: 1 } })
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
      "relation_name": null,
      "field_name": "requisition_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Requisition No.",
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
      router.push({ name: 'ops.bunker-requisitions.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getBunkerRequisitions(filterOptions.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Bunker Requisition List</h2>
    <default-button :title="'Create Bunker Requisition'" :to="{ name: 'ops.bunker-requisitions.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody v-if="bunkerRequisitions?.data?.length" class="relative">
              <tr v-for="(bunkerRequisition, index) in bunkerRequisitions.data" :key="bunkerRequisition?.id">
                  <td class="text-center">{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
                  <td>{{ bunkerRequisition?.opsVoyage?.voyage_sequence }}</td>
                  <td>{{ bunkerRequisition?.opsVessel?.name }}</td>

                  <td>{{ bunkerRequisition?.requisition_no }}</td>
                  <td class="text-center">
                    <span :class="bunkerRequisition?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ bunkerRequisition?.business_unit }}</span>
                  </td>
                  <td class="items-center text-center justify-center space-x-1 text-gray-600">
                    <nobr>
                      <action-button :action="'show'" :to="{ name: 'ops.bunker-requisitions.show', params: { bunkerRequisitionId: bunkerRequisition.id } }"></action-button>
                      <!-- <action-button :action="'approved'" :to="{ name: 'ops.bunker-requisitions.approved', params: { bunkerRequisitionId: bunkerRequisition.id } }"></action-button> -->
                      <action-button :action="'edit'" :to="{ name: 'ops.bunker-requisitions.edit', params: { bunkerRequisitionId: bunkerRequisition.id } }"></action-button>
                      <action-button @click="confirmDelete(bunkerRequisition.id)" :action="'delete'"></action-button>
                    </nobr>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
              </tr>
              <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && bunkerRequisitions?.data?.length"></LoaderComponent>
          </tbody>
          
          <tfoot v-if="!bunkerRequisitions?.data?.length" class="relative h-[250px]">
          <tr v-if="isLoading">
            <td colspan="8">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="8">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
            </tr>
          <tr v-else-if="!bunkerRequisitions?.data?.length">
            <td colspan="8">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="bunkerRequisitions" to="ops.bunker-requisitions.index" :page="page"></Paginate>
  </div>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<style>
  table > tbody> tr > td {
      text-align: left;
  }
</style>