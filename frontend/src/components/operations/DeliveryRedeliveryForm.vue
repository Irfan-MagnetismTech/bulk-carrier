<template>

    <div id="basic-info">
      <h4 class="text-md font-semibold">Basic Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
        </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Note Type <span class="text-red-500">*</span></span>
              <select v-model="form.note_type" required class="form-input" :disabled="formType == 'edit'">
                <option value="" selected disabled>--Choose an option--</option>
                <option>Delivery</option>
                <option>Re-delivery</option>
              </select>
          </label>


          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Effective Date <span class="text-red-500">*</span></span>
              <VueDatePicker v-model="form.effective_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>

            </label>

          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Currency <span class="text-red-500">*</span></span>
                <select v-model="form.currency" class="form-input" required>
                  <option value="" selected disabled>--Choose an option--</option>
                  <option v-for="currency in currencies" :key="currency">{{ currency }}</option>
                </select>
          </label>

          <label class="block w-full mt-2 text-sm" v-if="form.currency != 'USD' && form.currency != ''">
              <span class="text-gray-700 dark-disabled:text-gray-300">Exchange Rate <small>[To USD]</small> <span class="text-red-500">*</span></span>
              <input type="number" step="0.001" v-model.trim="form.exchange_rate_usd" required placeholder="Exchange Rate" class="form-input" autocomplete="off" />
          </label>

          <label class="block w-full mt-2 text-sm" v-if="form.currency != 'BDT' && form.currency != ''">
              <span class="text-gray-700 dark-disabled:text-gray-300">Exchange Rate <small>[To BDT]</small> <span class="text-red-500">*</span></span>
              <input type="number" step="0.001" v-model.trim="form.exchange_rate_bdt" required placeholder="Exchange Rate" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm" v-if="form.currency == ''"></label>

          
          
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
              <v-select :options="vessels" placeholder="--Choose an option--" v-model="form.opsVessel" label="name" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsVessel"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-model="form.ops_vessel_id" />
            </label>        
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Code</span>
              <input type="text" v-model.trim="form.short_code" placeholder="Vessel Code" class="form-input bg-gray-100" readonly autocomplete="off" />
          </label>
        
        
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Owner Name</span>
              <input type="text" v-model.trim="form.owner_name" placeholder="Vessel Owner Name" class="form-input bg-gray-100" readonly autocomplete="off" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Capacity</span>
            <input type="text" v-model.trim="form.capacity" placeholder="Vessel Capacity" class="form-input bg-gray-100" readonly autocomplete="off" />
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Charterer Name <span class="text-red-500">*</span></span>
              <v-select :options="chartererProfiles" placeholder="--Choose an option--" v-model="form.opsChartererProfile" label="name" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsChartererProfile"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-model="form.ops_charterer_profile_id" />

          </label>
        
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Charterer Owner Code</span>
              <input type="text" v-model.trim="form.owner_code" placeholder="Charterer Code" class="form-input bg-gray-100" readonly autocomplete="off" />
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Charterer Email</span>
              <input type="text" v-model.trim="form.email" placeholder="Charterer Email" class="form-input bg-gray-100" readonly autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Contact No.</span>
            <input type="text" v-model.trim="form.contact_no" placeholder="Contact No." class="form-input bg-gray-100" readonly autocomplete="off" />
        </label>
      </div>
      <RemarksComponet v-model="form.remarks" :maxlength="500" :fieldLabel="'Remarks'"></RemarksComponet>
      
    </div>


    <div v-if="form.opsBunkers?.length" id="bunker-info" class="mt-5">
      <h4 class="text-md font-semibold uppercase mb-2">Bunker Information</h4>
        <table class="w-full whitespace-no-wrap" >
          <thead v-once>
            <tr class="w-full">
              <th>SL</th>
              <th class="w-72">Bunker Name</th>
              <th>Unit</th>
              <th>Quantity</th>
              <th>Rate</th>
              <th>Amount </th>
              <th>Amount <small>(USD)</small></th>
              <th>Amount <small>(BDT)</small></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(certificate, index) in form.opsBunkers" :key="index">
              <td>
                {{ index+1 }}
              </td>
              <td>
                <span class="show-block !justify-center !bg-gray-100" v-if="form.opsBunkers[index]?.name">{{ form.opsBunkers[index]?.name }}</span>
              </td>
              <td>
                <span class="show-block !justify-center !bg-gray-100" v-if="form.opsBunkers[index]?.unit">{{ form.opsBunkers[index]?.unit }}</span>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsBunkers[index].quantity" placeholder="Quantity" :keypress="calculatePrice(index)" class="form-input text-right" autocomplete="off"/>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsBunkers[index].rate" placeholder="Rate" :keypress="calculatePrice(index)" class="form-input text-right" autocomplete="off" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model.trim="form.opsBunkers[index].amount" placeholder="Amount" class="form-input text-right" autocomplete="off" readonly/>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model.trim="form.opsBunkers[index].amount_usd" placeholder="USD Amount" class="form-input text-right" autocomplete="off" readonly/>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model.trim="form.opsBunkers[index].amount_bdt" placeholder="BDT Amount" class="form-input text-right" autocomplete="off" readonly/>
                </label>
              </td>
            </tr>
          </tbody>
        </table>
    </div>
    <ErrorComponent :errors="errors"></ErrorComponent>
    
