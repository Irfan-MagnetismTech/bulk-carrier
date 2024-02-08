<script setup>
import {onMounted, ref, watch, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import useChartererContract from '../../../composables/operations/useChartererContract';
import Store from "../../../store";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import { formatDate } from "../../../utils/helper.js"
import FilterComponent from "../../../components/utils/FilterComponent.vue";


const { chartererContracts, getChartererContracts, deleteChartererContract, isLoading, isTableLoading } = useChartererContract();
const icons = useHeroIcon();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const debouncedValue = useDebouncedRef('', 800);
const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Charterer Contract List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

let showFilter = ref(false);

function swapFilter() {
  showFilter.value = !showFilter.value;
}

watch(

	() => businessUnit.value,
	(newBusinessUnit, oldBusinessUnit) => {
		if (newBusinessUnit !== oldBusinessUnit) {
		router.push({ name: "ops.configurations.cargo-types.index", query: { page: 1 } })
		}	
	}
);

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
        deleteChartererContract(id);
    }
  })
}

let filterOptions = ref( {
"business_unit": businessUnit.value,
"items_per_page": 15,
"page": props.page,
"isFilter": false,
"filter_options": [

			{
			"relation_name": null,
			"field_name": "contract_type",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null,
      "label": "Contract Type",
      "filter_type": "input"
			},
      {
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
			"relation_name": null,
			"field_name": "contract_name",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null,
      "label": "Contract Name",
      "filter_type": "input"
			},
      {
			"relation_name": null,
			"field_name": "email",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null,
      "label": "Email",
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
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;

    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }

    getChartererContracts(filterOptions.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Charterer Contract List</h2>
    <default-button :title="'Create Charterer Contract'" :to="{ name: 'ops.charterer-contracts.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
        <FilterComponent :filterOptions = "filterOptions"/>
          <tbody v-if="chartererContracts?.data?.length" class="relative">
              <tr v-for="(chartererContract, index) in chartererContracts.data" :key="chartererContract?.id">
                  <td class="text-center">{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
                  <td>{{ chartererContract?.contract_type }}</td>
                  <td>{{ chartererContract?.opsChartererProfile?.name }}</td>
                  <td>{{ chartererContract?.contract_name }}</td>
                  <td>{{ chartererContract?.email }}</td>
                  <td class="text-center">
                    <span :class="chartererContract?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ chartererContract?.business_unit }}</span>

                  </td>
                  <td class="items-center text-center justify-center space-x-1 text-gray-600">
                    <nobr>
                      <action-button :action="'show'" :to="{ name: 'ops.charterer-contracts.show', params: { chartererContractId: chartererContract.id } }"></action-button>
                      <action-button :action="'edit'" :to="{ name: 'ops.charterer-contracts.edit', params: { chartererContractId: chartererContract.id } }"></action-button>
                      <action-button @click="confirmDelete(chartererContract.id)" :action="'delete'"></action-button>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                    </nobr>
                  </td>
              </tr>
              <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && chartererContracts?.data?.length"></LoaderComponent>
          </tbody>
          
          <tfoot v-if="!chartererContracts?.data?.length" class="relative h-[250px]">
          <tr v-if="isLoading">
            <td colspan="9">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="9">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
          </tr>
          <tr v-else-if="!chartererContracts?.data?.length">
            <td colspan="9">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="chartererContracts" to="ops.charterer-contracts.index" :page="page"></Paginate>
  </div>
</template>
<style>
  table > tbody> tr > td {
      text-align: left;
  }
</style>