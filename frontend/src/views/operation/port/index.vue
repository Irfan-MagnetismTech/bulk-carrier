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

let filterOptions = ref( {
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
      "date_from": null
    },
    {
      "relation_name": null,
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    }
  ]
});

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

function setSortingState(index, order) {
  filterOptions.value.filter_options.forEach(function (t) {
    t.order_by = null;
  });
  filterOptions.value.filter_options[index].order_by = order;
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
          <thead>
            <tr class="w-full">
              <th class="w-16">
                <div class="w-full flex items-center justify-between">
                  # <button @click="swapFilter()" type="button" v-html="icons.FilterIcon"></button>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Port/Ghat Code</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Port/Ghat Name</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
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


              <th>
                
              </th>

            </tr>
          </thead>
          <tbody v-if="ports?.data?.length" class="relative">
              <tr v-for="(port, index) in ports.data" :key="port?.id">
                  <td>{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>

                  <td>{{ port?.code }}</td>
                  <td>{{ port?.name }}</td>
                  <td class="items-center justify-center space-x-1 text-gray-600">
                      <action-button :action="'edit'" :to="{ name: 'ops.configurations.ports.edit', params: { portId: port.id } }"></action-button>
                      <action-button @click="confirmDelete(port.id)" :action="'delete'"></action-button>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
                </tr>
                <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && ports?.data?.length"></LoaderComponent>
          </tbody>
          
          <tfoot v-if="!ports?.data?.length" class="relative h-[250px]">
          <tr v-if="isLoading">
            <td colspan="6">Loading...</td>
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
