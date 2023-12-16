<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
        <label class="block w-full mt-2 text-sm"></label>
        <label class="block w-full mt-2 text-sm"></label>
        <label class="block w-full mt-2 text-sm"></label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Date <span class="text-red-500">*</span></span>
            <input type="date" v-model.trim="form.date" placeholder="Date" class="form-input" required autocomplete="off" />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Vendor <span class="text-red-500">*</span></span>
        <v-select :options="vendors" placeholder="--Choose an option--" :loading="vendorLoader" v-model="form.scmVendor" label="name" class="block form-input" >
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.scmVendor"
                    v-bind="attributes"
                    v-on="events"
                    />
            </template>
        </v-select>
        <input type="hidden" v-model="form.scm_vendor_id" />        
      </label>

      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Bill No. <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.vendor_bill_no" placeholder="Bill No." class="form-input" required autocomplete="off" />
      </label>

      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Remarks <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.remarks" placeholder="Remarks" class="form-input" required autocomplete="off" />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2 mt-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Upload file(Supplier Invoice) <span class="text-red-500">*</span><p v-if="props?.formType == 'edit'" class="text-red-600"> {{ getFileName(form.attachment) }}</p></span>
            <input type="file" @change="attachFile" placeholder="Billing Email" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Upload file(SRM Copy) <span class="text-red-500">*</span> <p v-if="props?.formType == 'edit'" class="text-red-600"> {{ getFileName(form.smr_file_path) }}</p></span>
            <input type="file" @change="attachSMRFile" placeholder="Billing Email" class="form-input" autocomplete="off" />
        </label>
    </div>

    <div class="mt-3 md:mt-8">
      <h4 class="text-md font-semibold uppercase mb-2">Bunker Line Information</h4>
      
      <div v-for="(pr, index) in form.opsBunkerBillLines" :key="index">

        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2 mt-2">
          <label class="block w-1/2 mt-2 text-sm"></label>

          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">PR No. <span class="text-red-500">*</span></span>
            <v-select :options="bunkerRequisitions" placeholder="--Choose an option--" v-model="form.opsBunkerBillLines[index].opsBunkerRequisition" label="requisition_no" class="block form-input" @update:modelValue="getBunkers(index)">
              <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.opsBunkerBillLines[index].opsBunkerRequisition"
                      v-bind="attributes"
                      v-on="events"
                      />
              </template>
            </v-select>
            <!-- <input type="hidden"  step="0.001" required v-model="form.opsBunkerBillLines[index].pr_no" class="form-input" autocomplete="off"/> -->
          </label>
          <label class="block w-1/2 mt-2 text-sm"></label>

        </div>

        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700">Currency <span class="text-red-500">*</span></span>
              <select v-model.trim="form.opsBunkerBillLines[index].currency" class="form-input" required>
                <option selected value="" disabled>Select Currency</option>
                <option v-for="currency in currencies" :value="currency" :key="currency">{{ currency }}</option>
              </select>
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700">Exchange Rate (To USD) </span>
            <input type="text" v-model="form.opsBunkerBillLines[index].exchange_rate_usd" placeholder="Exchange Rate (To USD)" class="form-input" :readonly="isUSDCurrency(index)" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700">Exchange Rate (USD to BDT) </span>
            <input type="text" v-model="form.opsBunkerBillLines[index].exchange_rate_bdt" placeholder="Exchange Rate (USD to BDT)" class="form-input" :readonly="isBDTCurrency(index)" />
          </label>
          <label class="block w-full mt-2 text-sm"></label>
        </div>

        <div class="relative my-3">

          <div class="dt-responsive table-responsive">
            <table class="w-full whitespace-no-wrap" >
              <thead>
                  <tr class="w-full">
                    <th class="w-72">Bunker</th>
                    <th class="w-20">Quantity</th>
                    <th>Rate</th>
                    <th v-if="isOtherCurrency[index]">Amount </th>
                    <th>Amount USD</th>
                    <th>Amount BDT</th>
                  </tr>
              </thead>
              <tbody>
                <tr>
                  <td>

                  </td>
                  <td>
                    <input type="text" v-model="form.opsVoyageBudgetEntries[index].quantity" placeholder="Qty" class="form-input" autocomplete="off" />
                  </td>
                  <td>
                    <input type="text" v-model="form.opsVoyageBudgetEntries[index].rate" placeholder="Rate" class="form-input" autocomplete="off" />
                  </td>
                  <td v-if="isOtherCurrency">
                    <input type="text" v-model="form.opsVoyageBudgetEntries[index].amount" placeholder="Amount" readonly class="form-input" autocomplete="off" />
                  </td>
                  <td>
                      <input type="text" v-model="form.opsVoyageBudgetEntries[index].amount_usd" placeholder="USD Amount" readonly class="form-input" autocomplete="off" />
                  </td>
                  <td>
                      <input type="text" v-model="form.opsVoyageBudgetEntries[index].amount_bdt" placeholder="BDT Amount" readonly class="form-input" autocomplete="off" />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="flex justify-center items-center my-3">
                    <button type="button" @click="addPortSchedule()" class="px-3 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center">
                      Add More
                    </button>
                    <button type="button" v-if="index>0" @click="removePortSchedule(index)" class="px-3 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center ml-3">
                      Remove
                    </button> 
          </div>

      </div>
      
    </div>
    <ErrorComponent :errors="errors"></ErrorComponent>

</template>
<script setup>
import { ref, watch, onMounted, watchEffect } from 'vue';
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useVendor from "../../composables/supply-chain/useVendor.js";
import useBunkerRequisition from "../../composables/operations/useBunkerRequisition";
import useMaterial from '../../composables/supply-chain/useMaterial';
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import RemarksComponent from '../../components/utils/RemarksComponent.vue';
import DropZoneV2 from '../../components/DropZoneV2.vue';
import useBusinessInfo from '../../composables/useBusinessInfo';

const editInitiated = ref(false);
const props = defineProps({
    form: {
        required: false,
        default: {}
    },
    errors: { type: [Object, Array], required: false },
    bunkerObject: { type: Object, required: false },
    bunkerObjectItem: { type: Object, required: false },
    formType: { type: Object, required: false }
});

const { currencies, getCurrencies } = useBusinessInfo();
const {vendor, vendors, showVendor, searchVendor, isLoading: vendorLoader } = useVendor();
const {  bunkerRequisitions, requisitionBunker, searchBunkerRequisitionsByVendor , searchBunkersByPrNo} = useBunkerRequisition();

watch(() => props.form.business_unit, (value) => {
  // console.log(props.form.ops_vendor_id);
  if(props?.formType != 'edit') {
    props.form.scmVendor = null;
    props.form.opsBunkerBillLines = null;
    props.form.scm_vendor_id = null;
  }
}, { deep : true });

function fetchVendors(searchParam, loading) {
  searchVendor(searchParam, props.form.business_unit, loading)
}

watch(() => props.form.scmVendor, (newValue, oldValue) => {
    vendor.value = null;
  // if(value) {
    props.form.scm_vendor_id = newValue?.id
  //   showVendor(value?.id)
  // }
}, { deep: true });


watch(() => props.form.business_unit, (newValue, oldValue) => {
  if(newValue) {
    fetchVendors("", false)
  }
})

onMounted(() => {
  getCurrencies();
})
</script>
<style lang="postcss" scoped>
.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}
.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
}
</style>