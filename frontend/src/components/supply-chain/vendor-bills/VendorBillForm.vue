<template>

  <!-- Basic information -->
  <div class="flex flex-col justify-center  md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
    <label class="label-group"></label>
    <label class="label-group"></label>
    <label class="label-group"></label>
  </div>
  <div class="input-group">
    <label class="label-group">
          <span class="label-item-title">Date <span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd"></VueDatePicker>
      </label>
      <label class="label-group">
        <span class="label-item-title">Vendor Name <span class="text-red-500">*</span></span>
          <v-select :options="vendors" placeholder="--Choose an option--" :loading="isLoading" v-model="form.scmVendor" label="name" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.scmVendor"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select>
      </label>
      <label class="label-group">
        <span class="label-item-title">Bill No <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.bill_no" placeholder="Bill No" class="form-input" autocomplete="off" />
      </label>
      <label class="label-group">
        <span class="label-item-title">Warehouse Name <span class="text-red-500">*</span></span>
          <v-select :options="warehouses" placeholder="--Choose an option--" :loading="isLoading" v-model="form.scmWarehouse" label="name" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.scmWarehouse"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select>
      </label>
      
  </div>

  <div class="input-group">
    <label for="" class="label-group">
      <span class="label-item-title">Currency <span class="text-red-500">*</span></span>
      <v-select :options="currencies" :loading="isCurrencyLoading" placeholder="--Choose an option--" v-model="form.currency" class="block form-input">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.currency"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700"><nobr>Exchange Rate</nobr> (To USD)</span>
      <input type="text" @input="redoFullCalculation" v-model="form.currency_to_usd" placeholder="Exchange Rate" class="form-input" autocomplete="off" :readonly="isUSDCurrency()"/>
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700"><nobr>Exchange Rate</nobr> (USD To BDT)</span>
      <input type="text" @input="redoFullCalculation" v-model="form.usd_to_bdt" placeholder="Exchange Rate" class="form-input" autocomplete="off" :readonly="isBDTCurrency()"/>
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">Upload File </span>
      <input type="file" @change="selectedFile" placeholder="Upload File" class="form-input" autocomplete="off" />
    </label>
  </div>
  <div class="input-group !w-1/2">
    <label class="label-group">
          <RemarksComponet v-model="form.remarks" :maxlength="300" :fieldLabel="'Remarks'"></RemarksComponet>
    </label>
  </div>


  <div id="">
    <div id="">
      <div class="table-responsive min-w-screen">
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Materials</legend>
          <table class="w-full">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="py-3 align-center">MRR No <span class="text-red-500">*</span></th>
              <th class="py-3 align-center">Challan No</th>
              <th class="py-3 align-center">PO No</th>
              <th class="py-3 align-center">Amount <span class="text-red-500">*</span></th>
              <th class="py-3 align-center">Amount USD</th>
              <th class="py-3 align-center">Amount BDT</th>
              <th class="py-3 text-center align-center">
                <button type="button" @click="addMrr()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                </button>
              </th>
            </tr>
            </thead>

            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
              <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(billLine, index) in form.scmVendorBillLines" :key="index">
                <td class="!w-56 relative">
                  <v-select :options="scmVendorMrrs" :loading="isMrrLoading" label="ref_no" placeholder="--Choose an option--" v-model="form.scmVendorBillLines[index].scmMrr" @update:modelValue="setScmVendorMrrId(index)" class="block form-input">
                      <template #search="{attributes, events}">
                          <input
                              class="vs__search"
                              :required="!form.scmVendorBillLines[index].scmMrr"
                              v-bind="attributes"
                              v-on="events"
                              />
                      </template>
                  </v-select>
                  <input type="hidden" v-model="form.scmVendorBillLines[index].scm_mrr_id" />
                  <span v-show="form.scmVendorBillLines[index].isMrrDuplicate" class="text-yellow-600 absolute top-5 right-12 " title="Duplicate Warning" v-html="icons.ExclamationTriangle"></span>

                </td>
                <td class="!w-32">
                  <label class="block w-full mt-2 text-sm">
                    <span class="vms-readonly-input form-input show-block !bg-[#e7e6e6]">
                      <nobr>{{ form.scmVendorBillLines[index]?.scmMrr?.challan_no }}</nobr>
                    </span>
                  </label>
                  
                </td>
              
                <td class="!w-32">
                  <label class="block w-full mt-2 text-sm">
                    <span class="vms-readonly-input form-input show-block !bg-[#e7e6e6]">
                      <nobr>{{ form.scmVendorBillLines[index]?.scmPo?.ref_no }}</nobr>
                    </span>
                  </label>
                  
                </td>
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <input type="number" @input="calculateSingleItem(index)" required v-model="form.scmVendorBillLines[index].amount" class="form-input !text-right" min="1">
                  </label>
                </td>
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <input type="text" readonly v-model="form.scmVendorBillLines[index].amount_usd" class="!text-right vms-readonly-input form-input">
                  </label>
                </td>
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <input type="text" readonly v-model="form.scmVendorBillLines[index].amount_bdt" class="!text-right vms-readonly-input form-input">
                  </label>
                </td>

                <td class="px-1 py-1 text-center">
                  <button type="button" @click="removeMrr(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                  </button>
                  
                </td>
              </tr>
            </tbody>

            <tfoot>
              <tr class="text-gray-700 dark-disabled:text-gray-400">
                <td colspan="5" class="text-right pr-2">
                  Sub Total
                </td>
                <td>
                    <input type="text" v-model="form.sub_total" readonly placeholder="Sub Total" class="!text-right vms-readonly-input form-input" autocomplete="off" />
                </td>
                <td></td>
              </tr>
              <tr class="text-gray-700 dark-disabled:text-gray-400">
                <td colspan="5" class="text-right pr-2">
                  Discount
                </td>
                <td>
                    <input type="number" @input="CalculateAll" v-model="form.discount" placeholder="Discount" class="!text-right form-input" autocomplete="off" />
                </td>
                <td></td>
              </tr>
              <tr class="text-gray-700 dark-disabled:text-gray-400">
                <td colspan="5" class="text-right pr-2">
                  Net Amount
                </td>
                <td>
                    <input type="text" v-model="form.net_amount" readonly placeholder="Net Amount" class="!text-right vms-readonly-input form-input" autocomplete="off" />
                </td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </fieldset>
      </div>
    </div>
  </div>
  <ErrorComponent :errors="errors"></ErrorComponent>  

