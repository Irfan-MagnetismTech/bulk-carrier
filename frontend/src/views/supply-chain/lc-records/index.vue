<script setup>
import {onMounted, watchEffect,watch,ref, watchPostEffect} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useLcRecord from "../../../composables/supply-chain/useLcRecord";
import useHelper from "../../../composables/useHelper.js";
import Title from "../../../services/title";
import useDebouncedRef from '../../../composables/useDebouncedRef';
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";

const { getLcRecords, lcRecords, deleteLcRecord, isLoading,isTableLoading } = useLcRecord();
const { numberFormat } = useHelper();
const { setTitle } = Title();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

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

setTitle('Lc Records');


let filterOptions = ref({
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "lc_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "LC No",
      "filter_type": "input" 
    },
    {
      "relation_name": null,
      "field_name": "lc_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "LC Date",
      "filter_type": "input" 
    },
    {
      "relation_name": null,
      "field_name": "expire_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Expire Date",
      "filter_type": "input" 
    },
    {
      "relation_name": null,
      "field_name": "weight",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Weight",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "invoice_value",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Invoice Value",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "assessment_value",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Assessment Value",
      "filter_type": "input"
    }
  ]
});


const currentPage = ref(1);
const paginatedPage = ref(1);

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);




onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'scm.lc-records.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getLcRecords(filterOptions.value)
      .then(() => {
      paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");
      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching PR:", error);
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
            deleteLcRecord(id);
          }
        })
      }
</script>

<template>
  <!-- Heading -->
 
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">LC Records List</h2>
    <default-button :title="'Create LC Records'" :to="{ name: 'scm.lc-records.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">
            <tr v-for="(lcRecord,index) in (lcRecords?.data ? lcRecords?.data : lcRecords)" :key="index">
              <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
              <td>{{ lcRecord?.lc_no }}</td>
              <td>{{ lcRecord?.lc_date }}</td>
              <td>{{ lcRecord?.expire_date }}</td>
              <td>{{ lcRecord?.weight }}</td>
              <td>{{ lcRecord?.invoice_value }}</td>
              <td>{{ lcRecord?.assessment_value }}</td>
              <td>
                <span :class="lcRecord?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ lcRecord?.business_unit }}</span>
              </td>
              <td>
                <nobr>
                  <action-button :action="'edit'" :to="{ name: 'scm.lc-records.edit', params: { lcRecordId: lcRecord.id } }"></action-button>
                  <action-button @click="confirmDelete(lcRecord.id)" :action="'delete'"></action-button>
                </nobr>
              </td>
            </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && lcRecords?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!lcRecords?.data?.length" class="bg-white dark-disabled:bg-gray-800">
            <tr v-if="isLoading">
            </tr>
            <tr v-else-if="isTableLoading">
                <td colspan="7">
                  <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                </td>
            </tr>
            <tr v-else-if="!lcRecords?.data?.length">
              <td colspan="7">No Data found.</td>
            </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="lcRecords" to="scm.lc-records.index" :page="page"></Paginate>
  </div>
  <!-- Heading -->
  
  

  <ErrorComponent :errors="errors"></ErrorComponent>  
</template>