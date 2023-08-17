
<script setup>
import { onMounted, watchEffect, watch, ref } from 'vue';
import Title from '../../../services/title';
import useSLotPartner from '../../../composables/dataencoding/useSlotPartner';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Heading from '../../../components/Heading.vue';
import Paginate from '../../../components/utils/paginate.vue';
import useDebouncedRef from "../../../composables/useDebouncedRef";
import useSlotPartner from "../../../composables/dataencoding/useSlotPartner";
import useTableExportExcel from "../../../services/tableExportExcel";

const { tableToExcel } = useTableExportExcel();

const props = defineProps({
    page: {
        type: Number,
        default: 1,
    },
})
const { partners, isLoading, getSlotPartner, slotPartnerCode, getSlotPartnerByNameOrCode, deleteSlotPartner } = useSLotPartner();
const { setTitle } = Title();

setTitle('Slot Partners');

// Code for global search starts here
const columns = ["name", "code", "principal_office_address", "country", "type"];
const searchKey = useDebouncedRef('', 800);
const table = "slot_partners";

const partnerTypes = [
    { name: "MLO", value: "MLO" },
    { name: "NVOCC", value: "NVOCC" },
    { name: "FEEDER", value: "FEEDER" },
    { name: "FREIGHT FORWARDER", value: "FRD" },
    { name: "OTHERS", value: "OTHERS" }];

const searchParams = ref({
  partner_code_name: null,
  code: '',
  partner_type: null,
  type: '',
  page_view_type: 'Paginate',
});

function clearFormData(){
  searchParams.value = {
    partner_code_name: null,
    code: '',
    partner_type: null,
    type: '',
    page_view_type: 'Paginate',
  };
}

watch(searchKey, newQuery => {
  getSlotPartner(props.page, columns, searchKey.value, table);
});
// Code for global search end here

function fetchSlotPartner(search, loading) {
  getSlotPartnerByNameOrCode(search);
}

onMounted(() => {
  watchEffect(() => {
    if(searchParams.value.partner_code_name){
      searchParams.value.code = searchParams.value.partner_code_name.code;
    }

    if(searchParams.value.partner_type){
      searchParams.value.type = searchParams.value.partner_type.value;
    }

    getSlotPartner(props.page, searchParams.value);
  });
});
</script>
<template>
    <!-- Heading -->
    <heading label="Slot Partners" type="create" :to="{ name: 'dataencoding.slot-partner.create' }"></heading>

  <div class="w-full flex items-center justify-between mb-2 select-none">
    <fieldset class="w-full grid grid-cols-5 gap-1 px-2 pb-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Search Slot Partner</legend>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Code/Name</label>
        <v-select placeholder="--Choose an option--" :options="slotPartnerCode" @search="fetchSlotPartner" v-model="searchParams.partner_code_name" label="code_name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        <input type="hidden" v-model="searchParams.code">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Type</label>
        <v-select placeholder="--Choose an option--" :options="partnerTypes" v-model="searchParams.partner_type" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        <input type="hidden" v-model="searchParams.type">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Page View</label>
        <select v-model="searchParams.page_view_type" id="" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="Paginate">Paginate</option>
          <option value="All">All</option>
        </select>
      </div>
      <div>
        <label for="">&nbsp;</label>
        <button @click="clearFormData" class="w-full flex items-center justify-center px-2 py-2 mt-1 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Clear</span>
        </button>
      </div>
      <div>
        <label for="">&nbsp;</label>
        <button type="button" @click="tableToExcel('slot-partner-list-table','slot-partner-list')" class="w-full bg-gray-700 flex items-center justify-center px-2 py-2 mt-1 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          <span>Download Excel</span>
        </button>
      </div>
    </fieldset>
  </div>

<!--  <div class="flex items-center justify-between mb-2 select-none">-->
<!--    <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Showing {{ partners?.from }}-{{ partners?.to }} of {{ partners?.total }}</span>-->
<!--    &lt;!&ndash; Search &ndash;&gt;-->
<!--    <div class="relative w-full">-->
<!--      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">-->
<!--        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />-->
<!--      </svg>-->
<!--      <input type="text" v-model.trim="searchKey" placeholder="Search..." class="search" />-->
<!--    </div>-->
<!--  </div>-->
    <!-- Table -->
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap" id="slot-partner-list-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Country</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody v-if="(partners?.data ? partners?.data?.length : partners?.length)">
                    <tr v-for="(partnerData, index) in (partners?.data ? partners?.data : partners)" :key="partnerData?.id">
                        <td>{{ (partners?.from) ? partners?.from + index : index+1 }}</td>
                        <td>{{ partnerData?.code }}</td>
                        <td>{{ partnerData?.name }}</td>
                        <td>{{ partnerData?.country }}</td>
                        <td>{{ partnerData?.type }}</td>
                        <td class="items-center justify-center space-x-2 text-gray-600">
                            <action-button :action="'show'" :to="{ name: 'dataencoding.slot-partner.show', params: { partnerId: partnerData.id } }"></action-button>
                            <action-button :action="'edit'" :to="{ name: 'dataencoding.slot-partner.edit', params: { partnerId: partnerData.id } }"></action-button>
                            <action-button @click="deleteSlotPartner(partnerData.id)" :action="'delete'"></action-button>
                          <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: partnerData.subject_type,subject_id: partnerData.id } }"></action-button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!(partners?.data ? partners?.data?.length : partners?.length)" class="bg-white dark:bg-gray-800">
                    <tr v-if="isLoading">
                        <td colspan="6">Loading...</td>
                    </tr>
                    <tr v-else-if="!(partners?.data ? partners?.data?.length : partners?.length)">
                        <td colspan="6">No slot partner found.</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Pagination -->
        <Paginate :data="partners" to="dataencoding.slot-partner.index" :page="page"></Paginate>
    </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
    .tab {
        @apply p-2.5 text-xs;
    }
    thead tr {
        @apply font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
    }
    th {
        @apply tab text-center;
    }
    tbody {
        @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
    }
    tbody tr {
        @apply text-gray-700 dark:text-gray-400;
    }
    tbody tr td {
        @apply tab text-center;
    }
    tfoot td {
        @apply tab text-center;
    }

  table, th,td{
    @apply border border-collapse;
  }
  .search {
    @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
  }

  >>> {
    --vs-controls-color: #374151;
    --vs-border-color: #4b5563;

    --vs-dropdown-bg: #282c34;
    --vs-dropdown-color: #eeeeee;
    --vs-dropdown-option-color: #eeeeee;

    --vs-selected-bg: #664cc3;
    --vs-selected-color: #374151;

    --vs-search-input-color: #4b5563;

    --vs-dropdown-option--active-bg: #664cc3;
    --vs-dropdown-option--active-color: #eeeeee;
  }
}
</style>