</template>



<script setup>
    import { ref, watch, onMounted,watchEffect,computed, watchPostEffect } from 'vue';
    import Error from "../../Error.vue";
    import DropZone from "../../DropZone.vue";
    import useVendor from "../../../composables/supply-chain/useVendor.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import ErrorComponent from "../../utils/ErrorComponent.vue";
    import RemarksComponet from '../../utils/RemarksComponent.vue';
    import DropZoneV2 from '../../DropZoneV2.vue';
    import {useStore} from "vuex";
    import env from '../../../config/env';
    import cloneDeep from 'lodash/cloneDeep';
    import useBusinessInfo from "../../../composables/useBusinessInfo"
    import useHeroIcon from "../../../assets/heroIcon";
    import Swal from "sweetalert2";


    const icons = useHeroIcon();
    const { vendors, searchVendor, scmVendorMrrs, searchMrrByVendor, isLoading, isMrrLoading } = useVendor();
    const { warehouses, searchWarehouse } = useWarehouse();
    const { getCurrencies, currencies, isCurrencyLoading } = useBusinessInfo();


    const props = defineProps({
      form: { type: Object, required: true },
      billLineObject: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      formType: { type: String, required : false },
      page: {required: false, default: {} },

    });

    const editInitiated = ref(0);

    const selectedFile = (event) => {
      props.form.attachment = event.target.files[0];
    };

    function addMrr() {
      props.form.scmVendorBillLines.push({...props.billLineObject});
    }

    function removeMrr(index){
      props.form.scmVendorBillLines.splice(index, 1);
    }

    const tableScrollWidth = ref(null);
    const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

    const isUSDCurrency = () => {
      if(props.form.currency) {
        const currency = props.form.currency;
        return currency === 'USD';
      }
    };

    const isBDTCurrency = () => {
      if(props.form.currency) {
        const currency = props.form.currency;
        return currency === 'BDT';
      }
    };

    const isOtherCurrency = (item, index) => {
      const currency = item[index]?.currency;
      return (currency !== 'BDT' || currency !== 'USD');
    };

  function fetchVendors(search) {
    searchVendor(search, props.form.business_unit);
  }

  function fetchWarehouses(search) {
    searchWarehouse(search, props.form.business_unit);
  }

  function fetchMrrByVendor() {
    searchMrrByVendor(props.form.scm_vendor_id);
  }

  watch(() => props.form.currency, (value) => {
        
        if(props.form.currency != '' && (editInitiated.value == 1 || props.formType == 'create')) {
          props.form.usd_to_bdt = null;
          props.form.currency_to_usd = null;
          redoFullCalculation()
        }
  });

  watch(() => props.form.scmVendor, (value) => {
        fetchMrrByVendor();
        if(editInitiated.value == 1) {
          props.form.scm_vendor_id = value?.id ?? null;
        }
  });

  watch(() => props.form.scmWarehouse, (value) => {
        if(editInitiated.value == 1) {
          props.form.scm_warehouse_id = value?.id ?? null;
        }
  });


