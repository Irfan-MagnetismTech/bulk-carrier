<script setup>
import {onMounted, ref, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import useCustomerInvoice from '../../../composables/operations/useCustomerInvoice';
import Store from "../../../store";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import useHelper from "../../../composables/useHelper";

const { customerInvoices, getCustomerInvoices, deleteCustomerInvoice, isLoading,isTableLoading ,errors } = useCustomerInvoice();
const { numberFormat } = useHelper();
const icons = useHeroIcon();
const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Customer Invoice List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

let filterOptions = ref({
  // "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": "opsCustomer",
      "field_name": "name_code",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Customer Name",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Invoiced Voyages",
      "filter_type": ""
    },
    
    {
      "relation_name": null,
      "field_name": "date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Date",
      "filter_type": "date",
    },
    
    {
      "relation_name": null,
      "field_name": "grand_total",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Grand Total",
      "filter_type": "input",
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
        deleteCustomerInvoice(id);
    }
  })
}

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'scm.store-requisitions.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getCustomerInvoices(filterOptions.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Customer Invoice List</h2>
    <default-button :title="'Customer Invoice'" :to="{ name: 'ops.customer-invoices.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
        <FilterComponent :filterOptions = "filterOptions"/>
          <tbody v-if="customerInvoices?.data?.length" class="relative">
              <tr v-for="(customerInvoice, index) in customerInvoices.data" :key="customerInvoice?.id">
                  <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
                  <td>{{ customerInvoice?.opsCustomer?.name_code }}</td>
                  <td>{{ customerInvoice?.voyage_name }}</td>
                  <td><nobr>{{ customerInvoice?.date }}</nobr></td>
                  <td>{{ numberFormat(customerInvoice?.grand_total) }}</td>
                  <!-- <td>
                    <span :class="customerInvoice?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ customerInvoice?.business_unit }}</span>
                  </td> -->
                  <td class="items-center justify-center space-x-1 text-gray-600">
                    <nobr>
                      <!-- <action-button :action="'show'" :to="{ name: 'ops.customer-invoices.show', params: { customerInvoiceId: customerInvoice.id } }"></action-button> -->
                      <action-button :action="'edit'" :to="{ name: 'ops.customer-invoices.edit', params: { customerInvoiceId: customerInvoice.id } }"></action-button>
                      <action-button @click="confirmDelete(customerInvoice.id)" :action="'delete'"></action-button>
                    </nobr>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
              </tr>
              <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && customerInvoices?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!customerInvoices?.data?.length" class="relative h-[250px]">
            <tr v-if="isLoading">
            </tr>
            <tr v-else-if="isTableLoading">
                <td colspan="8">
                  <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                </td>
            </tr>
            <tr v-else-if="!customerInvoices?.data?.length">
              <td colspan="8">No Data found.</td>
            </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="customerInvoices" to="ops.customer-invoices.index" :page="page"></Paginate>
  </div>
</template>
