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
              <span class="text-gray-700 ">Note Type</span>
              <select v-model="form.note_type" class="form-input">
                <option value="">Select Type</option>
                <option>Delivery</option>
                <option>Re-delivery</option>
              </select>
              <Error v-if="errors?.note_type" :errors="errors.note_type" />
          </label>


          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Effective Date <span class="text-red-500">*</span></span>
              <input type="date" v-model.trim="form.effective_date" placeholder="Effective Date" class="form-input" autocomplete="off" />
              <Error v-if="errors?.effective_date" :errors="errors.effective_date" />

            </label>

          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Exchange Rate</span>
              <input type="number" step="0.001" v-model.trim="form.exchange_rate" placeholder="Exchange Rate" class="form-input" autocomplete="off" />
            <Error v-if="errors?.exchange_rate" :errors="errors.exchange_rate" />
          </label>

          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Currency</span>
                <select v-model="form.currency" class="form-input">
                  <option value="">Select Currency</option>
                  <option v-for="currency in currencies">{{ currency }}</option>
                </select>
                <Error v-if="errors?.currency" :errors="errors.currency" />
        </label>
          
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Vessel <span class="text-red-500">*</span></span>
              <v-select :options="vessels" placeholder="--Choose an option--" @search="fetchVessels"  v-model="form.opsVessel" label="name" class="block form-input">
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
              <span class="text-gray-700 ">Vessel Code</span>
              <input type="text" v-model.trim="form.short_code" placeholder="Vessel Code" class="form-input bg-gray-100" readonly autocomplete="off" />
            <Error v-if="errors?.short_code" :errors="errors.short_code" />
          </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Vessel Owner Name</span>
              <input type="text" v-model.trim="form.owner_name" placeholder="Vessel Owner Name" class="form-input bg-gray-100" readonly autocomplete="off" />
            <Error v-if="errors?.owner_name" :errors="errors.owner_name" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Vessel Capacity</span>
            <input type="text" v-model.trim="form.capacity" placeholder="Vessel Capacity" class="form-input bg-gray-100" readonly autocomplete="off" />
          <Error v-if="errors?.capacity" :errors="errors.capacity" />
        </label>
        
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Select Charterer <span class="text-red-500">*</span></span>
              <v-select :options="chartererProfiles" placeholder="--Choose an option--" @search="fetchCharterers"  v-model="form.opsChartererProfile" label="name" class="block form-input">
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
              <span class="text-gray-700 ">Charterer Code</span>
              <input type="text" v-model.trim="form.owner_code" placeholder="Charterer Code" class="form-input bg-gray-100" readonly autocomplete="off" />
            <Error v-if="errors?.owner_code" :errors="errors.owner_code" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Charterer Email</span>
              <input type="text" v-model.trim="form.email" placeholder="Charterer Email" class="form-input bg-gray-100" readonly autocomplete="off" />
            <Error v-if="errors?.email" :errors="errors.email" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Contact No.</span>
            <input type="text" v-model.trim="form.contact_no" placeholder="Contact No." class="form-input bg-gray-100" readonly autocomplete="off" />
          <Error v-if="errors?.contact_no" :errors="errors.contact_no" />
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">

        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 ">Remarks</span>
          <textarea type="text" v-model.trim="form.remarks" placeholder="Remarks" class="form-input w-full" autocomplete="off"></textarea>
          <Error v-if="errors?.remarks" :errors="errors.remarks" />
        </label>
        <label class="block w-full mt-2 text-sm"></label>


      </div>
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
              <th>Amount <small>(USD)</small></th>
              <th>Amount <small>(BDT)</small></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(certificate, index) in form.opsBunkers">
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
                  <input type="number" step="0.001" v-model.trim="form.opsBunkers[index].quantity" placeholder="Quantity" @keypress="calculatePrice(index)" class="form-input text-right" autocomplete="off"/>
                  <Error v-if="errors?.opsBunkers[index]?.quantity" :errors="errors.opsBunkers[index]?.quantity"/>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsBunkers[index].rate" placeholder="Rate" @keypress="calculatePrice(index)" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsBunkers[index]?.rate" :errors="errors.opsBunkers[index]?.rate"/>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model.trim="form.opsBunkers[index].amount_usd" placeholder="USD Amount" class="form-input text-right" autocomplete="off" readonly/>
                  <Error v-if="errors?.opsBunkers[index]?.amount_usd" :errors="errors.opsBunkers[index]?.amount_usd" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model.trim="form.opsBunkers[index].amount_bdt" placeholder="BDT Amount" class="form-input text-right" autocomplete="off" readonly/>
                  <Error v-if="errors?.opsBunkers[index]?.amount_bdt" :errors="errors.opsBunkers[index]?.amount_bdt" />
                </label>
              </td>
            </tr>
          </tbody>
        </table>
    </div>
    