const setScmVendorMrrId = (index) => {
  props.form.scmVendorBillLines[index].scm_mrr_id = props.form.scmVendorBillLines[index]?.id
}

const redoFullCalculation = () => {
  props?.form?.scmVendorBillLines.forEach((element, index) => {
    calculateSingleItem(index);
  });
}

const calculateSingleItem = (index) => {

  const { amount, amount_usd, amount_bdt } = calculateInCurrency(props.form.scmVendorBillLines[index]);

  props.form.scmVendorBillLines[index].amount_usd = (amount_usd > 0) ? amount_usd : 0;
  props.form.scmVendorBillLines[index].amount_bdt = (amount_bdt > 0) ? amount_bdt : 0;
  props.form.scmVendorBillLines[index].amount = (amount > 0) ? amount : 0;

  calculateSubTotal();
}

const calculateSubTotal = () => {
  let sub_total = 0.00;

  props?.form?.scmVendorBillLines.forEach(element => {
      sub_total += (element.amount_bdt > 0) ? parseFloat(element.amount_bdt) : 0
  });
  console.log("calculating sub total", sub_total)

  props.form.sub_total = parseFloat(sub_total).toFixed(2);

  CalculateAll();
}

function CalculateAll() {
 let net_amount = (props.form.sub_total * 1) - ((props.form.discount > 0) ? props.form.discount : 0);
 props.form.net_amount = parseFloat((net_amount > 0) ? net_amount : 0).toFixed(2)
}

const calculateInCurrency = (item) => {
  let currency = props.form.currency;
  let toUsdRate = props.form.currency_to_usd
  let toBdtRate = props.form.usd_to_bdt

  if(currency == '') {
    Swal.fire({
                    icon: "",
        title: "Correct Please!",
        html: `Please choose a currency`,
        customClass: "swal-width",
    });
    return false;
  } else {
    
  }

  if(currency == 'USD'){
    item.amount_usd = parseFloat(item?.amount).toFixed(2);
    item.amount_bdt = parseFloat(item?.amount * toBdtRate).toFixed(2);
  } else if(currency == 'BDT'){
    item.amount_usd = parseFloat(item?.amount * toUsdRate).toFixed(2);
    item.amount_bdt = parseFloat(item?.amount).toFixed(2);
  } else {
    item.amount_usd = parseFloat(item?.amount * toUsdRate).toFixed(2);
    item.amount_bdt = parseFloat(item?.amount * toUsdRate * toBdtRate).toFixed(2);
  }
  return {amount: (item.amount > 0) ? item.amount : 0, amount_usd: (item.amount_usd > 0) ? item.amount_usd : 0, amount_bdt: (item.amount_bdt > 0) ? item.amount_bdt : 0};
}

  watch(() => props.form.business_unit, (newValue, oldValue) => {
    
    if(props.formType == 'create') {
      props.form.scmWarehouse = null;
      props.form.scmVendor = null;

      warehouses.value = []
      vendors.value = []

      fetchVendors("");
      fetchWarehouses("");
    }
    

    
});


onMounted(() => {
  watchPostEffect(() => {
    getCurrencies();
  });
});
</script>





<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm;
    }
    .label-item-title {
        @apply text-gray-700 dark-disabled:text-gray-300 text-sm;
    }
    .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

 

</style>