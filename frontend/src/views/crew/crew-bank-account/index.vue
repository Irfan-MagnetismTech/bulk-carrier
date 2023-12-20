<script setup>
import {onMounted, ref, watchEffect, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
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

const icons = useHeroIcon();
const router = useRouter();
import env from '../../../config/env';
import useCrewBankAccount from "../../../composables/crew/useCrewBankAccount";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { crewBankAccounts, getCrewBankAccounts, deleteCrewBankAccount, isLoading, isTableLoading } = useCrewBankAccount();

const debouncedValue = useDebouncedRef('', 800);

const { setTitle } = Title();

setTitle('Crew Bank Accounts');

const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": "crwCrew",
      "field_name": "full_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Crew Name",
      "filter_type": "input"
    },
    {
      "relation_name": "crwCrew",
      "field_name": "pre_mobile_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Crew Contact",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "bank_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Bank Name",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "account_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Account Name",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "account_number",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Account Number",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "is_active",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Status",
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
      deleteCrewBankAccount(id);
    }
  })
}

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'crw.crewBankAccounts.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }

    getCrewBankAccounts(filterOptions.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Crew Bank Account List</h2>
    <default-button :title="'Create Item'" :to="{ name: 'crw.crewBankAccounts.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">

      <table class="w-full whitespace-no-wrap" >
        <FilterComponent :filterOptions = "filterOptions"/>
          <tbody>
            <tr v-for="(crewBankAccount,index) in crewBankAccounts?.data" :key="index">
              <td> {{ index + 1 }} </td>
              <td> {{ crewBankAccount?.crwCrew?.full_name }} </td>
              <td> {{ crewBankAccount?.crwCrew?.pre_mobile_no }} </td>
              <td> {{ crewBankAccount?.bank_name }} </td>
              <td> {{ crewBankAccount?.account_name }} </td>
              <td> {{ crewBankAccount?.account_number }} </td>
              <td>
                <span v-if="!crewBankAccount?.is_active" class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">Deactive</span>
                <span v-else class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Active</span>
              </td>
              <!-- <td>
                <span :class="crewBankAccount?.is_active === 1 ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">
                  {{ crewBankAccount?.is_active === 1 ? "Active" : "Deactive" }}
                </span>
              </td> -->
              <td>
                <span :class="crewBankAccount?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crewBankAccount?.business_unit }}</span>
              </td>
              <td>
                <nobr>
                  <action-button :action="'edit'" :to="{ name: 'crw.crewBankAccounts.edit', params: { crewBankAccountId: crewBankAccount?.id } }"></action-button>
                  <action-button @click="confirmDelete(crewBankAccount?.id)" :action="'delete'"></action-button>
                </nobr>
              </td>
            </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && crewBankAccounts?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!crewBankAccounts?.data?.length" class="relative h-[250px]">
            <tr v-if="isLoading">
              <td colspan="11"></td>
            </tr>
            <tr v-else-if="isTableLoading">
              <td colspan="11">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>
              </td>
            </tr>
            <tr v-else-if="!crewBankAccounts?.data?.data?.length">
              <td colspan="11">No data found.</td>
            </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="crewBankAccounts" to="crw.crewBankAccounts.index" :page="page"></Paginate>
  </div>
</template>