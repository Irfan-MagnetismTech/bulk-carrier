<script setup>
import {onMounted, ref, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useFixedAsset from "../../../composables/accounts/useFixedAsset";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from "../../../store";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { fixedAssets, getFixedAssets, deleteFixedAsset, isLoading, isTableLoading} = useFixedAsset();
const debouncedValue = useDebouncedRef('', 800);
const { setTitle } = Title();
setTitle('Fixed Asset');
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

let filterOptions = ref({
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "isFilter": false,
  "page": props.page,
  "filter_options": [
    {
      "relation_name": 'costCenter',
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Cost Center",
      "filter_type": "input"
    },
    {
      "relation_name": 'scmMaterial',
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Asset Name",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "asset_tag",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Asset Tag",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": 'useful_life',
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Useful Life (Year)",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "depreciation_rate",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Depreciation Rate (%)",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "acquisition_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Acquisition Date",
      "filter_type": "input"
    },
  ]
});

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

const currentPage = ref(1);
const paginatedPage = ref(1);
let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

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
      deleteFixedAsset(id);
    }
  })
}

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'acc.fixed-assets.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getFixedAssets(filterOptions.value)
      .then(() => {
        paginatedPage.value = filterOptions.value.page;
        const customDataTable = document.getElementById("customDataTable");

        if (customDataTable) {
          tableScrollWidth.value = customDataTable.scrollWidth;
        }
      //  isTableLoader.value = true; 
      })
      .catch((error) => {
        console.error("Failed to Load:", error);
      });
  });

  // filterOptions.value.filter_options.forEach((option, index) => {
  //   filterOptions.value.filter_options[index].search_param = useDebouncedRef('', 800);
  // });
});
</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700"> Fixed Asset List </h2>
    <default-button :title="'Create Fixed Asset'" :to="{ name: 'acc.fixed-assets.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
        <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">
                <tr v-for="(fixedAsset, index) in fixedAssets?.data" :key="index">
                  <td> {{ (paginatedPage  - 1) * filterOptions.items_per_page + index + 1 }} </td>
                  <td class="text-left"> {{ fixedAsset?.costCenter?.name }} </td>
                  <td class="text-left"> {{ fixedAsset?.scmMaterial?.name }} </td>
                  <td class="text-left"> <nobr> {{ fixedAsset?.asset_tag }} </nobr> </td>
                  <td> {{ fixedAsset?.useful_life }} </td>
                  <td> {{ fixedAsset?.depreciation_rate }} </td>
                  <td> {{ fixedAsset?.acquisition_date }} </td>
                <td>
                  <span :class="fixedAsset?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">
                    {{ fixedAsset?.business_unit }}
                  </span>
                </td>
                <td>
                  <nobr>
                    <action-button :action="'edit'" :to="{ name: 'acc.fixed-assets.edit', params: { fixedAssetId: fixedAsset?.id } }"></action-button>
                    <action-button @click="confirmDelete(fixedAsset?.id)" :action="'delete'"></action-button>
                  </nobr>
                </td>
              </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && fixedAssets?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!fixedAssets?.data?.length">
          <tr v-if="isLoading">
            <td colspan="13"></td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="13">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
          </tr>
          <tr v-else-if="!fixedAssets?.data?.length">
            <td colspan="13">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="fixedAssets" to="acc.fixed-assets.index" :page="page"></Paginate>
  </div>
</template>