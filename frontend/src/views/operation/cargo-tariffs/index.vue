<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
const icons = useHeroIcon();
import useCargoTariff from '../../../composables/operations/useCargoTariff';
const { cargoTariffs, getCargoTariffs, deleteCargoTariff, isLoading } = useCargoTariff();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Cargo Tariff List');

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
        deleteCargoTariff(id);
    }
  })
}

onMounted(() => {
  watchEffect(() => {
    getCargoTariffs(props.page)
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
    <h2 class="text-2xl font-semibold text-gray-700">Cargo Tariff List</h2>
    <default-button :title="'Create Cargo Tariff'" :to="{ name: 'ops.configurations.cargo-tariffs.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
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
            <th>#</th>
            <th>Cargo Tariff</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody v-if="cargoTariffs?.data?.length">
              <tr v-for="(cargoTariff, index) in cargoTariffs.data" :key="cargoTariff?.id">
                  <td>{{ cargoTariffs.from + index }}</td>
                  <td>{{ cargoTariff?.cargo_type }}</td>
                  <td>{{ cargoTariff?.description }}</td>
                  <td class="items-center justify-center space-x-2 text-gray-600">
                      <action-button :action="'edit'" :to="{ name: 'ops.configurations.cargo-tariffs.edit', params: { cargoTariffId: cargoTariff.id } }"></action-button>
                      <action-button @click="confirmDelete(cargoTariff.id)" :action="'delete'"></action-button>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
              </tr>
          </tbody>
          
          <tfoot v-if="!cargoTariffs?.length">
          <tr v-if="isLoading">
            <td colspan="6">Loading...</td>
          </tr>
          <tr v-else-if="!cargoTariffs?.data?.length">
            <td colspan="6">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="cargoTariffs" to="ops.configurations.cargo-tariffs.index" :page="page"></Paginate>
  </div>
</template>
