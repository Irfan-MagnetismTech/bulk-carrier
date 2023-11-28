<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel <span class="text-red-500">*</span></span>
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
              <span class="text-gray-700 dark-disabled:text-gray-300">Voyage </span>
              <v-select :options="voyages" placeholder="--Choose an option--" v-model="form.opsVoyage" label="voyage_sequence" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-model="form.ops_voyage_id" />
      </label>
      
      
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Ship Master <span class="text-red-500">*</span></span>
              <input type="text" v-model.trim="form.ship_master" required placeholder="Ship Master" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Chief Engineer <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.chief_engineer" required placeholder="Chief Engineer" class="form-input" autocomplete="off" />
      </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Noon Position <span class="text-red-500">*</span></span>
              <input type="text" v-model.trim="form.noon_position" required placeholder="Noon Position" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Status</span>
              <input type="text" v-model.trim="form.status" placeholder="Status" class="form-input" autocomplete="off" />
        </label>
        
        
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Engine Running Hours</span>
              <input type="number" step="0.001" v-model.trim="form.engine_running_hours" placeholder="Engine Running Hours" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Lat/Long <span class="text-red-500">*</span></span>
              <input type="text" v-model.trim="form.lat_long" required placeholder="Lat/Long" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Date <span class="text-red-500">*</span></span>
              <input type="datetime-local" v-model.trim="form.date" required placeholder="Vessel" class="form-input" autocomplete="off" />
        </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">

        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Last Port <span class="text-red-500">*</span></span>
          <v-select :options="ports" placeholder="Search Port" v-model="form.lastPort" label="code_name" class="block form-input">
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.lastPort"
                    v-bind="attributes"
                    v-on="events"
                    />
            </template>
          </v-select>
          <input type="hidden" v-model="form.last_port" />
        </label>

        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Next Port <span class="text-red-500">*</span></span>
          <v-select :options="ports" placeholder="Search Port" v-model="form.nextPort" label="code_name" class="block form-input">
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.nextPort"
                    v-bind="attributes"
                    v-on="events"
                    />
            </template>
          </v-select>
          <input type="hidden" v-model="form.next_port" />
        </label>
    </div>

    <RemarksComponet v-model="form.remarks" :maxlength="300" :fieldLabel="'Remarks'"></RemarksComponet>

    <div id="bunkers" class="mt-5" v-if="form.opsBunkers?.length > 0">
      <h4 class="text-md font-semibold my-3">Bunker Info</h4>

      <table class="w-full whitespace-no-wrap" >
          <thead v-once>
            <tr class="w-full">
              <th>SL</th>
              <th class="w-72">Bunker Name</th>
              <th>Unit</th>
              <th><nobr> Present Stock </nobr></th>
              <th><nobr> FUEL - CON/24H </nobr></th>
              <th><nobr> FUEL - CON/Voyage </nobr></th>
              <th class="hidden"><nobr> FUEL - Stock/L </nobr></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(certificate, index) in form.opsBunkers" :key="index">
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
                <label class="block w-full mt-2 text-sm">
                  <span class="show-block !block !bg-gray-100 !text-right">{{ form.opsBunkers[index].opening_balance }}</span>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsBunkers[index].fuel_con_24h" placeholder="FUEL - CON/24H" class="form-input text-right" autocomplete="off"/>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsBunkers[index].fuel_con_voyage" placeholder="FUEL - CON/Voyage" class="form-input text-right" autocomplete="off"/>
                </label>
              </td>
              <td class="hidden">
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsBunkers[index].fuel_stock_l" placeholder="FUEL - Stock/L" class="form-input text-right" autocomplete="off"/>
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
import useVoyage from "../../composables/operations/useVoyage";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import usePort from '../../composables/operations/usePort';
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import RemarksComponet from '../../components/utils/RemarksComponent.vue';

const editInitiated = ref(false);

const { ports, getPortList } = usePort();
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


watch(() => props.form.business_unit, (value) => {
  if(props?.formType != 'edit') {
    props.form.opsVoyage = null;
    props.form.ops_voyage_id = null;
    props.form.opsVessel = null;
    props.form.ops_vessel_id = null;
  }
  
  getVesselList(props.form.business_unit);

}, { deep : true })

watch(() => props.form.opsVoyage, (value) => {
  
  if(value) {
    props.form.ops_voyage_id = value?.id
  }
}, { deep: true })

watch(() => props.form.opsVessel, (value) => {
  
  if(value) {
    props.form.ops_vessel_id = value?.id
    let loadStatus = false;
    showVessel(value?.id, loadStatus);

    if(props?.formType != 'edit' || (props?.formType == 'edit' && editInitiated.value == true)) {
      voyages.value = [];
      props.form.opsVoyage = null;
      props.form.ops_voyage_id = null;
    }
    getVoyageList(props.form.business_unit, props.form.ops_vessel_id);
  }
}, { deep: true })

watch(() => props.form.lastPort, (value) => {
  if(value) {
    props.form.last_port = value?.code
  }
}, { deep: true })

watch(() => props.form.nextPort, (value) => {
  if(value) {
    props.form.next_port = value?.code
  }
}, { deep: true })


watch(() => vessel, (value) => {
  if(value?.value) {
    if(props?.formType != 'edit' || (props?.formType == 'edit' && editInitiated.value == true)) {
      props.form.opsBunkers = value?.value?.opsBunkers
    }
  }
}, { deep: true })

watch(() => vessels, (value) => {

  if(props?.formType == 'edit' && editInitiated.value != true) {
   
    if(vessels.value.length > 0) {
        editInitiated.value = true
      }
  }
}, {deep: true});

onMounted(() => {
  getPortList();
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