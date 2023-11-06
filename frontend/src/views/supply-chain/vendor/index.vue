<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useVendor from "../../../composables/supply-chain/useVendor";
import Title from "../../../services/title";
import { useFuse } from "@vueuse/integrations/useFuse";
import Swal from "sweetalert2";
import Paginate from '../../../components/utils/paginate.vue';
import useHeroIcon from "../../../assets/heroIcon";

const { vendors, getVendors, deleteVendor, isLoading } = useVendor();

const { setTitle } = Title();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

setTitle('Vendors');

const icons = useHeroIcon();

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

onMounted(() => {
  watchEffect(() => {
    getVendors(props.page)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");
      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching vendors:", error);
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
            deleteVendor(id);
          }
        })
      }
</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Vendor List</h2>
    <default-button :title="'Create Vendor'" :to="{ name: 'scm.vendor.create' }" :icon="icons.AddIcon"></default-button>
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
            <th>Name</th>
            <th>Origin</th>
            <th>Contact</th>
            <th>Email</th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="(vendor,index) in vendors.data" :key="vendor.id">
              <td>{{ index + 1 }}</td>
              <td>{{ vendor.name }}</td>
              <td>{{ vendor.origin }}</td>
              <td>{{ vendor.scmVendorContactPersons[0].phone }}</td>
              <td>{{ vendor.scmVendorContactPersons[0].email }}</td>
              <td>
                <action-button :action="'edit'" :to="{ name: 'scm.vendor.edit', params: { vendorId: vendor.id } }"></action-button>
                <action-button @click="confirmDelete(vendor.id)" :action="'delete'"></action-button>
              </td>
            </tr>
          </tbody>
          <tfoot v-if="!vendors?.data?.length" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="5">Loading...</td>
        </tr>
        <tr v-else-if="!vendors?.data?.length">
          <td colspan="5">No Vendor found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="vendors" to="scm.vendor.index" :page="page"></Paginate>
  </div>
</template>