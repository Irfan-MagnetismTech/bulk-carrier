<script setup>
import {onMounted, ref, watch, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import Store from './../../../store/index.js';
import useDebouncedRef from "../../../composables/useDebouncedRef";
import useCustomer from '../../../composables/operations/useCustomer';

const icons = useHeroIcon();
const { customers, getCustomers, deleteCustomer, isLoading } = useCustomer();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const debouncedValue = useDebouncedRef('', 800);

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Customer List');

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
        deleteCustomer(id);
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
		router.push({ name: "ops.customers.index", query: { page: 1 } })
		}	
	}
);

let filterOptions = ref( {
"business_unit": businessUnit.value,
"items_per_page": 15,
"page": props.page,
"filter_options": [

			{
			"relation_name": null,
			"field_name": "code",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "name",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "legal_name",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "phone",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "email_general",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "business_unit",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			}
	]
});

function setSortingState(index,order){
  filterOptions.value.filter_options[index].order_by = order;
}

onMounted(() => {
  watchEffect(() => {
  filterOptions.value.page = props.page;

    getCustomers(filterOptions.value)
    .then(() => {
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
    <h2 class="text-2xl font-semibold text-gray-700">Customer List</h2>
    <default-button :title="'Create Customer'" :to="{ name: 'ops.configurations.customers.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
          <thead>
            <tr class="w-full">
              <th class="w-16 min-w-full">
                  <div class="w-full flex items-center justify-between">
                    # <button @click="swapFilter()" type="button" v-html="icons.FilterIcon"></button>
                  </div>
                </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <span>Customer Code</span>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <span>Customer Name</span>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <span>Legal Name</span>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(2,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(2,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <span>Phone</span>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(3,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(3,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[3].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[3].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <span>Email</span>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(4,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(4,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[4].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[4].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <span>Business Unit</span>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(5,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[5].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[5].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(5,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[5].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[5].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>Actions</th>
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
              <th><input v-model.trim="filterOptions.filter_options[2].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model.trim="filterOptions.filter_options[3].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model.trim="filterOptions.filter_options[4].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th>
                <filter-with-business-unit v-model="filterOptions.business_unit"></filter-with-business-unit>
              </th>
              <th></th>

            </tr>
          </thead>
          <tbody v-if="customers?.data?.length">
              <tr v-for="(customer, index) in customers.data" :key="customer?.id">
                  <td>{{ ((page-1) * filterOptions.items_per_page) + index + 1 }}</td>
                  <td>{{ customer?.code }}</td>
                  <td>{{ customer?.name }}</td>
                  <td>{{ customer?.legal_name }}</td>
                  <td>{{ customer?.phone }}</td>
                  <td>{{ customer?.email_general }}</td>
                  <td>
                    <span :class="customer?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ customer?.business_unit }}</span>
                  </td>
                  <td class="items-center justify-center space-x-2 text-gray-600">
                      <nobr>
                        <action-button :action="'show'" :to="{ name: 'ops.configurations.customers.show', params: { customerId: customer.id } }"></action-button>
                        <action-button :action="'edit'" :to="{ name: 'ops.configurations.customers.edit', params: { customerId: customer.id } }"></action-button>
                        <action-button @click="confirmDelete(customer.id)" :action="'delete'"></action-button>
                      </nobr>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
              </tr>
          </tbody>
          
          <tfoot v-if="!customers?.length">
          <tr v-if="isLoading">
            <td colspan="6">Loading...</td>
          </tr>
          <tr v-else-if="!customers?.data?.length">
            <td colspan="6">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="customers" to="ops.configurations.customers.index" :page="page"></Paginate>
  </div>
</template>
