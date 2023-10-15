<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useWarehouse from "../../../composables/supply-chain/useWarehouse";
import Title from "../../../services/title";
import { useFuse } from "@vueuse/integrations/useFuse";
import useHeroIcon from "../../../assets/heroIcon";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const icons = useHeroIcon();
const { warehouses, getWarehouses, deleteWarehouse, isLoading } = useWarehouse();
const { setTitle } = Title();
setTitle('Warehouses');


const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);

onMounted(() => {
  getWarehouses();
});


function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this warehouse!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteWarehouse(id);
    }
  })
}

function setBusinessUnit($el){
  businessUnit.value = $el.target.value;
}

onMounted(() => {
    watchEffect(() => {
    getWarehouses(props.page, businessUnit.value)
      .then(() => {
        const customDataTable = document.getElementById("customDataTable");

        if (customDataTable) {
          tableScrollWidth.value = customDataTable.scrollWidth;
        }
      })
      .catch((error) => {
        console.error("Error fetching warehouses:", error);
      });
  });
});

</script>



<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Warehouse List</h2>
    <default-button :title="'Create Warehouse'" :to="{ name: 'supply-chain.warehouse.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <div class="relative w-full">
      <select @change="setBusinessUnit($event)" class="form-control business_filter_input border-transparent focus:ring-0"
      :disabled="defaultBusinessUnit === 'TSLL' || defaultBusinessUnit === 'PSML'"
      >
        <option value="ALL" :selected="businessUnit === 'ALL'">ALL</option>
        <option value="PSML" :selected="businessUnit === 'PSML'">PSML</option>
        <option value="TSLL" :selected="businessUnit === 'TSLL'">TSLL</option>
      </select>
    </div>
    <!-- Search -->
    <div class="relative w-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
      </svg>
      <input type="text" placeholder="Search..." class="search" />
    </div>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
          <thead v-once>
          <tr class="w-full">
            <th class="">SL. </th>
            <th class="">Name</th>
            <th class="">Short Code</th>
            <th>Business Unit</th>
            <th class="px-4 py-3 text-center">Actions</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(warehouse, index) in warehouses?.data" :key="warehouse.id">  
              <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
              <td class="px-4 py-3 text-sm">{{ warehouse.name }}</td>
              <td class="px-4 py-3 text-sm">{{ warehouse.short_code }}</td>
            <td>
              <span :class="warehouse?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ warehouse?.business_unit }}</span>
            </td>
            <td>
              <action-button :action="'edit'" :to="{ name: 'supply-chain.warehouse.edit', params: { warehouseId: warehouse?.id } }"></action-button>
              <action-button @click="confirmDelete(warehouse?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!warehouses?.data?.length">
          <tr v-if="isLoading">
            <td colspan="4">Loading...</td>
          </tr>
          <tr v-else-if="!warehouses?.data?.data?.length">
            <td colspan="4">No Warehouse found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="ranks" to="supply-chain.warehouse.index" :page="page"></Paginate>
  </div>
</template>

