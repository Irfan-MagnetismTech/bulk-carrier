<script setup>
import {onMounted, ref,watch, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import useDeliveryRedelivery from '../../../composables/operations/useDeliveryRedelivery';
import Store from "../../../store";
import moment from 'moment';
import useHelper from "../../../composables/useHelper";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import useDebouncedRef from "../../../composables/useDebouncedRef";


const { deliveryRedeliveries, getDeliveryRedeliverys, deleteDeliveryRedelivery, isLoading, isTableLoading } = useDeliveryRedelivery();
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
setTitle('Delivery Redelivery List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const { numberFormat } = useHelper();

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
        deleteDeliveryRedelivery(id);
    }
  })
}


let filterOptions = ref( {
"items_per_page": 15,
"page": props.page,
"isFilter": false,
"filter_options": [

{
			"relation_name": "opsChartererProfile",
			"field_name": "name",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": "opsVessel",
			"field_name": "name",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "effective_date",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "note_type",
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

    getDeliveryRedeliverys(filterOptions.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Delivery Redelivery List</h2>
    <default-button :title="'Create Delivery Redelivery'" :to="{ name: 'ops.delivery-redelivery.create' }" :icon="icons.AddIcon"></default-button>
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
              <th>
                <div class="flex justify-evenly items-center">
                    <span>Charterer</span>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <span>Vessel</span>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <!-- <th>
                <div class="flex justify-evenly items-center">
                    <span>Date</span>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(2,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(2,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th> -->
              <th class="w-28">
                <div class="flex justify-evenly items-center">
                    <span>Type</span>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(3,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(3,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[3].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[3].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">                  
                  <nobr>Total Amount <small>(USD)</small></nobr>
                  <!-- <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(4,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(4,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[4].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[4].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div> -->
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <nobr>Total Amount <small>(BDT)</small></nobr>
                    <!-- <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(5,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[5].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[5].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(5,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[5].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[5].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div> -->
                  </div>
              </th>
              <th>
                <nobr>Business Unit</nobr>
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
              <th><input v-model.trim="filterOptions.filter_options[0].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model.trim="filterOptions.filter_options[1].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <!-- <th><input v-model.trim="filterOptions.filter_options[2].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th> -->
              <th>
                <select v-model="filterOptions.filter_options[2].search_param" class="filter_input">
                  <option value="Delivery">Delivery</option>
                  <option value="Re-delivery">Re-delivery</option>
                </select>
              </th>
              <th>
                <input type="text" disabled placeholder="" class="filter_input bg-gray-100" autocomplete="off" />
              </th>
              <th>
                <input type="text" disabled placeholder="" class="filter_input bg-gray-100" autocomplete="off" />

              </th>
              <th>
                <filter-with-business-unit v-model="filterOptions.business_unit"></filter-with-business-unit>
              </th>
              <th>
                <button title="Clear Filter" @click="clearFilter()" type="button" v-html="icons.NotFilterIcon"></button>
              </th>
            </tr>
          </thead>
          <tbody v-if="deliveryRedeliveries?.data?.length" class="relative">
              <tr v-for="(deliveryRedelivery, index) in deliveryRedeliveries.data" :key="deliveryRedelivery?.id">
                <td>{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
                  <td>{{ deliveryRedelivery?.opsChartererProfile?.name }}</td>
                  <td>{{ deliveryRedelivery?.opsVessel?.name }}</td>
                  
                  <td>{{ deliveryRedelivery?.note_type }}</td>
                  <td>
                    {{ numberFormat(deliveryRedelivery?.opsBunkers.reduce((accumulator, currentObject) => {
  return accumulator + currentObject.amount_usd;
}, 0)) }}
                  </td>
                  <td>
                  
                    {{ numberFormat(deliveryRedelivery?.opsBunkers.reduce((accumulator, currentObject) => {
  return accumulator + currentObject.amount_bdt;
}, 0)) }}

                  </td>
                  <td>
                    <span :class="deliveryRedelivery?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ deliveryRedelivery?.business_unit }}</span>

                  </td>

                  <td class="items-center justify-center space-x-1 text-gray-600">
                      <nobr>
                        <action-button :action="'show'" :to="{ name: 'ops.delivery-redelivery.show', params: { deliveryRedeliveryId: deliveryRedelivery.id } }"></action-button>
                        <action-button :action="'edit'" :to="{ name: 'ops.delivery-redelivery.edit', params: { deliveryRedeliveryId: deliveryRedelivery.id } }"></action-button>
                        <action-button @click="confirmDelete(deliveryRedelivery.id)" :action="'delete'"></action-button>
                      <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                      </nobr>
                  </td>
              </tr>
              <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && deliveryRedeliveries?.data?.length"></LoaderComponent>
          </tbody>
          
          <tfoot v-if="!deliveryRedeliveries?.data?.length" class="relative h-[250px]">
            <tr v-if="isLoading">
              <td colspan="9">Loading...</td>
            </tr>
            <tr v-else-if="isTableLoading">
                <td colspan="9">
                  <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                </td>
            </tr>
            <tr v-else-if="!deliveryRedeliveries?.data?.length">
              <td colspan="9">No data found.</td>
            </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="deliveryRedeliveries" to="ops.delivery-redelivery.index" :page="page"></Paginate>
  </div>
</template>
