<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useItemGroup from "../../../composables/maintenance/useItemGroup";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { itemGroups, getItemGroups, deleteItemGroup, isLoading } = useItemGroup();
const { setTitle } = Title();
setTitle('Item Group List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;


function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this item group!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteItemGroup(id);
    }
  })
}

onMounted(() => {
  watchEffect(() => {
  getItemGroups(props.page)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching item groups:", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Item Group List</h2>
    <default-button :title="'Create'" :to="{ name: 'maintenance.item-group.create' }"></default-button>
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
            <th class="w-1/4 md:w-64">Name</th>
            <th class="w-64">Short Code</th>
            <th class="w-68">Action</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(itemGroup,index) in itemGroups?.data" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ itemGroup?.name }}</td>
            <td>{{ itemGroup?.short_code }}</td>
            
            <td class="">
              <div class="flex">
                <action-button :action="'edit'" :to="{ name: 'maintenance.item-group.edit', params: { itemGroupId: itemGroup?.id } }"></action-button>
                <action-button @click="confirmDelete(itemGroup?.id)" :action="'delete'"></action-button>
              </div>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!itemGroups?.data?.length">
          <tr v-if="isLoading">
            <td colspan="6">Loading...</td>
          </tr>
          <tr v-else-if="!itemGroups?.data?.length">
            <td colspan="6">No item group found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="itemGroups" to="maintenance.item-group.index" :page="page"></Paginate>
  </div>
</template>