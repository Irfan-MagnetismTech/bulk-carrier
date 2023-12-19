<script setup>
import Error from "../Error.vue";
import {onMounted, ref, watch, watchEffect, getCurrentInstance } from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";
import useAccountCommonApiRequest from "../../composables/accounts/useAccountCommonApiRequest";
import ErrorComponent from '../utils/ErrorComponent.vue';
import RemarksComponent from "../utils/RemarksComponent.vue";
import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();
const { allSalaryHeadLists, getSalaryHead, isLoading } = useAccountCommonApiRequest();

const { emit } = getCurrentInstance();

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  page: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

function accAdministrativeSalaryLines() {
  let obj = {
    particular: '',
    amount: '',
    isParticularDuplicate: false
  };
  props.form.accSalaryLines.push(obj);
}

function removeAdministrativeSalaryLines(index){
  props.form.accSalaryLines.splice(index, 1);
}

watch(
    () => props.form,
    (newEntries, oldEntries) => {

      if(newEntries?.accSalaryLines?.length > 0) {
        let total_amount = 0.0;
        newEntries?.accSalaryLines?.forEach((item) => {
          if(item.amount) {
            total_amount += parseFloat(item.amount);
          }
        });
        if(!isNaN(total_amount)) {
          props.form.total_salary = total_amount;
        }
      }
    },
    { deep: true }
);

function calcTotalAdjustmentAmount(){
  props.form.adjustment_amount = parseFloat(props.form.total_bill_amount + props.form.total_cash_amount);
}

function setAdvanceAdjustmentFile($e,index){
  props.form.accAdvanceAdjustmentLines[index].attachment = $e.target.files[0];
}

watch(() => allSalaryHeadLists.value, (value) => {
  if(value){
    props.form.accSalaryLines = [];
    allSalaryHeadLists.value?.forEach((item,index) => {
      let obj = {
        particular: item?.name,
        amount: '',
        isParticularDuplicate: false
      }
      props.form.accSalaryLines.push(obj);
    });
  }
});

onMounted(() => {
  watchEffect(() => {
    if(props.page === "create"){
      getSalaryHead(null,props.form.business_unit);
    }
  });
});

</script>

<template>
  <ErrorComponent :errors="errors"></ErrorComponent>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
<!--      <label class="block w-full mt-2 text-sm">-->
<!--        <span class="text-gray-700 dark-disabled:text-gray-300"> Month-Year <span class="text-red-500">*</span></span>-->
<!--        <input type="month" v-model="form.year_month" class="form-input" id="monthInput" name="monthInput" required>-->
<!--      </label>-->
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-3/6 mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300"> Month-Year <span class="text-red-500">*</span></span>
      <input type="month" v-model="form.year_month" class="form-input" id="monthInput" name="monthInput" required>
    </label>
    <label class="block w-3/6 mt-2 text-sm"></label>
    <label class="block w-3/6 mt-2 text-sm"></label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <RemarksComponent v-model.trim="form.remarks" :maxlength="500" :fieldLabel="'Remarks'"></RemarksComponent>
  </div>
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Particular List<span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="px-4 py-3 align-bottom w-4/6">Particular <span class="text-red-500">*</span></th>
            <th class="px-4 py-3 align-bottom">Amount <span class="text-red-500">*</span></th>
            <th class="px-4 py-3 text-center align-bottom">Action</th>
          </tr>
          </thead>
          <tbody v-if="form?.accSalaryLines?.length" class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(salaryLine, index) in form.accSalaryLines" :key="salaryLine.id">
            <td class="px-1 py-1">
              <div style="position: relative;">
              <input
                  type="text"
                  v-model.trim="form.accSalaryLines[index].particular"
                  placeholder="Particular"
                  class="form-input"
                  autocomplete="off"
                  required
              />
              <span
                  v-show="salaryLine.isParticularDuplicate"
                  class="text-yellow-600 pl-1"
                  title="Duplicate Particular"
                  v-html="icons.ExclamationTriangle"
                  style="position: absolute; top: 50%; transform: translateY(-50%); right: 5px;"
              ></span>
              </div>
            </td>
            <td class="px-1 py-1">
              <input type="number" step=".01" min="1" v-model.trim="form.accSalaryLines[index].amount" class="form-input !text-right" autocomplete="off" required />
            </td>
            <td class="px-1 py-1 text-center">
              <button v-if="index!==0" type="button" @click="removeAdministrativeSalaryLines(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button>
              <button v-else type="button" @click="accAdministrativeSalaryLines()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </td>
          </tr>
          <tr class="text-gray-700 dark-disabled:text-gray-400">
            <td class="font-bold !text-right">Total Amount</td>
            <td class="px-1 py-1 font-bold text-right">
              <input type="number" step=".01" v-model.trim="form.total_salary" class="block w-full rounded form-input vms-readonly-input !text-right" readonly>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!form.accSalaryLines?.length">
          <tr v-if="isLoading">
            <td colspan="3">Loading...</td>
          </tr>
          <tr v-else-if="!form.accSalaryLines?.length">
            <td colspan="3">No data found.</td>
          </tr>
          </tfoot>
        </table>
    </fieldset>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-2 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
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
</style>