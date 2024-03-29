<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import usePolicy from "../../../composables/crew/usePolicy";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from "../../../store";
const icons = useHeroIcon();
import env from '../../../config/env';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { policies, getPolicies, deletePolicy, isLoading } = usePolicy();
const { setTitle } = Title();
setTitle('Policy List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);


function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this policy!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deletePolicy(id);
    }
  })
}

onMounted(() => {
  watchEffect(() => {
  getPolicies(props.page,businessUnit.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Policy List</h2>
    <default-button :title="'Create Policy'" :to="{ name: 'crw.policies.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <!-- Search -->
    <filter-with-business-unit v-model="businessUnit"></filter-with-business-unit>
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
            <th>Policy Name</th>
            <th>Policy Type</th>
            <th>Business Unit</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(crwPolicy,index) in policies?.data" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ crwPolicy?.name }}</td>
            <td>{{ crwPolicy?.type }}</td>
            <td>
              <span :class="crwPolicy?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crwPolicy?.business_unit }}</span>
            </td>
            <td>
              <a :href="env.BASE_API_URL+'/'+crwPolicy?.attachment" target="_blank" :class="{'custom_disabled' : !crwPolicy?.attachment}">
                <div class="tooltip">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icn w-6 h-6 text-green-800 dark:text-green-800 dark:hover:text-green-800" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                  </svg>
                  <span class="tooltiptext">Download Attachment</span>
                </div>
              </a>
              <action-button :action="'edit'" :to="{ name: 'crw.policies.edit', params: { policyId: crwPolicy?.id } }"></action-button>
              <action-button @click="confirmDelete(crwPolicy?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          </tbody>
        <tfoot v-if="!policies?.data?.length">
        <tr v-if="isLoading">
          <td colspan="5">Loading...</td>
        </tr>
        <tr v-else-if="!policies?.data?.data?.length">
          <td colspan="5">No data found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="policies" to="crw.policies.index" :page="page"></Paginate>
  </div>
</template>