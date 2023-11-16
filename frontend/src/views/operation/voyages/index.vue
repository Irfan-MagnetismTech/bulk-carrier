<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
const icons = useHeroIcon();
import useVoyage from '../../../composables/operations/useVoyage';
import Store from './../../../store/index.js';


const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);

const { voyages, getVoyages, deleteVoyage, isLoading } = useVoyage();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Voyage List');

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
        deleteVoyage(id);
    }
  })
}
function setBusinessUnit($el){
  businessUnit.value = $el.target.value;
}
onMounted(() => {
  watchEffect(() => {
    getVoyages(props.page, businessUnit.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Voyage List</h2>
    <default-button :title="'Create Voyage'" :to="{ name: 'ops.voyages.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <!-- Search -->
    <div class="relative w-full">
      <select @change="setBusinessUnit($event)" class="form-control business_filter_input border-transparent focus:ring-0"
      :disabled="defaultBusinessUnit === 'TSLL' || defaultBusinessUnit === 'PSML'"
      >
        <option value="ALL" :selected="businessUnit === 'ALL'">ALL</option>
        <option value="PSML" :selected="businessUnit === 'PSML'">PSML</option>
        <option value="TSLL" :selected="businessUnit === 'TSLL'">TSLL</option>
      </select>
    </div>
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
            <th>#</th>
            <th>Mother Vessel</th>
            <th>Vessel</th>
            <th>Voyage No</th>
            <th>Cargo Type</th>
            <th>POL</th>
            <th>FPOD</th>
            <!-- <th>Initial Load</th>
            <th>Actual Load</th> -->
            <!-- Its Hidden cause when there will be multiple sectors, it won't be useful anymore as there will be multiple initial and actual loads -->
            <th>Voyage Status</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody v-if="voyages?.data?.length">
              <tr v-for="(voyage, index) in voyages.data" :key="voyage?.id">
                  <td>{{ voyages.from + index }}</td>
                  <td>{{ voyage?.mother_vessel }}</td>
                  <td>{{ voyage?.opsVessel?.name }}</td>
                  <td>{{ voyage?.voyage_no }}</td>
                  <td>{{ voyage?.opsCargoType?.cargo_type }}</td>
                  <td>{{ voyage?.opsVoyageSectors[0].loading_point }}</td>
                  <td>{{ voyage?.opsVoyageSectors[voyage?.opsVoyageSectors.length - 1].unloading_point }}</td>
                  <!-- <td></td>
                  <td></td> -->
                  <td>{{ voyage?.status }}</td>

                  <td class="items-center justify-center space-x-2 text-gray-600">
                      <action-button :action="'show'" :to="{ name: 'ops.voyages.show', params: { voyageId: voyage.id } }"></action-button>
                      <action-button :action="'edit'" :to="{ name: 'ops.voyages.edit', params: { voyageId: voyage.id } }"></action-button>
                      <action-button @click="confirmDelete(voyage.id)" :action="'delete'"></action-button>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
              </tr>
          </tbody>
          
          <tfoot v-if="!voyages?.length">
          <tr v-if="isLoading">
            <td colspan="11">Loading...</td>
          </tr>
          <tr v-else-if="!voyages?.data?.length">
            <td colspan="11">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="voyages" to="ops.configurations.voyages.index" :page="page"></Paginate>
  </div>
</template>
