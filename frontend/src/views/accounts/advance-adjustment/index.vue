<script setup>
import {onMounted, ref, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useAdvanceAdjustment from "../../../composables/accounts/useAdvanceAdjustment";
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

const { advanceAdjustments, getAdvanceAdjustments, deleteAdvanceAdjustment, isLoading, isTableLoading} = useAdvanceAdjustment();
const debouncedValue = useDebouncedRef('', 800);
const { setTitle } = Title();
setTitle('Advance Adjustments');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

let filterOptions = ref({
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "isFilter": false,
  "page": props.page,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "adjustment_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Adjustment Date",
      "filter_type": "input"
    },
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
      "relation_name": 'accCashRequisition',
      "field_name": "id",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Cash Requisition No.",
      "filter_type": "input"
    },
    {
      "relation_name": 'accCashRequisition',
      "field_name": "total_amount",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Requisition Amount",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "adjustment_amount",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Adjustment Amount",
      "filter_type": "input"
    },
  ]
});

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
      deleteAdvanceAdjustment(id);
    }
  })
}

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'acc.advance-adjustments.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getAdvanceAdjustments(filterOptions.value)
      .then(() => {
        paginatedPage.value = filterOptions.value.page;
        const customDataTable = document.getElementById("customDataTable");

        if (customDataTable) {
          tableScrollWidth.value = customDataTable.scrollWidth;
        }
      })
      .catch((error) => {
        console.error("Failed to Load:", error);
      });
  });
  filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = useDebouncedRef('', 800);
  });
});
</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700"> Advance Adjustment List </h2>
    <default-button :title="'Create Advance Adjustment'" :to="{ name: 'acc.advance-adjustments.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
        <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">
                <tr v-for="(advanceAdjustment, index) in advanceAdjustments?.data" :key="index">
                  <td> {{ (paginatedPage  - 1) * filterOptions.items_per_page + index + 1 }} </td>
                  <td> {{ advanceAdjustment?.adjustment_date }} </td>
                  <td> {{ advanceAdjustment?.costCenter?.name }} </td>
                  <td> {{ advanceAdjustment?.accCashRequisition?.id }} </td>
                  <td> {{ advanceAdjustment?.accCashRequisition?.total_amount }} </td>
                  <td> {{ advanceAdjustment?.adjustment_amount }} </td>
                <td>
                  <span :class="advanceAdjustment?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">
                    {{ advanceAdjustment?.business_unit }}
                  </span>
                </td>
                <td>
                  <nobr>
                    <action-button :action="'show'" :to="{ name: 'acc.advance-adjustments.show', params: { advanceAdjustmentId: advanceAdjustment?.id } }"></action-button>
                    <action-button :action="'edit'" :to="{ name: 'acc.advance-adjustments.edit', params: { advanceAdjustmentId: advanceAdjustment?.id } }"></action-button>
                    <action-button @click="confirmDelete(advanceAdjustment?.id)" :action="'delete'"></action-button>
                  </nobr>
                </td>
              </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && advanceAdjustments?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!advanceAdjustments?.data?.length">
          <tr v-if="isLoading">
            <td colspan="8"></td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="8">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
          </tr>
          <tr v-else-if="!advanceAdjustments?.data?.length">
            <td colspan="8">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="advanceAdjustments" to="acc.advance-adjustments.index" :page="page"></Paginate>
  </div>
</template>