<script setup>
import Error from "../Error.vue";
import useVessel from "../../composables/operations/useVessel";
import {onMounted, ref, watch, watchEffect, getCurrentInstance } from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";
import useAccountCommonApiRequest from "../../composables/accounts/useAccountCommonApiRequest";
import ErrorComponent from '../utils/ErrorComponent.vue';
import RemarksComponent from "../utils/RemarksComponent.vue";
import { formatMonthYear, formatDate } from "../../utils/helper.js";
import useHeroIcon from "../../assets/heroIcon";
import useCashRequisition from "../../composables/accounts/useCashRequisition";
const { vessels, searchVessels } = useVessel();
const icons = useHeroIcon();

const { allCashRequisitionLists, getCashRequisition, isLoading } = useAccountCommonApiRequest();
const { cashRequisition, showCashRequisition } = useCashRequisition();

const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);
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

watch(() => props.form.acc_cash_requisition_id, (value) => {
  if(value){
    showCashRequisition(props.form.acc_cash_requisition_id);
  }
});

watch(() => cashRequisition.value, (value) => {
  if(value){
    props.form.acc_requisition_date = formatDate(value?.applied_date);
    props.form.acc_requisition_amount = value?.total_amount;
    props.form.acc_cost_center_name = value?.costCenter?.name;
    props.form.acc_cost_center_id = value?.costCenter?.id;
  }
});

function accAdvanceAdjustmentLines() {
  let obj = {
    acc_advance_adjustment_id: '',
    particular: '',
    amount: '',
    attachment: null,
    remarks: '',
    isParticularDuplicate: false
  };
  props.form.accAdvanceAdjustmentLines.push(obj);
}

function removeAccAdvanceAdjustmentLines(index){
  props.form.accAdvanceAdjustmentLines.splice(index, 1);
}

watch(
    () => props.form,
    (newEntries, oldEntries) => {

      if(newEntries?.accAdvanceAdjustmentLines?.length > 0) {
        let total_bill_amount = 0.0;
        newEntries?.accAdvanceAdjustmentLines?.forEach((item) => {
          if(item.amount) {
            total_bill_amount += parseFloat(item.amount);
          }
        });
        if(!isNaN(total_bill_amount)) {
          props.form.total_bill_amount = total_bill_amount;
        }
        props.form.adjustment_amount = parseFloat(props.form.total_bill_amount) + parseFloat(props.form.cash_amount);
      }
    },
    { deep: true }
);

function calcTotalAdjustmentAmount(){
  props.form.adjustment_amount = parseFloat(props.form.total_bill_amount + props.form.cash_amount);
}

function setAdvanceAdjustmentFile($e,index){
  props.form.accAdvanceAdjustmentLines[index].attachment = $e.target.files[0];
}

onMounted(() => {
  watchEffect(() => {
    getCashRequisition(null,props.form.business_unit);
  });
});

</script>

<template>
  <ErrorComponent :errors="errors"></ErrorComponent>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Adjustment Date <span class="text-red-500">*</span></span>
        <VueDatePicker v-model.trim="form.adjustment_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Cash Requisition No. <span class="text-red-500">*</span></span>
        <v-select :options="allCashRequisitionLists" placeholder="--Choose an option--" :loading="isLoading" v-model.trim="form.acc_cash_requisition_id" label="id" :reduce="allCashRequisitionLists => allCashRequisitionLists.id" class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.acc_cash_requisition_id" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Requisition Date <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.acc_requisition_date" class="form-input vms-readonly-input" autocomplete="off" readonly />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Requisition Amount <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.acc_requisition_amount" class="form-input vms-readonly-input" autocomplete="off" readonly />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Cost Center Name <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.acc_cost_center_name" class="form-input vms-readonly-input" autocomplete="off" readonly />
      </label>
    </div>
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300"> Advance Adjustment Lines <span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="px-4 py-3 align-bottom">Particular <span class="text-red-500">*</span></th>
            <th class="px-4 py-3 align-bottom">Remarks</th>
            <th class="px-4 py-3 align-bottom w-48">Attachment</th>
            <th class="px-4 py-3 align-bottom w-36">Amount <span class="text-red-500">*</span></th>
            <th class="px-4 py-3 text-center align-bottom">Action</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(accAdvanceAdjustmentLine, index) in form.accAdvanceAdjustmentLines" :key="accAdvanceAdjustmentLine.id">
            <td class="px-1 py-1">
              <div style="position: relative;">
              <input
                  type="text"
                  v-model.trim="form.accAdvanceAdjustmentLines[index].particular"
                  placeholder="Particular"
                  class="form-input"
                  autocomplete="off"
                  required
              />
              <span
                  v-show="accAdvanceAdjustmentLine.isParticularDuplicate"
                  class="text-yellow-600 pl-1"
                  title="Duplicate Particular"
                  v-html="icons.ExclamationTriangle"
                  style="position: absolute; top: 50%; transform: translateY(-50%); right: 5px;"
              ></span>
              </div>
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.accAdvanceAdjustmentLines[index].remarks" placeholder="Remarks" class="form-input" autocomplete="off" />
            </td>
            <td class="px-1 py-1">
              <input type="file" @change="setAdvanceAdjustmentFile($event,index)" placeholder="Remarks" class="block form-input border border-gray-300 rounded-lg cursor-pointer" autocomplete="off" />
            </td>
            <td class="px-1 py-1">
              <input type="number" step=".01" v-model.trim="form.accAdvanceAdjustmentLines[index].amount" class="form-input !text-right" autocomplete="off" required />
            </td>
            <td class="px-1 py-1 text-center">
              <button v-if="index!==0" type="button" @click="removeAccAdvanceAdjustmentLines(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button>
              <button v-else type="button" @click="accAdvanceAdjustmentLines()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </td>
          </tr>
          <tr class="text-gray-700 dark-disabled:text-gray-400">
            <td class="font-bold !text-right" colspan="3">Total Bill Amount</td>
            <td class="px-1 py-1 font-bold text-right">
              <input type="number" step=".01" v-model.trim="form.total_bill_amount" class="block w-full rounded form-input vms-readonly-input !text-right" readonly>
            </td>
            <td class="px-1 py-1 font-bold text-right"></td>
          </tr>
          <tr class="text-gray-700 dark-disabled:text-gray-400">
            <td class="px-1 py-1 font-bold !text-right" colspan="3">Total Cash Amount</td>
            <td class="px-1 py-1 font-bold text-right">
              <input type="number" step=".01" @input="calcTotalAdjustmentAmount" v-model.trim="form.cash_amount" class="block w-full rounded form-input !text-right">
            </td>
            <td class="px-1 py-1 font-bold text-right"></td>
          </tr>
          <tr class="text-gray-700 dark-disabled:text-gray-400">
            <td class="px-1 py-1 font-bold !text-right" colspan="3">Adjusted Amount</td>
            <td class="px-1 py-1 font-bold text-right">
              <input type="number" step=".01" v-model.trim="form.adjustment_amount" class="block w-full rounded form-input vms-readonly-input !text-right" readonly>
            </td>
            <td class="px-1 py-1 font-bold text-right"></td>
          </tr>
          </tbody>
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