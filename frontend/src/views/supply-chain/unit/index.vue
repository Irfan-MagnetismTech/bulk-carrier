<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useUnit from "../../../composables/supply-chain/useUnit";
import Title from "../../../services/title";
import { useFuse } from "@vueuse/integrations/useFuse";
import Swal from "sweetalert2";
import Paginate from '../../../components/utils/paginate.vue';
import useHeroIcon from "../../../assets/heroIcon";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { units, getUnits, deleteUnit, isLoading} = useUnit();
const icons = useHeroIcon();
const { setTitle } = Title();
setTitle('Units');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

onMounted(() => {
  watchEffect(() => {
  getUnits(props.page)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching units:", error);
    });
});

});

function confirmDelete(id) {
        Swal.fire({
          title: 'Are you sure?',
          text: "You want to change delete this Unit!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {
            deleteUnit(id);
          }
        })
      }

</script>



<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Unit List</h2>
    <default-button :title="'Create Unit'" :to="{ name: 'scm.unit.create' }" :icon="icons.AddIcon"></default-button>
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
            <th>Unit Name</th>
            <th>Short Code</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(unit,index) in units?.data" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ unit?.name }}</td>
            <td>{{ unit?.short_code }}</td>
            <td>
              <action-button :action="'edit'" :to="{ name: 'scm.unit.edit', params: { unitId: unit?.id } }"></action-button>
              <action-button @click="confirmDelete(unit?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!units?.data?.length" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="7">Loading...</td>
        </tr>
        <tr v-else-if="!units?.data?.length">
          <td colspan="7">No Units found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="units" to="scm.unit.index" :page="page"></Paginate>
  </div>
</template>
