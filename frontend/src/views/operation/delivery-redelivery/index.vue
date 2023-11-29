<script setup>
import {onMounted, ref,watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import useDeliveryRedelivery from '../../../composables/operations/useDeliveryRedelivery';
import Store from "../../../store";
import moment from 'moment';
import useHelper from "../../../composables/useHelper";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import FilterComponent from "../../../components/utils/FilterComponent.vue";


const { deliveryRedeliveries, getDeliveryRedeliverys, deleteDeliveryRedelivery, isLoading, isTableLoading } = useDeliveryRedelivery();
const icons = useHeroIcon();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const debouncedValue = useDebouncedRef('', 800);
const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Delivery Redelivery List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const { numberFormat } = useHelper();

let showFilter = ref(false);

function swapFilter() {
  showFilter.value = !showFilter.value;
}

watch(

	() => businessUnit.value,
	(newBusinessUnit, oldBusinessUnit) => {
		if (newBusinessUnit !== oldBusinessUnit) {
		router.push({ name: "ops.configurations.cargo-types.index", query: { page: 1 } })
		}	
	}
);


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
        deleteDeliveryRedelivery(id);
    }
  })
}


let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": "opsChartererProfile",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Charterer Name",
      "filter_type": "input"
    },
    {
      "relation_name": "opsVessel",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Vessel Name",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "note_type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Note Type",
      "filter_type": "select",
      "select_options": [
          { value: "", label: "All" ,defaultSelected: true},
          { value: "Delivery", label: "Delivery" ,defaultSelected: false},
          { value: "Re-delivery", label: "Re-delivery",defaultSelected: false},
        ]
    },
    {
      "relation_name": null,
      "field_name": null,
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Amount (USD)",
      "filter_type": ""
    },
    {
      "relation_name": null,
      "field_name": null,
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Amount (BDT)",
      "filter_type": ""
    },
	]
});

const currentPage = ref(1);
const paginatedPage = ref(1);

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'ops.delivery-redelivery.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getDeliveryRedeliverys(filterOptions.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Delivery Redelivery List</h2>
    <default-button :title="'Create Delivery Redelivery'" :to="{ name: 'ops.delivery-redelivery.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
        <FilterComponent :filterOptions = "filterOptions"/>

          <tbody v-if="deliveryRedeliveries?.data?.length" class="relative">
              <tr v-for="(deliveryRedelivery, index) in deliveryRedeliveries.data" :key="deliveryRedelivery?.id">
                <td>{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
                  <td>{{ deliveryRedelivery?.opsChartererProfile?.name }}</td>
                  <td>{{ deliveryRedelivery?.opsVessel?.name }}</td>
                  
                  <td>{{ deliveryRedelivery?.note_type }}</td>
                  <td>
                    {{ numberFormat(deliveryRedelivery?.opsBunkers.reduce((accumulator, currentObject) => {
  return accumulator + currentObject.amount_usd;
}, 0)) }}
                  </td>
                  <td>
                  
                    {{ numberFormat(deliveryRedelivery?.opsBunkers.reduce((accumulator, currentObject) => {
  return accumulator + currentObject.amount_bdt;
}, 0)) }}

                  </td>
                  <td>
                    <span :class="deliveryRedelivery?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ deliveryRedelivery?.business_unit }}</span>

                  </td>

                  <td class="items-center justify-center space-x-1 text-gray-600">
                      <nobr>
                        <action-button :action="'show'" :to="{ name: 'ops.delivery-redelivery.show', params: { deliveryRedeliveryId: deliveryRedelivery.id } }"></action-button>
                        <action-button :action="'edit'" :to="{ name: 'ops.delivery-redelivery.edit', params: { deliveryRedeliveryId: deliveryRedelivery.id } }"></action-button>
                        <action-button @click="confirmDelete(deliveryRedelivery.id)" :action="'delete'"></action-button>
                      <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                      </nobr>
                  </td>
              </tr>
              <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && deliveryRedeliveries?.data?.length"></LoaderComponent>
          </tbody>
          
          <tfoot v-if="!deliveryRedeliveries?.data?.length" class="relative h-[250px]">
            <tr v-if="isLoading">
              <td colspan="9">Loading...</td>
            </tr>
            <tr v-else-if="isTableLoading">
                <td colspan="9">
                  <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                </td>
            </tr>
            <tr v-else-if="!deliveryRedeliveries?.data?.length">
              <td colspan="9">No data found.</td>
            </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="deliveryRedeliveries" to="ops.delivery-redelivery.index" :page="page"></Paginate>
  </div>
</template>