</template>
<script setup>
import { ref, watch, onMounted } from "vue";
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useBusinessInfo from "../../composables/useBusinessInfo"
import useVessel from "../../composables/operations/useVessel";
import useVoyage from "../../composables/operations/useVoyage";
import useChartererProfile from "../../composables/operations/useChartererProfile";
import RemarksComponet from '../../components/utils/RemarksComponent.vue';
import ErrorComponent from '../../components/utils/ErrorComponent.vue';

const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);

const editInitiated = ref(false);
const { getCurrencies, currencies } = useBusinessInfo();
const { getAllChartererProfiles, chartererProfiles } = useChartererProfile();
const { voyage, voyages, showVoyage, getVoyageList } = useVoyage();
const { vessel, vessels, getVesselList, showVessel } = useVessel();
const props = defineProps({
    form: {
        required: false,
        default: {},
    },
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required : false }
});



watch(() => props.form.opsVessel, (value) => {
  if(value) {
    props.form.ops_vessel_id = value?.id
    props.form.short_code = value?.short_code
    props.form.owner_name = value?.owner_name
    props.form.capacity = value?.capacity
  
    if((props?.formType == 'edit' && editInitiated.value == true) || props.formType == 'create') {
      props.form.opsBunkers = []
    }
    let loadStatus = false;
    showVessel(value?.id, loadStatus);
  } else {
    props.form.ops_vessel_id = null
    props.form.short_code = null
    props.form.owner_name = null
    props.form.capacity = null
  }
}, { deep: true})

watch(() => props.form.business_unit, (value) => {

  if(props?.formType != 'edit') {
    props.form.opsVoyage = null;
    props.form.ops_voyage_id = null;
    props.form.opsVessel = null;
    props.form.ops_vessel_id = null;
    props.form.opsChartererProfile = null;
    props.form.ops_charterer_profile_id = null;
    props.form.short_code = null;
    props.form.owner_name = null;
    props.form.capacity = null;
    props.form.owner_code = null;
    props.form.email = null;
    props.form.contact_no = null;
    props.form.opsBunkers = null;
  }

  getVesselList(props.form.business_unit);
  getAllChartererProfiles(props.form.business_unit);
  

}, { deep : true })

const bunkerReset = ref([]);
watch(() => vessel, (value) => {

  
  bunkerReset.value = value?.value?.opsBunkers?.map(obj => {
    // Assuming you want to reset the resettableValue property to some default value
    return {
      ...obj,
      exchange_rate_bdt: null,
      exchange_rate_usd: null,
      rate: null,
      amount_usd: null,
      amount_bdt: null,
    };
  });

  if(value) {
    if(props?.formType == 'edit' && editInitiated.value == true) {
      props.form.opsBunkers = bunkerReset.value
    } else if(props?.formType == 'edit' && editInitiated.value != true) {
      editInitiated.value = true;
    }

    if(props?.formType == 'create') {
      props.form.opsBunkers = bunkerReset.value
    }
  }
}, { deep : true})


watch(() => props.form.opsChartererProfile, (value) => {
  if(value) {
    props.form.ops_charterer_profile_id = value?.id

    props.form.owner_code = value?.owner_code
    props.form.email = value?.email
    props.form.contact_no = value?.contact_no
  } else {
    props.form.ops_charterer_profile_id = null

    props.form.owner_code = null
    props.form.email = null
    props.form.contact_no = null
  }
}, { deep: true})

