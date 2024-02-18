<script setup>
import {onMounted, ref, watchEffect, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useCrewSalaryStructure from "../../../composables/crew/useCrewSalaryStructure";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from "../../../store";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import { formatDate,formatMonthYear,formatMonthYearWithTime } from "../../../utils/helper.js";
import FileExportButton from "../../../components/buttons/FileExportButton.vue";

const icons = useHeroIcon();
const router = useRouter();
import env from '../../../config/env';

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const rightAlign = [];
const leftAlign = [1];
const { crewSalaryStructures, getCrewSalaryStructures, deleteCrewSalaryStructure, isLoading, isTableLoading } = useCrewSalaryStructure();

const debouncedValue = useDebouncedRef('', 800);

const { setTitle } = Title();

setTitle('Crew Salary Structure');

const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": "crwCrewProfile",
      "field_name": "full_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Crew Name",
      "filter_type": "input"
    },
    {
      "relation_name": "crwCrewProfile.crwCurrentRank",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Current Rank",
      "filter_type": "input"
    },
    {
      "relation_name": "crwCrewProfile",
      "field_name": "pre_mobile_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Contact",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "gross_salary",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Gross Salary",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "addition",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Addition",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "deduction",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Deduction",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "net_amount",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Net Amount",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "currency",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Currency",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "effective_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Effective Date",
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
      deleteCrewSalaryStructure(id);
    }
  })
}

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'crw.crewSalaryStructures.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }

    getCrewSalaryStructures(filterOptions.value)
        .then(() => {
          const customDataTable = document.getElementById("customDataTable");
          paginatedPage.value = filterOptions.value.page;
          if (customDataTable) {
            tableScrollWidth.value = customDataTable.scrollWidth;
          }
        })
        .catch((error) => {
          console.error("Error fetching ranks:", error);
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
    <h2 class="text-2xl font-semibold text-gray-700">Crew Salary Structure List</h2>
    <div class="flex gap-2">
      <default-button :title="'Create Item'" :to="{ name: 'crw.crewSalaryStructures.create' }" :icon="icons.AddIcon"></default-button>
      <file-export-button
          :businessUnit="businessUnit"
          :pageOrientation="'l'"
          :fileName="'Salary Structure List'"
          :tableId="'crew-salary-structure-list'"
          :leftAlign="leftAlign"
          :rightAlign="rightAlign"
      ></file-export-button>
    </div>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">

      <table class="w-full whitespace-no-wrap" id="crew-salary-structure-list">
        <FilterComponent :filterOptions = "filterOptions"/>
        <tbody class="relative">
        <tr v-for="(crewSalaryStructureData,index) in crewSalaryStructures?.data" :key="index">
          <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
          <td class="!text-left">{{ crewSalaryStructureData?.crwCrewProfile?.full_name }}</td>
          <td class="!text-left">{{ crewSalaryStructureData?.crwCrewProfile?.crwCurrentRank?.name }}</td>
          <td>{{ crewSalaryStructureData?.crwCrewProfile?.pre_mobile_no }}</td>
          <td class="!text-right">{{ crewSalaryStructureData?.gross_salary }}</td>
          <td class="!text-right">{{ crewSalaryStructureData?.addition }}</td>
          <td class="!text-right">{{ crewSalaryStructureData?.deduction }}</td>
          <td class="!text-right">{{ crewSalaryStructureData?.net_amount }}</td>
          <td>{{ crewSalaryStructureData?.currency }}</td>
          <td>{{ formatDate(crewSalaryStructureData?.effective_date) }}</td>
          <td>
            <span :class="crewSalaryStructureData?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crewSalaryStructureData?.business_unit }}</span>
          </td>
          <td>
            <nobr>
              <action-button :action="'show'" :to="{ name: 'crw.crewSalaryStructures.show', params: { crewSalaryStructureId: crewSalaryStructureData?.id } }"></action-button>
              <action-button :action="'edit'" :to="{ name: 'crw.crewSalaryStructures.edit', params: { crewSalaryStructureId: crewSalaryStructureData?.id } }"></action-button>
              <action-button @click="confirmDelete(crewSalaryStructureData?.id)" :action="'delete'"></action-button>
            </nobr>
          </td>
        </tr>
        <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && crewSalaryStructures?.data?.length"></LoaderComponent>
        </tbody>
        <tfoot v-if="!crewSalaryStructures?.data?.length" class="relative h-[250px]">
        <tr v-if="isLoading">
          <td colspan="11"></td>
        </tr>
        <tr v-else-if="isTableLoading">
          <td colspan="11">
            <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>
          </td>
        </tr>
        <tr v-else-if="!crewSalaryStructures?.data?.data?.length">
          <td colspan="11">No data found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="crewSalaryStructure" to="crw.crewSalaryStructures.index" :page="page"></Paginate>
  </div>
</template>