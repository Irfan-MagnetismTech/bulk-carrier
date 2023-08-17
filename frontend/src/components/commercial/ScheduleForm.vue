<script setup>
import {onMounted} from "@vue/runtime-core";
import usePort from "../../composables/usePort";
import {computed, ref, watch} from "vue";
import NProgress from "nprogress";
import Error from "../Error.vue";
import useService from "../../composables/commercial/useService";
import useVessel from "../../composables/dataencoding/useVessel";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
  page: {
    required: false,
    default: {},
  }
});
const bounds = { 'east': 'east', 'west': 'west', 'north': 'north', 'south': 'south' };

const { service, services, getServices, serviceGroupById, showService, uniqueServicePorts, getServiceUniquePorts } = useService();
const { vessels, getVesselWithoutPaginate } = useVessel();

function addSchedule() {
  let obj = {
    bound: '',
    voyage_no: '',
    voyage: '',
    port_id: '',
    port_code: '',
    etb_date: '',
    etd_date: '',
  };
  props.form.schedule_ports.push(obj);
}

function removeSchedule(index){
  props.form.schedule_ports.splice(index, 1);
}

watch(() => props.form.service_id, (val) => {
  if (val) {
    serviceGroupById(val);
    getServiceUniquePorts(val);
  }
});

function prepareVoyageNo(index){

  let bound = props.form.schedule_ports[index].bound.charAt(0).toUpperCase();
  let voyage = '00'+props.form.voyage_no+bound;
  props.form.schedule_ports[index].voyage = voyage;
}

// watch(() => props.form, (value) => {
//   if(value?.sectors?.length) {
//     value.sectors.forEach((sector, index) => {
//       props.form.sectors[index].pol = sector?.pol_code_name?.code ?? '';
//       props.form.sectors[index].pod = sector?.pod_code_name?.code ?? '';
//     });
//   }
// }, {deep: true});

onMounted(() => {
  getServices();
  getVesselWithoutPaginate();
});

</script>

<template>
    <!-- Basic information -->
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm md:w-2/6">
          <span class="text-gray-700 dark:text-gray-300">Service <span class="text-red-500">*</span></span>
          <select v-model="form.service_id" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled selected>--Choose an Option--</option>
            <option v-for="(service,index) in services" :value="service.id" :key="index">{{ service.name }} - ({{ service.code }})</option>
          </select>
<!--          <Error v-if="errors?.code" :errors="errors.code" />-->
        </label>
        <label class="block w-full mt-3 text-sm md:w-2/6">
            <span class="text-gray-700 dark:text-gray-300">Vessel <span class="text-red-500">*</span></span>
            <select name="service_id" v-model="form.vessel_id" id="contract_service_id" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <option value="" disabled selected>--Choose an Option--</option>
              <option v-for="(vessel,index) in vessels" :value="vessel.id" :key="index">{{ vessel.name }}</option>
            </select>
        </label>

      <label class="block w-full mt-3 text-sm md:w-2/6">
        <span class="text-gray-700 dark:text-gray-300">Voyage No<span class="text-red-500">*</span></span>
        <input type="text" v-model="form.voyage_no" required placeholder="voyage serial no" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      </label>

      <label class="block w-full mt-3 text-sm md:w-2/6">
        <span class="text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></span>
        <select name="service_id" v-model="form.status" id="contract_service_id" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="" disabled selected>--Choose an Option--</option>
          <option value="Not-Published">Save</option>
          <option value="Published">Save & Publish</option>
        </select>
      </label>
    </div>
    <!-- Basic information -->

    <!-- South Sectors -->
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 dark:text-gray-300">Schedules <span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    <th class="px-4 py-3 align-bottom">Bound</th>
                    <th class="px-4 py-3 align-bottom">Voyage</th>
                    <th class="px-4 py-3 align-bottom">Port</th>
                    <th class="px-4 py-3 align-bottom">ETB</th>
                    <th class="px-4 py-3 align-bottom">ETD</th>
                    <th class="px-4 py-3 text-center align-bottom">Action</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <tr class="text-gray-700 dark:text-gray-400" v-for="(sector, index) in form.schedule_ports" :key="sector.id">
                    <td class="px-1 py-1" style="width: 20%">
                        <select v-model="form.schedule_ports[index].bound" @change="prepareVoyageNo(index)" name="bound" required class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                            <option value="" selected disabled>-- Select Bound --</option>
                            <option :value="singleBound" v-for="singleBound in service['bounds']">{{ singleBound.toUpperCase() }}</option>
                        </select>
                    </td>

                  <td class="px-1 py-1" style="width: 20%">
                    <label class="block w-full text-sm">
                      <input type="text" v-model="form.schedule_ports[index].voyage" disabled required placeholder="Ex: 0031S" class="block vms-readonly-input w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      <input type="hidden" v-model="form.schedule_ports[index].voyage">
                    </label>
                  </td>

                  <td class="px-1 py-1" style="width: 20%">
                    <select v-model="form.schedule_ports[index].port_code" name="port_code" required class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                      <option value="" selected disabled>-- Select Bound --</option>
                      <option :value="uniquePort" v-for="uniquePort in uniqueServicePorts">{{ uniquePort }}</option>
                    </select>
                  </td>

                  <td class="px-1 py-1" style="width: 20%">
                    <label class="block w-full text-sm">
                      <input type="date" v-model="form.schedule_ports[index].etb_date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                  </td>

                  <td class="px-1 py-1" style="width: 20%">
                    <label class="block w-full text-sm">
                      <input type="date" v-model="form.schedule_ports[index].etd_date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                  </td>
                  <td class="px-1 py-1 text-center">
                    <button v-if="index!=0" type="button" @click="removeSchedule(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                      </svg>
                    </button>
                    <button v-else type="button" @click="addSchedule()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                      </svg>
                    </button>
                  </td>
                </tr>
            </tbody>
        </table>
    </fieldset>
</template>

<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
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