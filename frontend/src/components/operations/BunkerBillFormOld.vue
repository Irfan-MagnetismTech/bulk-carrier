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
        <v-select :options="vendors" placeholder="--Choose an option--" :loading="vendorLoader" v-model="form.scmVendor" label="name" class="block form-input" @update:modelValue="vendorChange">
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
            <span class="text-gray-700 dark-disabled:text-gray-300">Upload file(Supplier Invoice) <span class="text-red-500">*</span></span>
            <input type="file" @change="attachFile" placeholder="Billing Email" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Upload file(SRM Copy) <span class="text-red-500">*</span></span>
            <input type="file" @change="attachSMRFile" placeholder="Billing Email" class="form-input" autocomplete="off" />
        </label>
    </div>

    <div class="mt-3 md:mt-8">
      <h4 class="text-md font-semibold uppercase mb-2">Bunker Line Information</h4>
      
      <div v-for="(requisiton, index) in form.opsBunkerBillLines" :key="index" class="w-full mx-auto p-2 border rounded-mdborder-gray-400 mb-5 shadow-md">
        <div  class="flex flex-col justify-center md:flex-row w-full md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">PR No. <span class="text-red-500">*</span></span>
            <v-select :options="bunkerRequisitions" placeholder="--Choose an option--" :loading="vendorLoader" v-model="form.opsBunkerBillLines[index].pr_no" label="requisition_no" class="block form-input" @update:modelValue="getBunkers(index)">
              <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.opsBunkerBillLines[index].requisition_no"
                      v-bind="attributes"
                      v-on="events"
                      />
              </template>
            </v-select>
            <!-- <input type="hidden"  step="0.001" required v-model="form.opsBunkerBillLines[index].pr_no" class="form-input" autocomplete="off"/> -->
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Exchange Rate (USD) <span class="text-red-500">*</span></span>
            <input type="text"  step="0.001" required v-model="form.opsBunkerBillLines[index].exchange_rate_usd" placeholder="Exchange Rate (USD)" class="form-input" autocomplete="off"/>
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Exchange Rate (BDT) <span class="text-red-500">*</span></span>
            <input type="text"  step="0.001" required v-model="form.opsBunkerBillLines[index].exchange_rate_bdt" placeholder="Exchange Rate (BDT)" class="form-input" autocomplete="off"/>
          </label>          
        </div>
        <div class="dt-responsive table-responsive" v-if="form.opsBunkerBillLines[index].opsBunkerBillLineItems">
          <table id="dataTable" class="w-full table table-striped table-bordered">
            <thead>
              <tr>
                <th>Particular</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Amount(USD)</th>
                <th>Amount(BDT)</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(detail, index) in form.opsBunkerBillLines[index].opsBunkerBillLineItems" :key="index" v-if="form.opsBunkerBillLines[index].pr_no">
                <td>
                  <input type="text" readonly v-model.trim="detail.name" class="form-input text-right"/>
                </td>
                <td>
                  <input type="text" v-model.trim="detail.quantity" class="form-input text-right"/>
                </td>
                <td>
                  <input type="text" v-model.trim="detail.rate" class="form-input text-right"/>
                </td>
                <td>
                  <input type="text" readonly v-model.trim="detail.amount_usd" class="form-input text-right"/>
                </td>
                <td>
                  <input type="text" readonly v-model.trim="detail.amount_bdt" class="form-input text-right"/>
                </td>
              </tr>
              <tr>
                <td colspan="3">Total</td>
                <td><input type="text" readonly v-model="form.opsBunkerBillLines[index].amount_usd" class="form-input text-right"/></td>
                <td><input type="text" readonly v-model="form.opsBunkerBillLines[index].amount_bdt" class="form-input text-right"/></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="flex justify-center items-center my-3">
                  <button type="button" @click="addBunker()" class="px-3 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center">
                    Add More
                  </button>
                  <button type="button" v-if="index>0" @click="removeBunker(index)" class="px-3 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center ml-3">
                    Remove
                  </button> 
        </div>
      </div> 
      <div v-if="form.opsBunkerBillLines">
        <div class="flex flex-col justify-center md:flex-row w-full md:gap-2">
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Sub Total (USD) <span class="text-red-500">*</span></span>
              <input type="number" step="0.001" required :value="props.form.sub_total_usd" placeholder="Sub Total(USD)" class="form-input" autocomplete="off"/>
          </label>   
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Discount (USD) <span class="text-red-500">*</span></span>
              <input type="number" step="0.001" required v-model="props.form.discount_usd" placeholder="Sub Total(USD)" class="form-input" autocomplete="off"/>
          </label>   
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Grand Total (USD) <span class="text-red-500">*</span></span>
              <input type="number" step="0.001" required :value="props.form.grand_total_usd" placeholder="Sub Total(USD)" class="form-input" autocomplete="off"/>
          </label>   
        </div>
        <div class="flex flex-col justify-center md:flex-row w-full md:gap-2">
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Sub Total (BDT) <span class="text-red-500">*</span></span>
              <input type="number" step="0.001" required :value="props.form.sub_total_bdt" placeholder="Sub Total(BDT)" class="form-input" autocomplete="off"/>
          </label>   
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Discount (BDT) <span class="text-red-500">*</span></span>
              <input type="number" step="0.001" required v-model="props.form.discount_bdt" placeholder="Sub Total(BDT)" class="form-input" autocomplete="off"/>
          </label>   
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Grand Total (BDT) <span class="text-red-500">*</span></span>
              <input type="number" step="0.001" required :value="props.form.grand_total_bdt" placeholder="Sub Total(BDT)" class="form-input" autocomplete="off"/>
          </label>    
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
import cloneDeep from 'lodash/cloneDeep';
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
// const { materials, getBunkerList } = useMaterial();

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