</template>
<script setup>
import { ref, watch, onMounted } from "vue";
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useBusinessInfo from "../../composables/useBusinessInfo"
import useVessel from "../../composables/operations/useVessel";
import useChartererProfile from "../../composables/operations/useChartererProfile";

const editInitiated = ref(false);
const { getCurrencies, currencies } = useBusinessInfo();
const { vessel, vessels, searchVessels, showVessel } = useVessel();
const { searchChartererProfiles, chartererProfiles } = useChartererProfile();

const props = defineProps({
    form: {
        required: false,
        default: {},
    },
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required : false }
});

function fetchVessels(search, loading) {
      loading(true);
      searchVessels(search, props.form.business_unit, loading);
}

function fetchCharterers(search, loading) {
      loading(true);
      searchChartererProfiles(search, loading)
}

watch(() => props.form.opsVessel, (value) => {
  if(value) {
    props.form.ops_vessel_id = value?.id
    props.form.short_code = value?.short_code
    props.form.owner_name = value?.owner_name
    props.form.capacity = value?.capacity
    
    showVessel(value?.id)
  }
}, { deep: true})

watch(() => vessel, (value) => {
  console.log("vessel change ")
  if(value) {
    if(props?.formType == 'edit' && editInitiated.value == true) {
      props.form.opsBunkers = value?.value?.opsBunkers
    } else if(props?.formType == 'edit' && editInitiated.value != true) {
      editInitiated.value = true;
    }

    if(props?.formType == 'create') {
      props.form.opsBunkers = value?.value?.opsBunkers
    }
  }
}, { deep : true})


watch(() => props.form.opsChartererProfile, (value) => {
  if(value) {
    props.form.ops_charterer_profile_id = value?.id

    props.form.owner_code = value?.owner_code
    props.form.email = value?.email
    props.form.contact_no = value?.contact_no
  }
}, { deep: true})

watch(() => props.form.currency, (value) => {
  console.log("currency change ")

  if(value) {
    if(props?.formType == 'edit' && editInitiated.value == true) {

      for(const bunker of props.form.opsBunkers) {
        bunker.rate = 0;
      }
    }

    if(props?.formType == 'create') {

      for(const bunker of props.form.opsBunkers) {
        bunker.rate = 0;
      }
    }
  }
}, { deep: true})

watch(() => props.form.exchange_rate, (value) => {
console.log("exchange rate changing")
  if(value) {
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
  console.log("calculate price")
  let quantity = props.form.opsBunkers[index].quantity ?? 0;
  let rate = props.form.opsBunkers[index].rate ?? 0;
  let exchangeRate = props.form.exchange_rate ?? 0;

  let amount = parseFloat(quantity) * parseFloat(rate)

  if(props.form.currency == 'USD') {
    props.form.opsBunkers[index].amount_usd = amount ?? 0;
    props.form.opsBunkers[index].amount_bdt = amount * parseFloat(exchangeRate);
  } else {
    props.form.opsBunkers[index].amount_bdt = amount ?? 0;
    props.form.opsBunkers[index].amount_usd = parseFloat(amount / parseFloat(exchangeRate));
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
  @apply text-gray-700 ;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed ;
}
.form-input {
  @apply block mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple ;
}
</style>