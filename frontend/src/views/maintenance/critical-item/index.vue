<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useCriticalItem from "../../../composables/maintenance/useCriticalItem";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { criticalItems, getCriticalItems, deleteCriticalItem, isLoading } = useCriticalItem();
const { setTitle } = Title();
setTitle('Critical Item List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this critical Item!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteCriticalItem(id);
    }
  })
}

function setBusinessUnit($el){
  businessUnit.value = $el.target.value;
}

onMounted(() => {
  watchEffect(() => {
  getCriticalItems(props.page, businessUnit.value)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching critical items:", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Critical Item List</h2>
    <default-button :title="'Create Critical Item'" :to="{ name: 'mnt.critical-items.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <!-- <div class="relative w-full">
      <select @change="setBusinessUnit($event)" class="form-control business_filter_input border-transparent focus:ring-0"
      :disabled="defaultBusinessUnit === 'TSLL' || defaultBusinessUnit === 'PSML'"
      >
        <option value="ALL" :selected="businessUnit === 'ALL'">ALL</option>
        <option value="PSML" :selected="businessUnit === 'PSML'">PSML</option>
        <option value="TSLL" :selected="businessUnit === 'TSLL'">TSLL</option>
      </select>
    </div> -->
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
            <th class="w-1/12">#</th>
            <th class="w-2/12">Function Name</th>
            <th class="w-2/12">Category Name</th>
            <th class="w-2/12">Item Name</th>
            <th class="w-2/12">Specification</th>
            <th class="w-2/12">Notes</th>
            <!-- <th class="w-2/12">Business Unit</th> -->
            <th class="w-1/12">Action</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(criticalItem,index) in criticalItems?.data" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ criticalItem?.mntCriticalItemCat?.mntCriticalFunction?.function_name }}</td>
            <td>{{  criticalItem?.mntCriticalItemCat?.category_name }}</td>
            <td>{{ criticalItem?.item_name }}</td>
            <td>{{ criticalItem?.specification }}</td>
            <td>{{ criticalItem?.notes }}</td>
            <!-- <td><span :class="criticalItem?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ criticalItem?.business_unit }}</span></td> -->
            <td>
                <action-button :action="'edit'" :to="{ name: 'mnt.critical-items.edit', params: { criticalItemId: criticalItem?.id } }"></action-button>
                <action-button @click="confirmDelete(criticalItem?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!criticalItems?.data?.length">
          <tr v-if="isLoading">
            <td colspan="7">Loading...</td>
          </tr>
          <tr v-else-if="!criticalItems?.data?.length">
            <td colspan="7">No item found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="criticalItems" to="mnt.critical-items.index" :page="page"></Paginate>
  </div>
</template>