watch(() => props.form.currency, (value) => {
  console.log("currency change ")

  if(value) {
    


    if(props?.formType == 'edit' && editInitiated.value == true) {
      props.form.exchange_rate_bdt = null;
      props.form.exchange_rate_usd = null;
      if(props.form.opsBunkers) {
        // for(const bunker of props.form.opsBunkers) {
        //   bunker.rate = 0;
        // }
        for(let index in props.form.opsBunkers) {
          // props.form.opsBunkers[index].rate = null
          calculatePrice(index)
        }
      }
      
    }

    if(props?.formType == 'create') {
      props.form.exchange_rate_bdt = null;
      props.form.exchange_rate_usd = null;
      if(props.form.opsBunkers) {
        // for(const bunker of props.form.opsBunkers) {
        //   bunker.rate = 0;
        // }
        for(let index in props.form.opsBunkers) {
          // props.form.opsBunkers[index].rate = null
          calculatePrice(index)
        }
      }
    }
  }
}, { deep: true})

watch(() => props.form.exchange_rate_usd, (value) => {
console.log("exchange rate changing")
  if(value) {

    props.form.opsBunkers.forEach(function(element, index) {
        calculatePrice(index)
    })

    if(props?.formType == 'edit' && editInitiated.value == true) {
      for(let index in props.form.opsBunkers) {
        calculatePrice(index)
      }
    }

    if(props?.formType == 'create') {

      for(let index in props.form.opsBunkers) {
        calculatePrice(index)
      }
    }
  }
}, {deep: true});
watch(() => props.form.exchange_rate_bdt, (value) => {
console.log("exchange rate changing")
  if(value) {

    props.form.opsBunkers.forEach(function(element, index) {
        calculatePrice(index)
    })

    if(props?.formType == 'edit' && editInitiated.value == true) {
      for(let index in props.form.opsBunkers) {
        calculatePrice(index)
      }
    }

    if(props?.formType == 'create') {

      for(let index in props.form.opsBunkers) {
        calculatePrice(index)
      }
    }
  }
}, {deep: true});

function calculatePrice(index) {
  console.log("calculate price", index)
  let quantity = (props.form.opsBunkers[index].quantity > 0) ? props.form.opsBunkers[index].quantity : 0;
  let rate = (props.form.opsBunkers[index].rate > 0) ? props.form.opsBunkers[index].rate : 0;
  let exchangeRateUsd = (props.form.exchange_rate_usd > 0) ? props.form.exchange_rate_usd : 0;
  let exchangeRateBdt = (props.form.exchange_rate_bdt > 0) ? props.form.exchange_rate_bdt : 0;

  let amount = parseFloat(parseFloat(quantity) * parseFloat(rate))
console.log("dd", amount)
  if(props.form.currency == 'USD') {
    console.log("usd", exchangeRateBdt, quantity, rate, "=", amount)
    props.form.opsBunkers[index].amount = parseFloat((amount) ? amount : 0).toFixed(2);
    props.form.opsBunkers[index].amount_usd = parseFloat((amount) ? amount : 0).toFixed(2);
    props.form.opsBunkers[index].amount_bdt = parseFloat(amount * parseFloat(exchangeRateBdt)).toFixed(2);
  } else if(props.form.currency == 'BDT') {
    console.log("bdt", exchangeRateUsd, quantity, rate, "=", amount)
    props.form.opsBunkers[index].amount = parseFloat((amount) ? amount : 0).toFixed(2);
    props.form.opsBunkers[index].amount_bdt = parseFloat((amount) ? amount : 0).toFixed(2);
    props.form.opsBunkers[index].amount_usd = parseFloat(amount * parseFloat(exchangeRateUsd)).toFixed(2);
  } else {
    console.log("other", exchangeRateUsd, quantity, rate, "=", amount)
    props.form.opsBunkers[index].amount = parseFloat((amount) ? amount : 0).toFixed(2);
    props.form.opsBunkers[index].amount_bdt = parseFloat(amount * parseFloat(exchangeRateUsd) * parseFloat(exchangeRateBdt)).toFixed(2);
    props.form.opsBunkers[index].amount_usd = parseFloat(amount * parseFloat(exchangeRateUsd)).toFixed(2);
  }
}


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

/* Hide the default number input arrows */
input[type=number] {
  -moz-appearance: textfield; /* Firefox */
  -webkit-appearance: textfield; /* Chrome, Safari, Edge */
  appearance: textfield; /* Standard syntax */
}

/* Hide the spin buttons in Chrome */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>