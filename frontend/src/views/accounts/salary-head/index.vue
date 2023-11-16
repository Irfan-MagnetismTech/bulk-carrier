<script setup>
import {onMounted, ref, watch, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useSalaryHead from "../../../composables/accounts/useSalaryHead";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from './../../../store/index.js';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
const router = useRouter();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});
const icons = useHeroIcon();
const { salaryHeads, getSalaryHeads, deleteSalaryHead, isLoading } = useSalaryHead();
const { setTitle } = Title();
setTitle('Salary Head List');

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
      deleteSalaryHead(id);
    }
  })
}

// watch(
//     () => props.page,
//     (newBusinessUnit, oldBusinessUnit) => {
//       if (newBusinessUnit !== oldBusinessUnit) {
//         router.push({ name: "acc.salary-heads.index", query: { page: 1 } })
//       }
//     }
// );

let showFilter = ref(false);
let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
  ]
});

function setSortingState(index,order){
  filterOptions.value.filter_options[index].order_by = order;
}

function swapFilter() {
  showFilter.value = !showFilter.value;
}

onMounted(() => {
  watchEffect(() => {
  filterOptions.value.page = props.page;
  getSalaryHeads(filterOptions.value)
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
  filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = useDebouncedRef('', 800);
  });

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3">
    <h2 class="text-2xl font-semibold text-gray-700">Salary Head List</h2>
    <default-button :title="'Create Salary Head'" :to="{ name: 'acc.salary-heads.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <div id="customDataTable">
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
                <span><nobr>Salary Head Name</nobr></span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr>Business Unit</nobr></span>
              </div>
            </th>
            <th class="w-20 min-w-full">Action</th>
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
            <th><input v-model="filterOptions.filter_options[0].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
            <th>
              <filter-with-business-unit v-model="filterOptions.business_unit"></filter-with-business-unit>
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(head,index) in salaryHeads?.data" :key="index">
            <td>{{ index + 1 }}</td>
            <td class="text-left">{{ head?.name }}</td>
            <td>
              <span :class="head?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ head?.business_unit }}</span>
            </td>
            <td>
              <action-button :action="'edit'" :to="{ name: 'acc.salary-heads.edit', params: { salaryHeadId: head?.id } }"></action-button>
              <action-button @click="confirmDelete(head?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!salaryHeads?.data?.length">
          <tr v-if="isLoading">
            <td colspan="4">Loading...</td>
          </tr>
          <tr v-else-if="!salaryHeads?.data?.length">
            <td colspan="4">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="salaryHeads" to="acc.salary-heads.index" :page="page"></Paginate>
  </div>
</template>