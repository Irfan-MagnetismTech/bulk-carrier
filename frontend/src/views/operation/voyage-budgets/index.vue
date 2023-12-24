<script setup>
import {onMounted, ref, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import useVoyageBudget from '../../../composables/operations/useVoyageBudget';
import Store from "../../../store";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import {useRouter} from "vue-router";
import moment from "moment";
import useHelper from "../../../composables/useHelper";


const { voyageBudgets, getVoyageBudgets, deleteVoyageBudget, isLoading, isTableLoading, errors } = useVoyageBudget();
const icons = useHeroIcon();
const router = useRouter();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Voyage Budget List');
const { numberFormat } = useHelper();

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

let filterOptions = ref({
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "title",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Title",
      "filter_type": "input"
    },
    {
      "relation_name": "opsVoyage",
      "field_name": "voyage_sequence",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Voyage",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": null,
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Effective From",
      "filter_type": null
    },
    {
      "relation_name": null,
      "field_name": null,
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Effective Till",
      "filter_type": null
    },
    {
      "relation_name": null,
      "field_name": null,
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Total (BDT)",
      "filter_type": null
    },
   
  ]
});

const currentPage = ref(1);
const paginatedPage = ref(1);

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);




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
        deleteVoyageBudget(id);
    }
  })
}

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'ops.voyage-budgets.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getVoyageBudgets(filterOptions.value)
      .then(() => {
        paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching data.", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Voyage Budget List</h2>
    <default-button :title="'Charterer Invoice'" :to="{ name: 'ops.voyage-budgets.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap mb-5" >
        <FilterComponent :filterOptions = "filterOptions"/>
          <tbody v-if="voyageBudgets?.data?.length" class="relative">
              <tr v-for="(voyageBudget, index) in voyageBudgets.data" :key="voyageBudget?.id">
                  <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
                  <td>{{ voyageBudget?.title }}</td>
                  <td>{{ voyageBudget?.opsVoyage?.voyage_sequence }}</td>
                  <td>
                    <nobr>{{ voyageBudget?.effective_from ? moment(voyageBudget?.effective_from).format('DD-MM-YYYY') : null }}</nobr>
                  </td>
                  <td>
                    <nobr>{{ voyageBudget?.effective_till ? moment(voyageBudget?.effective_till).format('DD-MM-YYYY') : null }}</nobr>
                  </td>
                  <td class="!text-right">
                    {{ numberFormat((voyageBudget?.opsVoyageBudgetEntries.reduce((accumulator, currentObject) => {
  return accumulator + (currentObject.amount_bdt ? parseFloat(currentObject.amount_bdt) : 0);
}, 0)) || 0) }}
                  </td>
                  <td>
                    <span :class="voyageBudget?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ voyageBudget?.business_unit }}</span>
                  </td>
                  <td class="items-center justify-center space-x-1 text-gray-600">
                    <nobr>
                      <!-- <action-button :action="'show'" :to="{ name: 'ops.voyage-budgets.show', params: { voyageBudgetId: voyageBudget.id } }"></action-button> -->
                      <action-button :action="'edit'" :to="{ name: 'ops.voyage-budgets.edit', params: { voyageBudgetId: voyageBudget.id } }"></action-button>
                      <action-button @click="confirmDelete(voyageBudget.id)" :action="'delete'"></action-button>
                    </nobr>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
              </tr>
              <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && voyageBudgets?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!voyageBudgets?.data?.length" class="relative h-[250px]">
            <tr v-if="isLoading">
            </tr>
            <tr v-else-if="isTableLoading">
                <td colspan="7">
                  <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                </td>
            </tr>
            <tr v-else-if="!voyageBudgets?.data?.length">
              <td colspan="7">No Data found.</td>
            </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="voyageBudgets" to="ops.voyage-budgets.index" :page="page"></Paginate>
  </div>
</template>
