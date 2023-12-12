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
        <v-select :options="vendors" placeholder="--Choose an option--" :loading="vendorLoader"  v-model="form.scmVendor" label="name" class="block form-input">
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
      <table class="w-full whitespace-no-wrap" >
        <thead v-once>
          <tr class="w-full">
            <th class="!w-12">SL.</th>
            <th class="!w-16">PR NO.</th>
            <th class="!w-80">Description</th>
            <!-- <th class="!w-20">Exchange Rate (To USD)</th>
            <th class="!w-20">Exchange Rate (To BDT)</th> -->
            <th class="!w-20">Amount(USD)</th>
            <th class="!w-20">Amount(BDT)</th>
            <th class="w-16">
              <button type="button" @click="addBunker()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(requisiton, index) in form.opsBunkerBillLines">
            <td>
              {{ index+1 }}
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                <input type="number" :readonly="form.opsBunkerBillLines[index]?.is_readonly"  step="0.001" required v-model="form.opsBunkerBillLines[index].pr_no" placeholder="Requisition No." class="form-input" autocomplete="off"/>
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                <input type="text" :readonly="form.opsBunkerBillLines[index]?.is_readonly"  step="0.001" required v-model="form.opsBunkerBillLines[index].description" placeholder="Description" class="form-input" autocomplete="off"/>
              </label>
            </td>

            <!-- <td>
              <label class="block w-full mt-2 text-sm">
                <input type="number" :readonly="form.opsBunkerBillLines[index]?.is_readonly"  step="0.001" required v-model="form.opsBunkerBillLines[index].exchange_rate_bdt" placeholder="Exchange Rate(BDT)" class="form-input" autocomplete="off"/>
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                <input type="number" :readonly="form.opsBunkerBillLines[index]?.is_readonly"  step="0.001" required v-model="form.opsBunkerBillLines[index].exchange_rate_usd" placeholder="Exchange Rate(USD)" class="form-input" autocomplete="off"/>
              </label>
            </td> -->
            <td>
              <label class="block w-full mt-2 text-sm">
                <input type="number" :readonly="form.opsBunkerBillLines[index]?.is_readonly"  step="0.001" required v-model="form.opsBunkerBillLines[index].amount_usd" placeholder="Amount(USD)" class="form-input" autocomplete="off"/>
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                <input type="number" :readonly="form.opsBunkerBillLines[index]?.is_readonly"  step="0.001" required v-model="form.opsBunkerBillLines[index].amount_bdt" placeholder="Amount(BDT)" class="form-input" autocomplete="off"/>
              </label>
            </td>
            <td :class="{hidden : form.opsBunkerBillLines[index]?.is_readonly}">
              <button type="button" @click="removeBunker(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button> 
            </td>
          </tr>
          <tr>
              <td :colspan="3">Sub Total</td>
              <td>
                <input type="number" step="0.001" required :value="props.form.sub_total_usd" placeholder="Sub Total(USD)" class="form-input" autocomplete="off"/>
              </td>
              <td>
                <input type="number" step="0.001" required :value="props.form.sub_total_bdt" placeholder="Sub Total" class="form-input" autocomplete="off"/>
              </td>
              <td></td>
          </tr>
          <tr>
              <td :colspan="3">Discount</td>
              <td>
                <input type="number" step="0.001" required v-model="props.form.discount_usd" placeholder="Discount(USD)" class="form-input" autocomplete="off"/>
              </td>
              <td>
                <input type="number" step="0.001" required v-model="props.form.discount_bdt" placeholder="Discount" class="form-input" autocomplete="off"/>
              </td>
              <td></td>
          </tr>
          <tr>
              <td :colspan="3">Grand Total</td>
              <td>
                <input type="number" step="0.001" required :value="props.form.grand_total_usd" placeholder="Grand Total(USD)" class="form-input" autocomplete="off"/>
              </td>
              <td>
                <input type="number" step="0.001" required :value="props.form.grand_total_bdt" placeholder="Grand Total(BDT)" class="form-input" autocomplete="off"/>
              </td>
              <td></td>
          </tr>
        </tbody>
      </table>
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
    formType: { type: Object, required: false }
});

