<script setup>
import {onMounted, ref, watchEffect, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useAgencyBill from "../../../composables/crew/useAgencyBill";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from "../../../store";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import FileExportButton from "../../../components/buttons/FileExportButton.vue";

const icons = useHeroIcon();
const router = useRouter();
import env from '../../../config/env';

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const rightAlign = [];
const leftAlign = [1];
const { agencyBills, getAgencyBills, deleteAgencyBill, isLoading, isTableLoading } = useAgencyBill();
const debouncedValue = useDebouncedRef('', 800);
const { setTitle } = Title();
setTitle('Agency Bill');
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": 'crwAgency',
      "field_name": "agency_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Agency Name",
      "filter_type": "input"
    },
    {
      "relation_name": 'crwAgency',
      "field_name": "phone",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Contact No.",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "invoice_amount",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Billing Amount",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "invoice_currency",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Currency",
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
      deleteAgencyBill(id);
    }
  })
}

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'crw.agencyBills.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getAgencyBills(filterOptions.value)
        .then(() => {
          const customDataTable = document.getElementById("customDataTable");
          paginatedPage.value = filterOptions.value.page;
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
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Agency Bill List</h2>
    <div class="flex gap-2">
      <default-button :title="'Create Item'" :to="{ name: 'crw.agencyBills.create' }" :icon="icons.AddIcon"></default-button>
      <file-export-button
          :businessUnit="businessUnit"
          :pageOrientation="'l'"
          :fileName="'Agency Bill List'"
          :tableId="'crew-agency-bill'"
          :leftAlign="leftAlign"
          :rightAlign="rightAlign"
      ></file-export-button>
    </div>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" id="crew-agency-bill">
        <FilterComponent :filterOptions = "filterOptions"/>
          <tbody>
          <tr v-for="(bill,index) in agencyBills?.data" :key="index">
            <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
            <td class="!text-left">{{ bill?.crwAgency?.agency_name }}</td>
            <td>{{ bill?.crwAgency?.phone }}</td>
            <td class="!text-right">{{ bill?.invoice_amount }}</td>
            <td>{{ bill?.invoice_currency }}</td>
<!--            <td>Waiting</td>-->
            <td>
              <span :class="bill?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ bill?.business_unit }}</span>
            </td>
            <td>
              <nobr>
                <action-button :action="'show'" :to="{ name: 'crw.agencyBills.show', params: { agencyBillId: bill?.id } }"></action-button>
                <action-button :action="'edit'" :to="{ name: 'crw.agencyBills.edit', params: { agencyBillId: bill?.id } }"></action-button>
                <action-button @click="confirmDelete(bill?.id)" :action="'delete'"></action-button>
              </nobr>
            </td>
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && agencyBills?.data?.length"></LoaderComponent>
          </tbody>
        <tfoot v-if="!agencyBills?.data?.length" class="relative h-[250px]">
        <tr v-if="isLoading">
          <td colspan="7"></td>
        </tr>
        <tr v-else-if="isTableLoading">
          <td colspan="7">
            <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>
          </td>
        </tr>
        <tr v-else-if="!agencyBills?.data?.length">
          <td colspan="7">No data found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="agencyBills" to="crw.agencyBills.index" :page="page"></Paginate>
  </div>
</template>