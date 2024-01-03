<script setup>
import {onMounted, watchEffect,watch,ref, watchPostEffect} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useMovementRequisition from "../../../composables/supply-chain/useMovementRequisition";
import useHelper from "../../../composables/useHelper.js";
import Title from "../../../services/title";
import useDebouncedRef from '../../../composables/useDebouncedRef';
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import { useRouter } from 'vue-router';
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import moment from 'moment';

const { getMovementRequisitions, movementRequisitions, deleteMovementRequisition, isLoading,isTableLoading } = useMovementRequisition();
const { numberFormat } = useHelper();
const { setTitle } = Title();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const router = useRouter();
const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const critical = ['No','Yes'];

const icons = useHeroIcon();

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

setTitle('Movement Requisitions');
// Code for global search starts here

let filterOptions = ref({
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "ref_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Ref No",
      "filter_type": "input" 
    },
    {
      "relation_name": "fromWarehouse",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "From Warehouse",
      "filter_type": "input"
    },
    {
      "relation_name": "toWarehouse",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "To Warehouse",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Requested Date",
      "filter_type": "date"
    },
    {
      "relation_name": null,
      "field_name": "delivery_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Delivery Date",
      "filter_type": "date"
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
      router.push({ name: 'scm.movement-requisitions.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getMovementRequisitions(filterOptions.value)
      .then(() => {
        paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");
      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching SR:", error);
    });
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
            deleteMovementRequisition(id);
          }
        })
      }

      const navigateToSICreate = (SrId) => {
        const sr_id = SrId;
        const routeOptions = {
          name: 'scm.store-issues.create',
          query: {
            sr_id: sr_id,
          }
        };
        router.push(routeOptions);
      };
</script>

<template>
  <!-- Heading -->
 
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Movement Requisition List</h2>
    <default-button :title="'Create Movement Requisition'" :to="{ name: 'scm.movement-requisitions.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <!-- Table -->
  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
        
          <FilterComponent :filterOptions = "filterOptions"/>

          <tbody>
            <tr v-for="(movementRequisition,index) in (movementRequisitions?.data ? movementRequisitions?.data : movementRequisitions)" :key="index">
              <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
              <td>{{ movementRequisition?.ref_no }}</td>
              <td>{{ movementRequisition?.fromWarehouse?.name?? '' }}</td>
              <td>{{ movementRequisition?.toWarehouse?.name?? '' }}</td>
              <td>{{ movementRequisition?.date ? moment(movementRequisition?.date).format('DD-MM-YYYY') : null }}</td>
              <td>{{ movementRequisition?.delivery_date ? moment(movementRequisition?.delivery_date).format('DD-MM-YYYY') : null }}</td>
              <td>
                <span :class="movementRequisition?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ movementRequisition?.business_unit }}</span>
              </td>
              <td>
                <div class="grid grid-flow-col-dense gap-x-2">
                  <action-button :action="'show'" :to="{ name: 'scm.movement-requisitions.show', params: { movementRequisitionId: movementRequisition.id } }"></action-button>
                  <action-button :action="'edit'" :to="{ name: 'scm.movement-requisitions.edit', params: { movementRequisitionId: movementRequisition.id } }" v-if="(movementRequisition?.scmMos.length <= 0)"></action-button>
                  <action-button @click="confirmDelete(movementRequisition.id)" :action="'delete'"></action-button>
                </div>
              </td>
            </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && movementRequisitions?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!movementRequisitions?.data?.length" class="relative h-[250px]">
              <tr v-if="isLoading">
              </tr>
              <tr v-else-if="isTableLoading">
                  <td colspan="7">
                    <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                  </td>
              </tr>
              <tr v-else-if="!movementRequisitions?.data?.length">
                <td colspan="7">No Data found.</td>
              </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="movementRequisitions" to="scm.movement-requisitions.index" :page="page"></Paginate>
  </div>
  <!-- Heading -->
  
  

  <ErrorComponent :errors="errors"></ErrorComponent>  
</template>