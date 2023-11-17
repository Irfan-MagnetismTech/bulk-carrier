<script setup>
import {onMounted, ref, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useMaterialCategory from "../../../composables/supply-chain/useMaterialCategory";
import Title from "../../../services/title";
import { useFuse } from "@vueuse/integrations/useFuse";
import Swal from "sweetalert2";
import Paginate from '../../../components/utils/paginate.vue';
import useHeroIcon from "../../../assets/heroIcon";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";



const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});


const { materialCategories, getMaterialCategories, deleteMaterialCategory, isLoading, isTableLoading } = useMaterialCategory();
const { setTitle } = Title();
setTitle('Material Categories');
const icons = useHeroIcon();

const debouncedValue = useDebouncedRef('', 800);

let showFilter = ref(false);
// let isTableLoader = ref(false);


function swapFilter() {
  showFilter.value = !showFilter.value;
}

let filterOptions = ref( {
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": "parent",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": null,
      "field_name": "short_code",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    }
  ]
});

function setSortingState(index, order) {
  filterOptions.value.filter_options.forEach(function (t) {
    t.order_by = null;
  });
  filterOptions.value.filter_options[index].order_by = order;
}

const currentPage = ref(1);
const paginatedPage = ref(1);
const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

function clearFilter(){
  filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = "";
    filterOptions.value.filter_options[index].order_by = null;
  });
}

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getMaterialCategories(filterOptions.value)
      .then(() => {
      paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");
      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching material category:", error);
    });
});
filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = useDebouncedRef('', 800);
  });
});
function confirmDelete(id) {
        Swal.fire({
          title: 'Are you sure?',
          text: "You want to change delete this Category!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {
            deleteMaterialCategory(id);
          }
        })
      }
</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Material Category List</h2>
    <default-button :title="'Create Category Material'" :to="{ name: 'scm.material-category.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <!-- Search -->
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
          <!-- <thead v-once>
          <tr class="w-full">
            <th>#</th>
            <th>Name</th>
            <th>Parent Name</th>
            <th>Short Code</th>
            <th>Action</th>
          </tr>
          </thead> -->
          <thead>
            <tr class="w-full">
              <th class="w-16">
                <div class="w-full flex items-center justify-between">
                  # <button @click="swapFilter()" type="button" v-html="icons.FilterIcon"></button>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Name</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span><nobr>Parent Name</nobr></span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span><nobr>Short Code</nobr></span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(2,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(2,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th class="">Action</th>
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
              <th><input v-model="filterOptions.filter_options[1].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[2].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th>
                <button title="Clear Filter" @click="clearFilter()" type="button" v-html="icons.NotFilterIcon"></button>
              </th>
             </tr>
          </thead>
          <tbody class="relative">
            <tr v-for="(materialCategory,index) in materialCategories?.data" :key="materialCategory.id">
              <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
              <td>{{ materialCategory.name }}</td>
              <td>{{ materialCategory?.parent?.name ?? 'N/a' }}</td>
              <td>{{ materialCategory.short_code }}</td>
              <td>
                <action-button :action="'edit'" :to="{ name: 'scm.material-category.edit', params: { materialCategoryId: materialCategory.id } }"></action-button>
                <action-button @click="confirmDelete(materialCategory.id)" :action="'delete'"></action-button>
              </td>
            </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && materialCategories?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!materialCategories?.data?.length" class="bg-white dark:bg-gray-800 relative h-[250px]">
        <tr v-if="isLoading">
          <td colspan="7">Loading...</td>
        </tr>
        <tr v-else-if="isTableLoading">
            <td colspan="7">
              <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
            </td>
        </tr>
        <tr v-else-if="!materialCategories?.data?.length">
          <td colspan="7">No Datas found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="materialCategories" to="scm.material-category.index" :page="page"></Paginate>
  </div>
</template>