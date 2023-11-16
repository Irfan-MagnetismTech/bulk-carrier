<script setup>
import {onMounted, ref, watch, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
const icons = useHeroIcon();
import useVessel from '../../../composables/operations/useVessel';
import Store from './../../../store/index.js';


const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const { vessels, getVessels, deleteVessel, isLoading } = useVessel();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Vessel List');

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
        deleteVessel(id);
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
		router.push({ name: "ops.vessels.index", query: { page: 1 } })
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
			"field_name": "name",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "short_code",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "vessel_type",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "imo",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "capacity",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": null,
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": null,
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": 'business_unit',
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

    getVessels(filterOptions.value)
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

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Vessel List</h2>
    <default-button :title="'Create Vessel'" :to="{ name: 'ops.vessels.create' }" :icon="icons.AddIcon"></default-button>
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
                  <span>Vessel Name</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Code</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Vessel Type</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(2,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(2,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>IMO/Lloyd Number</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(3,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(3,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[3].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[3].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Capacity (MT)</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(4,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(4,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[4].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[4].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Current Status</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(5,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[5].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[5].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(5,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[5].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[5].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Dry Docking Left (Days)</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(6,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[6].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[6].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(6,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[6].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[6].order_by !== 'desc' }" class=" font-semibold"></div>
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
              <th>Action</th>
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
              <th><input v-model.trim="filterOptions.filter_options[5].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model.trim="filterOptions.filter_options[6].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th>
                <filter-with-business-unit v-model="filterOptions.business_unit"></filter-with-business-unit>
              </th>

            </tr>
          </thead>
          <tbody v-if="vessels?.data?.length">
              <tr v-for="(vessel, index) in vessels.data" :key="vessel?.id">
                  <td>{{ vessels.from + index }}</td>
                  <td>{{ vessel?.name }}</td>
                  <td>{{ vessel?.short_code }}</td>
                  <td>{{ vessel?.vessel_type }}</td>
                  <td>{{ vessel?.imo }}</td>
                  <td>{{ vessel?.capacity }}</td>
                  <td></td>
                  <td></td>
                  <td>
                    <span :class="vessel?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ vessel?.business_unit }}</span>
                  </td>
                  <td class="items-center justify-center space-x-1 text-gray-600">
                      <nobr>
                        <action-button :action="'show'" :to="{ name: 'ops.vessels.show', params: { vesselId: vessel.id } }"></action-button>
                        <action-button :action="'edit'" :to="{ name: 'ops.vessels.edit', params: { vesselId: vessel.id } }"></action-button>
                        <action-button @click="confirmDelete(vessel.id)" :action="'delete'"></action-button>
                      </nobr>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
              </tr>
          </tbody>
          
          <tfoot v-if="!vessels?.length">
          <tr v-if="isLoading">
            <td colspan="9">Loading...</td>
          </tr>
          <tr v-else-if="!vessels?.data?.length">
            <td colspan="9">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="vessels" to="ops.configurations.vessels.index" :page="page"></Paginate>
  </div>
</template>
