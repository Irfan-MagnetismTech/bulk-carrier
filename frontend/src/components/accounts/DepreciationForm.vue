<script setup>
import Error from "../Error.vue";
import useVessel from "../../composables/operations/useVessel";
import {onMounted, ref, watch, watchEffect, getCurrentInstance } from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";
import useAccountCommonApiRequest from "../../composables/accounts/useAccountCommonApiRequest";
import useMaterialReceiptReport from "../../composables/supply-chain/useMaterialReceiptReport";
import ErrorComponent from '../utils/ErrorComponent.vue';
import useFixedAsset from "../../composables/accounts/useFixedAsset";
const { vessels, searchVessels } = useVessel();

const { allCostCenterLists, getAccount, getCostCenter, isLoading } = useAccountCommonApiRequest();
const { filteredFixedAssets, searchFixedAsset } = useFixedAsset();
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

let allMaterialLists = ref([]);

// watch(() => props.form.acc_cost_center_name, (value) => {
//   if(value){
//     props.form.acc_cost_center_id = value?.id ?? '';
//   }
// });

watch(() => filteredFixedAssets.value, (newEntries, oldEntries) => {
  if(newEntries?.length > 0) {
    props.form.accDepreciationLines = [];
    filteredFixedAssets?.value?.forEach((item) => {
         let data = {
           asset_name: item?.scmMaterial?.name,
           asset_tag: item?.asset_tag,
           useful_life: item?.useful_life,
           depreciation_rate: item?.depreciation_rate,
           acquisition_cost: item?.acquisition_cost,
           acc_fixed_asset_id: item?.id,
           amount: item?.amount,
         };
         props.form.accDepreciationLines.push(data);
    });
  }
},{deep:true});

// watch(
//     () => props.form,
//     (newEntries, oldEntries) => {
//
//       if(newEntries?.fixedAssetCosts?.length > 0) {
//         let total_grand_amount = 0.0;
//         newEntries?.fixedAssetCosts?.forEach((item) => {
//           if(item.amount) {
//             total_grand_amount += parseFloat(item.amount);
//           }
//         });
//         if(!isNaN(total_grand_amount)) {
//           props.form.acquisition_cost = total_grand_amount;
//         }
//       }
//     },
//     { deep: true }
// );

// watch(() => props.form.acc_cost_center_id, (newEntries, oldEntries) => {
//   if(newEntries !== oldEntries && oldEntries != '' ){
//     
//   }
// })

function callapi(){
  props.form.acc_cost_center_id = props.form.acc_cost_center_name.id;
  props.form.accDepreciationLines = [];
  searchFixedAsset(props.form.acc_cost_center_id,props.form.business_unit);
}

onMounted(() => {
  watchEffect(() => {
    getCostCenter(null,props.form.business_unit);
  });
});

</script>

<template>
  <ErrorComponent :errors="errors"></ErrorComponent>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="label-group">
        <span class="label-item-title"> Year <span class="text-red-500">*</span></span>
        <input type="number" class="label-item-input" placeholder="Year" required v-model.trim="form.month_year" />
      </label>
      <label class="label-group">
        <span class="label-item-title"> Applied Date <span class="text-red-500">*</span></span>
        <VueDatePicker v-model.trim="form.applied_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Cost Center <span class="text-red-500">*</span></span>
        <v-select :options="allCostCenterLists" placeholder="--Choose an option--" :loading="isLoading" v-model.trim="form.acc_cost_center_name" label="name"  class="block w-full rounded form-input" @option:selected="callapi">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.acc_cost_center_name" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
      </label>
    </div>
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300"> Depreciation Line <span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="px-4 py-3 align-bottom"> Asset Name</th>
            <th class="px-4 py-3 align-bottom"> Asset Tag</th>
            <th class="px-4 py-3 align-bottom"> Useful Life (Year) </th>
            <th class="px-4 py-3 align-bottom"> Depreciation Rate (%) <span class="text-red-500">*</span></th>
            <th class="px-4 py-3 align-bottom"> Aquisition Cost</th>
            <th class="px-4 py-3 align-bottom"> Amount <span class="text-red-500">*</span></th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(depreciationLine, index) in form.accDepreciationLines" :key="depreciationLine.id">
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.accDepreciationLines[index].asset_name" placeholder="Asset Name" class="form-input vms-readonly-input" readonly autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.accDepreciationLines[index].asset_tag" placeholder="Asset Tag" class="form-input vms-readonly-input" readonly autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.accDepreciationLines[index].useful_life" placeholder="Useful Life" class="form-input vms-readonly-input" readonly autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.accDepreciationLines[index].depreciation_rate" placeholder="Depreciation Rate" class="form-input" autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.accDepreciationLines[index].acquisition_cost" placeholder="Aquisition Cost" class="form-input vms-readonly-input" readonly autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="number" step=".01" v-model.trim="form.accDepreciationLines[index].amount" class="form-input !text-right" autocomplete="off" required />
            </td>
          </tr>
<!--          <tr class="text-gray-700 dark-disabled:text-gray-400">-->
<!--            <td class="px-1 py-1 font-bold !text-right">Acquisition Cost</td>-->
<!--            <td class="px-1 py-1 font-bold text-right">-->
<!--              <input type="number" v-model.trim="form.acquisition_cost" class="block w-full rounded form-input vms-readonly-input !text-right" readonly>-->
<!--            </td>-->
<!--            <td class="px-1 py-1 font-bold text-right"></td>-->
<!--          </tr>-->
          </tbody>
          <tfoot v-if="!form.accDepreciationLines?.length">
          <tr v-if="isLoading">
            <td colspan="6">Loading...</td>
          </tr>
          <tr v-else-if="!form.accDepreciationLines?.length">
            <td colspan="6">No data found.</td>
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