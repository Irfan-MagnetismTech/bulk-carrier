<template>
  <LoaderComponent :isLoading = isVesselLoading></LoaderComponent>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">      
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700">Voyage <span class="text-red-500">*</span></span>
              <input type="text" :value="form.opsVoyage?.voyage_sequence" class="form-input !bg-gray-100" readonly>
              <input type="hidden" v-model="form.ops_voyage_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700">Vessel</span>
              <input type="text" readonly v-model.trim="form.vessel_name" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700">Requisition No. <span class="text-red-500">*</span></span>
              <input type="text" readonly v-model.trim="form.requisition_no" placeholder="Requisition No." class="form-input  !bg-gray-100" autocomplete="off" required/>
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700">Remarks</span>
              <input type="text" readonly v-model.trim="form.remarks" placeholder="Remarks" class="form-input  !bg-gray-100" autocomplete="off" />
        </label>
    </div>

    <div id="sectors" class="mt-5" v-if="form.opsBunkers?.length > 0">
      <h4 class="text-md font-semibold my-3">Bunker Info</h4>

      <table class="w-full whitespace-no-wrap" >
          <thead v-once>
            <tr class="w-full">
              <th class="w-16">SL</th>
              <th>Bunker Name/Particulars</th>
              <th class="w-40">Unit</th>
              <th class="w-40">Supplier</th>
              <th class="w-40">Quantity</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(bunker, index) in form.opsBunkers" :key="index">
              <td>
                {{ index+1 }}
              </td>
              <td>                
                <span class="show-block !bg-gray-100">{{ form.opsBunkers[index].name }}</span>
              </td>
              <td>
                <span class="show-block !justify-center !bg-gray-100" v-if="form.opsBunkers[index]?.unit">{{ form.opsBunkers[index]?.unit }}</span>
              </td>
              <td>
                <!-- <v-select :options="form.opsSuppliers" placeholder="--Choose an option--" :loading="isSupplierLoading"  v-model="form.opsVoyage" label="supplier" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsSuppliers"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
                </v-select> -->
              </td>
              <td>
                <label class="block w-full mt-2 text-sm ">
                  <input type="text" v-model.trim="form.opsBunkers[index].quantity" placeholder="Stock In" class="form-input text-right !bg-gray-100" readonly autocomplete="off" />
                </label>
              </td>
            </tr>
          </tbody>
        </table>      
    </div>
    <div class="mt-5">
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700">Note</span>
          <textarea rows="3" v-model.trim="form.note" placeholder="Note" class="form-input" autocomplete="off"></textarea>
          <!-- <input type="text"  /> -->
        </label>
    </div>
    <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<script setup>
import { ref, watch, onMounted, watchEffect  } from "vue";
import Error from "../Error.vue";
import useVoyage from "../../composables/operations/useVoyage";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import LoaderComponent from "../../components/utils/LoaderComponent.vue";
import ErrorComponent from "../utils/ErrorComponent.vue";

const editInitiated = ref(false);

const { voyage, voyages, showVoyage, searchVoyages, isVoyageLoading } = useVoyage();
const { vessel, showVessel, isVesselLoading } = useVessel();

const props = defineProps({
    form: {
        required: false,
        default: {},
    },
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required : false }
});


function fetchVoyages(searchParam, loading) {
  searchVoyages(searchParam, props.form.business_unit, loading);
}


watch(() => props.form.opsVoyage, (value) => {
  voyage.value = null;
  vessel.value = null;
  if(value) {
    props.form.ops_voyage_id = value?.id
    props.form.ops_vessel_id = value?.ops_vessel_id
    showVoyage(value?.id)
  }
}, { deep: true });


const bunkerReset = ref([]);

watch(() => props.form.ops_vessel_id, (newValue, oldValue) => {
  
  props.form.ops_vessel_id = newValue;
  if(newValue !== oldValue && newValue != undefined){
    showVessel(newValue)
    .then(() => {
        props.form.vessel_name = vessel?.value?.name;
        bunkerReset.value = vessel?.value?.opsBunkers?.map(obj => {
      // Assuming you want to reset the resettableValue property to some default value
      return {
        ...obj,
        exchange_rate_bdt: null,
        exchange_rate_usd: null,
        rate: null,
        amount_usd: null,
        amount_bdt: null,
        quantity: null
      };
    });


      if((props?.formType == 'approve' && editInitiated.value == true) || (props.formType != 'approve')) {
        props.form.opsBunkers = bunkerReset.value
      } else {
      //   bunkerReset.value;
        editInitiated.value = true
      }
    })
    .catch((error) => {
      console.error("Error fetching data.", error);
    });
  }
},{deep:true});


onMounted(() => {
    watchEffect(() => {
      if(props.form.business_unit && props.form.business_unit != 'ALL'){
        fetchVoyages("", false);
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
  @apply text-gray-700;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
}
</style>