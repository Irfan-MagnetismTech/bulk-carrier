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
			"date_from": null
			},
      {
			"relation_name": "opsChartererProfile",
			"field_name": "name",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": "opsChartererProfile",
			"field_name": "owner_code",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "country",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "email",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "contact_no",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},

	]
});

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

function setSortingState(index, order) {
  filterOptions.value.filter_options.forEach(function (t) {
    t.order_by = null;
  });
  filterOptions.value.filter_options[index].order_by = order;
}

function clearFilter(){
  filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = "";
    filterOptions.value.filter_options[index].order_by = null;
  });
}


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
          <thead>
            <tr class="w-full">
              <th class="w-16">
                  <div class="w-full flex items-center justify-between">
                    # <button @click="swapFilter()" type="button" v-html="icons.FilterIcon"></button>
                  </div>
                </th>
              <th class="w-36">
                <div class="flex justify-evenly items-center">
                    <nobr>Contract Type</nobr>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th class="w-48">
                <div class="flex justify-evenly items-center">
                    <nobr>Charterer Name</nobr>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <nobr>Charterer Short Code</nobr>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(2,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(2,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <nobr>Country</nobr>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(3,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(3,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[3].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[3].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <nobr>Email</nobr>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(4,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(4,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[4].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[4].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <nobr>Contact</nobr>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(5,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[5].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[5].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(5,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[5].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[5].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                Action
              </th>
            </tr>
            <tr class="w-full" v-if="showFilter">
              <th>
                <select v-model="filterOptions.items_per_page" class="filter_input">
                  <option value="15">15</option>
                  <option value="30">30</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
              </th>
              <th>
                <select v-model="filterOptions.filter_options[0].search_param" class="filter_input">
                  <option value="Voyage Wise">Voyage Wise</option>
                  <option value="Day Wise">Day Wise</option>
                </select>
              </th>
              <th><input v-model.trim="filterOptions.filter_options[1].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model.trim="filterOptions.filter_options[2].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model.trim="filterOptions.filter_options[3].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model.trim="filterOptions.filter_options[4].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model.trim="filterOptions.filter_options[5].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th>
                <button title="Clear Filter" @click="clearFilter()" type="button" v-html="icons.NotFilterIcon"></button>
              </th>
            </tr>
          </thead>
          <tbody v-if="chartererContracts?.data?.length" class="relative">
              <tr v-for="(chartererContract, index) in chartererContracts.data" :key="chartererContract?.id">
                  <td>{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
                  <td>{{ chartererContract?.contract_type }}</td>
                  <td>{{ chartererContract?.opsChartererProfile?.name }}</td>
                  <td>{{ chartererContract?.opsChartererProfile?.owner_code }}</td>
                  <td>{{ chartererContract?.country }}</td>
                  <td>{{ chartererContract?.email }}</td>
                  <td>{{ chartererContract?.contact_no }}</td>
                  <td class="items-center justify-center space-x-1 text-gray-600">
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
            <td colspan="8">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="8">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
          </tr>
          <tr v-else-if="!chartererContracts?.data?.length">
            <td colspan="8">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="chartererContracts" to="ops.charterer-contracts.index" :page="page"></Paginate>
  </div>
</template>
