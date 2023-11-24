<script setup>
import {onMounted, ref, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useMaterial from "../../../composables/supply-chain/useMaterial";
import Title from "../../../services/title";
import { useFuse } from "@vueuse/integrations/useFuse";
import Swal from "sweetalert2";
import Paginate from '../../../components/utils/paginate.vue';
import useHeroIcon from "../../../assets/heroIcon";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});


const { materials, getMaterials, deleteMaterial, isLoading, isTableLoading } = useMaterial();
const { setTitle } = Title();
const icons = useHeroIcon();
const debouncedValue = useDebouncedRef('', 800);


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
      "date_from": null,
      "label": "Material Name",
      "filter_type": "input" 
    },
    {
      "relation_name": null,
      "field_name": "material_code",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Material Code",
      "filter_type": "input" 
    },
    {
      "relation_name": null,
      "field_name": "unit",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Unit",
      "filter_type": "input" 
    },
    {
      "relation_name": "scmMaterialCategory",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Material Category",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "minimum_stock",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Minimum Stock",
      "filter_type": "input"
    }
  ]
});
const currentPage = ref(1);
const paginatedPage = ref(1);

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);



const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

setTitle('Materials');



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
  getMaterials(filterOptions.value)
    .then(() => {
      paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");
      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
      // isTableLoader.value = true;
    })
    .catch((error) => {
      console.error("Error fetching materials:", error);
    });
});
filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = useDebouncedRef('', 800);
  });
});

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
            deleteMaterial(id);
          }
        })
      }

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Material List</h2>
    <default-button :title="'Create Material'" :to="{ name: 'scm.material.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <!-- Search -->
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">
          <tr v-for="(material,index) in materials?.data" :key="material.id">
            <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
            <td>{{ material.name }}</td>
            <td>{{ material.material_code }}</td>
            <td>{{ material.unit }}</td>
            <td>{{ material.scmMaterialCategory.name }}</td>
            <td>{{ material.minimum_stock }}</td>
            <td>
              <nobr>
              <action-button :action="'edit'" :to="{ name: 'scm.material.edit', params: { materialId: material.id } }"></action-button>
              <action-button @click="confirmDelete(material.id)" :action="'delete'"></action-button>
              </nobr>
            </td>
            
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && materials?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!materials?.data?.length" class="relative h-[250px]">
            <tr v-if="isLoading">
              <!-- <td colspan="7">Loading...</td> -->
            </tr>
            <tr v-else-if="isTableLoading">
                <td colspan="7">
                  <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                </td>
            </tr>
            <tr v-else-if="!materials?.data?.length">
              <td colspan="7">No Datas found.</td>
            </tr>
        </tfoot>
      </table>
    </div>
        <Paginate :data="materials" to="scm.material.index" :page="page"></Paginate>
  </div>
</template>