<script setup>
import {onMounted, ref, watch, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useOpeningBalance from "../../../composables/accounts/useOpeningBalance";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from './../../../store/index.js';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import {useRouter} from "vue-router/dist/vue-router";
const router = useRouter();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});
const icons = useHeroIcon();
const { openingBalances, getOpeningBalances, deleteOpeningBalance, isLoading } = useOpeningBalance();
const { setTitle } = Title();
setTitle('Opening Balance List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this item!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteOpeningBalance(id);
    }
  })
}

watch(
    () => businessUnit.value,
    (newBusinessUnit, oldBusinessUnit) => {
      if (newBusinessUnit !== oldBusinessUnit) {
        router.push({ name: "acc.opening-balances.index", query: { page: 1 } })
      }
    }
);

onMounted(() => {
  watchEffect(() => {
  getOpeningBalances(props.page, businessUnit.value)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching ranks:", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Opening Balance List</h2>
    <default-button :title="'Create Opening Balance'" :to="{ name: 'acc.opening-balances.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <filter-with-business-unit v-model="businessUnit"></filter-with-business-unit>
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
            <th>Cost Center</th>
            <th>Account Name</th>
            <th>Date</th>
            <th>Debit Amount</th>
            <th>Credit Amount</th>
            <th>Business Unit</th>
            <th>Action</th>

          </tr>
          </thead>
          <tbody>
          <tr v-for="(balanceData,index) in openingBalances?.data" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ balanceData?.costCenter?.name }}</td>
            <td>{{ balanceData?.account?.account_name }}</td>
            <td>{{ balanceData?.date }}</td>
            <td>{{ balanceData?.cr_amount }}</td>
            <td>{{ balanceData?.dr_amount }}</td>
            <td>
              <span :class="balanceData?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ balanceData?.business_unit }}</span>
            </td>
            <td>
              <action-button :action="'edit'" :to="{ name: 'acc.opening-balances.edit', params: { openingBalanceId: balanceData?.id } }"></action-button>
              <action-button @click="confirmDelete(balanceData?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!openingBalances?.data?.length">
          <tr v-if="isLoading">
            <td colspan="8">Loading...</td>
          </tr>
          <tr v-else-if="!openingBalances?.data?.data?.length">
            <td colspan="8">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="openingBalances" to="acc.opening-balances.index" :page="page"></Paginate>
  </div>
</template>