const { currencies, getCurrencies } = useBusinessInfo();
const {vendor, vendors, showVendor, searchVendor, isLoading: vendorLoader } = useVendor();
const {  bunkerRequisitions, searchBunkerRequisitionsByVendor } = useBunkerRequisition();
// const { materials, getBunkerList } = useMaterial();

watch(() => props.form.business_unit, (value) => {
  // console.log(props.form.ops_vendor_id);
  if(props?.formType != 'edit') {
    props.form.scmVendor = null;
    props.form.opsBunkerBillLines = null;
    props.form.scm_vendor_id = null;
  }
}, { deep : true })

function fetchVendors(searchParam, loading) {
  searchVendor(searchParam, props.form.business_unit, loading)
}

function getFileName(filePath) {
  if(filePath){
    const pathSegments = filePath.split('/');
    return pathSegments[pathSegments.length - 1];
  }
  return filePath;
}

watch(() => props.form.scmVendor, (value) => {
  vendor.value = null;
  if(value) {
    props.form.scm_vendor_id = value?.id
    showVendor(value?.id)
  }

}, { deep: true });


function addBunker() {
  props.form.opsBunkerBillLines.push({... props.bunkerObject });
}

function removeBunker(index){
  props.form.opsBunkerBillLines.splice(index, 1);
}

function attachFile(e) {
  let attachment = e.target.files[0];
  props.form.attachment = attachment;
}

function attachSMRFile(e) {
  let smr_file_path = e.target.files[0];
  props.form.smr_file_path = smr_file_path;
}

const bunkerReset = ref([]);
watch(() => vendor, (value) => {
  if(props.form.scm_vendor_id != undefined){
    searchBunkerRequisitionsByVendor(props.form.scm_vendor_id)
    .then(() => {
      bunkerReset.value = bunkerRequisitions?.value;
      if((props?.formType == 'edit' && editInitiated.value == true) || (props.formType != 'edit')) {
        props.form.opsBunkerBillLines = bunkerReset.value
      }
      else{
        editInitiated.value = true;
      }
    })
    .catch((error) => {
      console.error("Error fetching data.", error);
    });
  }
},{deep:true});


watch(() => props?.form?.opsBunkerBillLines, (newVal, oldVal) => {
      let sub_total_usd = 0.0;
      let sub_total_bdt = 0.0;
      newVal?.forEach((line, index) => {
        sub_total_usd += line.amount_usd;
        sub_total_bdt += line.amount_bdt;
      });
  props.form.sub_total_usd = sub_total_usd;
  props.form.sub_total_bdt = sub_total_bdt;
  CalculateAll();
}, { deep: true });


// watch(() => props.form.opsBunkerBillLines, (value) => {
//   let sub_total = 0.0;
//   // console.log('sub total');
//   if(value){
//     props.form.opsBunkerBillLines.forEach((line, index) => {
//       sub_total += parseFloat(props.form.opsBunkerBillLines[index].amount_bdt);
//       props.form.sub_total = parseFloat(sub_total.toFixed(2));
//     });
//     console.log(props.form.sub_total);
//     CalculateAll();
//   }
// }, { deep: true });

watch(() => props?.form?.discount_usd, (newVal, oldVal) => {
  console.log(newVal);
  props.form.grand_total_usd = (props.form.sub_total_usd * 1 ) - newVal;
  CalculateAll();
});

watch(() => props?.form?.discount_bdt, (newVal, oldVal) => {
  console.log(newVal);
  props.form.grand_total_bdt = (props.form.sub_total_bdt * 1 ) - newVal;
  CalculateAll();
});



function CalculateAll() {
  props.form.grand_total_usd = (props.form.sub_total_usd * 1) - (props.form.discount_usd * 1 );
  props.form.grand_total_bdt = (props.form.sub_total_bdt * 1) - (props.form.discount_bdt * 1 );
}





onMounted(() => {
    watchEffect(() => {
      if(props.form.business_unit && props.form.business_unit != 'ALL'){
        fetchVendors("", false);
      }
    });
});
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