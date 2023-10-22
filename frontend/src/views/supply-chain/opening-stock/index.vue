<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useOpeningStock from "../../../composables/supply-chain/useOpeningStock";
import useHelper from "../../../composables/useHelper.js";
import Title from "../../../services/title";
import useDebouncedRef from '../../../composables/useDebouncedRef';
import Swal from "sweetalert2";
import Paginate from '../../../components/utils/paginate.vue';
import useHeroIcon from "../../../assets/heroIcon";

const { getOpeningStocks, openingStocks, deleteService, isLoading } = useOpeningStock();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const { numberFormat } = useHelper();
const { setTitle } = Title();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

setTitle('Opening Stocks');
// Code for global search starts here
const columns = ["date"];
const searchKey = useDebouncedRef('', 600);
const table = "opening_stocks";

const icons = useHeroIcon();

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

onMounted(() => {
  watchEffect(() => {
    getOpeningStocks(props.page,businessUnit.value)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");
      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching opening-stock category:", error);
    });
});

});// Code for global search end here
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
            deleteService(id);
          }
        })
      }
</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Opening Stock List</h2>
    <default-button :title="'Create Opening Stock'" :to="{ name: 'scm.opening-stock.create' }" :icon="icons.AddIcon"></default-button>
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
            <th>Short Code</th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="(openingStock,index) in (openingStocks?.data ? openingStocks?.data : openingStocks)" :key="index">
              <td>{{ openingStocks?.from + index }}</td>
              <td>{{ openingStock?.date }}</td>
              <td>{{ openingStock?.warehouse }}</td>
              <td>
                <action-button :action="'show'" :to="{ name: 'scm.opening-stocks.show', params: { openingStockId: openingStock.id } }"></action-button>
                <action-button :action="'edit'" :to="{ name: 'scm.opening-stocks.edit', params: { openingStockId: openingStock.id } }"></action-button>
                <action-button @click="confirmDelete(openingStock.id)" :action="'delete'"></action-button>
              </td>
            </tr>
          </tbody>
          <tfoot v-if="!openingStocks?.data?.length" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="7">Loading...</td>
        </tr>
        <tr v-else-if="!openingStocks?.data?.length">
          <td colspan="7">No Material Category found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="openingStocks" to="scm.opening-stocks.index" :page="page"></Paginate>
  </div>
</template>