function vendorChange() {
  let val = props.form.scmVendor ?? null;
  // console.log(val.id);
  props.form.ops_vendor_id = val?.id ?? null;
  searchBunkerRequisitionsByVendor(val.id);
}

watch(() => props.form.scmVendor, (value) => {
  vendor.value = null;
  if(value) {
    props.form.scm_vendor_id = value?.id
    showVendor(value?.id)
  }

}, { deep: true });


function addBunker() {
  const bunker = cloneDeep(props.bunkerObject);
  props.form.opsBunkerBillLines.push(bunker);
  // props.form.opsBunkerBillLines.push({... props.bunkerObject });  
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


function getBunkers(index) {
  // console.log(index);
  // console.log(props.form.opsBunkerBillLines[index]);
  searchBunkersByPrNo(props.form.opsBunkerBillLines[index].pr_no.requisition_no).then(()=>{
    if(requisitionBunker?.value){
      props.form.opsBunkerBillLines[index].opsBunkerBillLineItems= requisitionBunker?.value?.opsBunkers;
    }
  })
  
}


watch(() => props.form.scmVendor, (value) => {  
  if(value){
    searchBunkerRequisitionsByVendor(value.id)
    .then(() => {
      // props.form.opsBunkerBillLines = bunkerRequisitions?.value;
      console.log(bunkerRequisitions?.value);
    })
    .catch((error) => {
      console.error("Error fetching data.", error);
    });
    // console.log(bunkerRequisitions);
  }
  editInitiated.value = true;
})

watch(() => props.form.opsBunkerBillLines, (value) => {
  // let lines= props.form.opsBunkerBillLines;
  // console.log(value);
  // if(props?.formType == 'edit' && editInitiated.value != true) {
  // }
  if(props.form.opsBunkerBillLines?.length < 1) {
    props.form.opsBunkerBillLines.push({... props.opsBunkerBillLines });
  }
  for(const linesIndex in value) {
    props.form.opsBunkerBillLines[linesIndex].exchange_rate_bdt=0;
    props.form.opsBunkerBillLines[linesIndex].exchange_rate_usd=0;
    // props.form.opsBunkerBillLines[linesIndex].opsBunkerBillLineItems = cloneDeep(props.form.opsBunkerBillLines[linesIndex].opsBunkers);
  }

  // console.log(props.form.opsBunkerBillLines);
});


watch(() => props?.form?.opsBunkerBillLines, (newVal, oldVal) => {
    // console.log(newVal);
      let sub_total_usd = 0.0;
      let sub_total_bdt = 0.0;
      if(newVal){
        newVal?.forEach((line, index) => {
          sub_total_usd += line?.amount_usd;
          sub_total_bdt += line?.amount_bdt;
        });
      }
  props.form.sub_total_usd = sub_total_usd;
  props.form.sub_total_bdt = sub_total_bdt;
  CalculateAll();
}, { deep: true });


watch(() => props.form.opsBunkerBillLines, (value) => {
  let sub_total = 0.0;
  if(value){
    let total_usd= 0;
    let total_bdt= 0;
    // console.log(value);
    for(const linesIndex in value) {
      let exchange_rate_bdt= props.form.opsBunkerBillLines[linesIndex].exchange_rate_bdt;
      let exchange_rate_usd= props.form.opsBunkerBillLines[linesIndex].exchange_rate_usd;
      props.form.opsBunkerBillLines[linesIndex].opsBunkerBillLineItems?.forEach((item, index) => {
        total_usd+=props.form.opsBunkerBillLines[linesIndex].opsBunkerBillLineItems[index].amount_usd = parseFloat((item?.rate * item?.quantity * exchange_rate_usd).toFixed(2));
        total_bdt+=props.form.opsBunkerBillLines[linesIndex].opsBunkerBillLineItems[index].amount_bdt = parseFloat((item?.rate * item?.quantity * exchange_rate_bdt).toFixed(2));
      });
      // console.log()
      props.form.opsBunkerBillLines[linesIndex].amount_usd=total_usd.toFixed(2);
      props.form.opsBunkerBillLines[linesIndex].amount_bdt=total_bdt.toFixed(2);
      total_usd= 0;
      total_bdt= 0;
    }
  }
}, { deep: true });

watch(() => props?.form?.discount_usd, (newVal, oldVal) => {
  // console.log(newVal);
  props.form.grand_total_usd = (props.form.sub_total_usd * 1 ) - newVal;
  CalculateAll();
});

watch(() => props?.form?.discount_bdt, (newVal, oldVal) => {
  // console.log(newVal);
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