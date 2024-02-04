<script setup>
import {onMounted, ref, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useFixedAsset from "../../../composables/accounts/useFixedAsset";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from "../../../store";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import useDepreciation from "../../../composables/accounts/useDepreciation";
const icons = useHeroIcon();
import { formatMonthYear, formatDate } from "../../../utils/helper.js";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { depreciations, getDepreciations, deleteDepreciation, isLoading, isTableLoading} = useDepreciation();
const debouncedValue = useDebouncedRef('', 800);
const { setTitle } = Title();
setTitle('Depreciation');
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

let filterOptions = ref({
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "isFilter": false,
  "page": props.page,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "month_year",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Month - Year",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "applied_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Applied Date",
      "filter_type": "input"
    },
    {
      "relation_name": 'costCenter',
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Cost Center",
      "filter_type": "input"
    },
  ]
});

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

const currentPage = ref(1);
const paginatedPage = ref(1);
let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this item!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteDepreciation(id);
    }
  })
}

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'acc.depreciations.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getDepreciations(filterOptions.value)
      .then(() => {
        paginatedPage.value = filterOptions.value.page;
        const customDataTable = document.getElementById("customDataTable");

        if (customDataTable) {
          tableScrollWidth.value = customDataTable.scrollWidth;
        }
      })
      .catch((error) => {
        console.error("Failed to Load:", error);
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
    <h2 class="text-2xl font-semibold text-gray-700"> Depreciation List </h2>
    <default-button :title="'Create Depreciation'" :to="{ name: 'acc.depreciations.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
        <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">
                <tr v-for="(depreciationData, index) in depreciations?.data" :key="index">
                  <td> {{ (paginatedPage  - 1) * filterOptions.items_per_page + index + 1 }} </td>
                  <td class="text-left"> {{ formatMonthYear(depreciationData?.month_year) }} </td>
                  <td class="text-left"> {{ formatDate(depreciationData?.applied_date) }} </td>
                  <td class="text-left"> {{ depreciationData?.costCenter?.name }} </td>
                <td>
                  <span :class="depreciationData?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">
                    {{ depreciationData?.business_unit }}
                  </span>
                </td>
                <td>
                  <nobr>
                    <action-button :action="'show'" :to="{ name: 'acc.depreciations.show', params: { depreciationId: depreciationData?.id } }"></action-button>
                    <action-button :action="'edit'" :to="{ name: 'acc.depreciations.edit', params: { depreciationId: depreciationData?.id } }"></action-button>
                    <action-button @click="confirmDelete(depreciationData?.id)" :action="'delete'"></action-button>
                  </nobr>
                </td>
              </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && depreciations?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!depreciations?.data?.length">
          <tr v-if="isLoading">
            <td colspan="5"></td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="5">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
          </tr>
          <tr v-else-if="!depreciations?.data?.length">
            <td colspan="13">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="depreciations" to="acc.depreciations.index" :page="page"></Paginate>
  </div>
</template>