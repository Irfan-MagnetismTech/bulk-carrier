
<script setup>
import { onMounted, watchEffect, ref } from 'vue';
import Title from '../../../services/title';
import useChargeType from '../../../composables/dataencoding/useChargeType';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Heading from '../../../components/Heading.vue';
import Paginate from '../../../components/utils/paginate.vue';

const props = defineProps({
    page: {
        type: Number,
        default: 1,
    },
})
const { chargeTypes, isLoading, chargeTypeData, getChargeTypeData, chargeTypeCategory, getChargeTypes, deleteChargeType } = useChargeType();
const { setTitle } = Title();

setTitle('Revenue Heads');

function fetchChargeTypeData(search, loading) {
  getChargeTypeData(search);
}

const searchParams = ref({
  code_name: null,
  code: '',
  charge_name: null,
  name: '',
  category_name: null,
  category: '',
});

function clearFormData(){
  searchParams.value = {
    code_name: null,
    code: '',
    charge_name: null,
    name: '',
    category_name: null,
    category: '',
  };
}

onMounted(() => {
  watchEffect(() => {
    if(searchParams.value.code_name){
      searchParams.value.code = searchParams.value.code_name.code;
    }

    if(searchParams.value.charge_name){
      searchParams.value.name = searchParams.value.charge_name.name;
    }

    if(searchParams.value.category_name){
      searchParams.value.category = searchParams.value.category_name.category;
    }

    getChargeTypes(props.page, searchParams.value);
  });

});
</script>
<template>
    <!-- Heading -->
    <heading label="Revenue Heads" type="create" :to="{ name: 'dataencoding.chargetypes.create' }"></heading>

  <div class="w-full flex items-center justify-between mb-2 select-none">
    <fieldset class="w-full grid grid-cols-4 gap-1 px-2 pb-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Search Revenue Head</legend>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">ISO Code</label>
        <v-select placeholder="--Choose an option--" :options="chargeTypeData" @search="fetchChargeTypeData" v-model="searchParams.code_name" label="code" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        <input type="hidden" v-model="searchParams.code">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Name</label>
        <v-select placeholder="--Choose an option--" :options="chargeTypeData" @search="fetchChargeTypeData" v-model="searchParams.charge_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        <input type="hidden" v-model="searchParams.name">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Category</label>
        <v-select placeholder="--Choose an option--" :options="chargeTypeCategory" @search="fetchChargeTypeData" v-model="searchParams.category_name" label="category" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        <input type="hidden" v-model="searchParams.category">
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
    </fieldset>
  </div>
    <!-- Table -->
    <div class="w-full mb-8 overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody v-if="(chargeTypes?.data ? chargeTypes?.data?.length : chargeTypes?.length)">
                    <tr v-for="(chargeType, index) in (chargeTypes?.data ? chargeTypes?.data : chargeTypes)" :key="chargeType?.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ chargeType?.code }}</td>
                        <td>{{ chargeType?.name }}</td>
                        <td>{{ chargeType?.category  }}</td>
                        <td>{{ chargeType?.remarks }}</td>
                        <td class="items-center justify-center space-x-2 text-gray-600">
                            <action-button :action="'show'" :to="{ name: 'dataencoding.chargetypes.show', params: { chargeTypeId: chargeType.id } }"></action-button>
                            <action-button :action="'edit'" :to="{ name: 'dataencoding.chargetypes.edit', params: { chargeTypeId: chargeType.id } }"></action-button>
                            <action-button @click="deleteChargeType(chargeType.id)" :action="'delete'"></action-button>
                          <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: chargeType.subject_type,subject_id: chargeType.id } }"></action-button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!(chargeTypes?.data ? chargeTypes?.data?.length : chargeTypes?.length)" class="bg-white dark:bg-gray-800">
                    <tr v-if="isLoading">
                        <td colspan="8">Loading...</td>
                    </tr>
                    <tr v-else-if="!(chargeTypes?.data ? chargeTypes?.data?.length : chargeTypes?.length)">
                        <td colspan="8">No charge types found.</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Pagination -->
        <Paginate :data="chargeTypes" to="dataencoding.chargetypes.index" :page="page"></Paginate>
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
