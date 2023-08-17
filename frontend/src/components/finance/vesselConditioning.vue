<script setup>
import { ref, watch, computed } from "vue";
import usePort from "../../composables/usePort";
import { onMounted } from "vue";
import Error from "../Error.vue";
import useNotification from "../../composables/useNotification.js";
import useSlotPartner from "../../composables/dataencoding/useSlotPartner";
import useVoyage from "../../composables/operation/useVoyage";
import DropZone from "../../components/DropZone.vue";
import env from "../../config/env.js";
import { useStore } from "vuex";

const props = defineProps({
  form: {
    required: false,
    default: {},
  },
  customTdr : {
    required: false
  },
  page: {
    required: false,
    default: {},
  },
  errors: { type: [Object, Array], required: false },
});

const notification = useNotification();
const { voyageName, getVoyageName } = useVoyage();
const validationErrors = ref({});

const { ports, getPortsByNameOrCode, portName } = usePort();
const { slotPartnerCode, getSlotPartnerByNameOrCode } = useSlotPartner();

const isAgentBillingStyleModalOpen = ref(0);
const openTab = ref(1);
const voyageNo = ref(null);
const portChange = ref(null);
const toggleTabs = (tabNumber, buttonType = null) => {
  if (buttonType === "back") {
    openTab.value = tabNumber;
  } else {
    // check required fields is empty or not
    // if (openTab.value === 1) {
    //   if (
    //     props.form.customer_code === "" ||
    //     props.form.customer_name === "" ||
    //     props.form.company_name === "" ||
    //     props.form.country === "" ||
    //     props.form.similar_codes === ""
    //   ) {
    //     // form validation start for customer code start
    //     if (props.form.customer_code === "") {
    //       document
    //         .getElementById("customer_code")
    //         .classList.add("vms-required-input-border");
    //     } else {
    //       document
    //         .getElementById("customer_code")
    //         .classList.remove("vms-required-input-border");
    //     }

    //     if (props.form.company_name === "") {
    //       document
    //         .getElementById("company_name")
    //         .classList.add("vms-required-input-border");
    //     } else {
    //       document
    //         .getElementById("company_name")
    //         .classList.remove("vms-required-input-border");
    //     }

    //     if (props.form.country === "") {
    //       document
    //         .getElementById("country")
    //         .classList.add("vms-required-input-border");
    //     } else {
    //       document
    //         .getElementById("country")
    //         .classList.remove("vms-required-input-border");
    //     }
    //     // form validation start for customer code end

    //     if (buttonType === "next") {
    //       notification.showError(422, "", "Please fill all required fields");
    //     }
    //     return;
    //   }
    // }
    // if (openTab.value === 2) {
    //   if (props.form.customer_general_email === "") {
    //     if (props.form.customer_general_email === "") {
    //       document
    //         .getElementById("customer_general_email")
    //         .classList.add("vms-required-input-border");
    //     } else {
    //       document
    //         .getElementById("customer_general_email")
    //         .classList.remove("vms-required-input-border");
    //     }
    //     // return with a message
    //     if (buttonType === "next") {
    //       notification.showError(422, "", "Please fill all required fields");
    //     }
    //     return;
    //   }
    // }
    openTab.value = tabNumber;
  }
};
/* add and remove contact dynamic start */

function addTerminal() {
  let obj = {
    description: "",
    commence: "",
    complete: "",
    break_down: "",
    meal: "",
    weather: "",
    other: "",
    total: "",
    action: "",
  };

  props.form.terminal_works.push(obj);
}
function removeTerminal(index) {
  props.form.terminal_works.splice(index, 1);
}

function fetchOptions(search, loading) {
  getPortsByNameOrCode(search);
}
/* add and remove contact dynamic end */

function fetchSlotPartner(search, loading) {
  getSlotPartnerByNameOrCode(search);
}
function fetchVoyages(search, loading) {
  var data = getVoyageName(search);
  console.log(console.log('data',data))
}

onMounted(()=> {
  console.log('voyage', props.form.voyage)
})

watch(
  () => props.form.voyage,
  (value) => {
    voyageNo.value = value
    },
  { deep: true }
);

watch(
  () => props.form.port,
  (value) => {
    portChange.value = value
  },
  { deep: true }
);

function openAgentBillingStyleModal() {
  isAgentBillingStyleModalOpen.value = 1;
}

function closeAgentBillingStyleModal() {
  isAgentBillingStyleModalOpen.value = 0;
}

function downloadSampleTdrFile() {
  let baseUrl = env.BASE_API_URL;
  window.location.href = baseUrl + "samples/Agent TDR.xlsx";
}

watch(portChange, (value) => {
  console.log("portChange", value);
  if (value) {
    props.form.port = value;
  }
});

watch(voyageNo, (value) => {
  console.log('props.voyage.value', props.form.voyage);
  if (value) {
  props.form.voyages_id = value?.id;
  }
}, {deep: true}); 

const store = useStore();
const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));

watch(dropZoneFile, (value) => {
  console.log("dropZoneFile", value);
  if (value !== null && value !== undefined) {
     props.customTdr.file = value;
  }
});
</script>

<template xmlns="http://www.w3.org/1999/html">
  <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
    <div>
      <label class="mt-1 block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300"
          >Select Voyage <span class="text-red-500">*</span></span
        >
      </label>
      <v-select
        v-model="props.form.voyage"
        class="mt-1 w-full placeholder-gray-600"
        :options="voyageName"
        placeholder="--Choose an option--"
        @search="fetchVoyages"
        label="voyage"
        required
      ></v-select>
      <input type="hidden" v-model="props.form.voyages_id" />

    </div>
    <div class="ml-5">
      <label class="mt-1 block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300"
          >Port <span class="text-red-500">*</span></span
        >
      </label>
      <v-select
        v-model="portChange"
        @search="fetchOptions"
        required
        :options="portName"
        label="code_name"
        :reduce="(portName) => portName.code"
        placeholder="Enter Port Code or Name"
        class="mt-1 w-full placeholder-gray-600"
      ></v-select>
      <Error v-if="errors?.port" :errors="errors.port" />
    </div>
    <div class="ml-5">
      <label class="mt-1 block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300"
          >Terminal <span class="text-red-500">*</span></span
        >
      </label>
      <input
                v-model="props.form.terminal"
                type="text"
                class="label-item-input"
              />
    </div>
  </div>
  <div class="border-b border-gray-200 dark:border-gray-700">
    <ul class="-mb-px flex flex-wrap">
      <li class="mr-2">
        <a
          href="#"
          class="group inline-flex rounded-t-lg border-b-2 border-transparent px-4 py-4 text-center text-sm font-medium text-gray-500 dark:text-gray-400"
          v-on:click="toggleTabs(1)"
          v-bind:class="{
            'text-purple-600 bg-white': openTab !== 1,
            'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active':
              openTab === 1,
          }"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="mr-2 h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            /></svg
          >ARR/DEP Time and Condition
        </a>
      </li>
      <li class="mr-2">
        <a
          href="#"
          class="group inline-flex rounded-t-lg border-b-2 border-transparent px-4 py-4 text-center text-sm font-medium text-gray-500 dark:text-gray-400"
          v-on:click="toggleTabs(2)"
          v-bind:class="{
            'text-purple-600 bg-white': openTab !== 2,
            'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active':
              openTab === 2,
          }"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="mr-2 h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            /></svg
          >ETA Next Port
        </a>
      </li>
      <li class="mr-2">
        <a
          href="#"
          class="group inline-flex rounded-t-lg border-b-2 border-transparent px-4 py-4 text-center text-sm font-medium text-gray-500 dark:text-gray-400"
          v-on:click="toggleTabs(3)"
          v-bind:class="{
            'text-purple-600 bg-white': openTab !== 3,
            'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active':
              openTab === 3,
          }"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="mr-2 h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            /></svg
          >Port Log
        </a>
      </li>
      <li class="mr-2">
        <a
          href="#"
          class="group inline-flex rounded-t-lg border-b-2 border-transparent px-4 py-4 text-center text-sm font-medium text-gray-500 dark:text-gray-400"
          v-on:click="toggleTabs(4)"
          v-bind:class="{
            'text-purple-600 bg-white': openTab !== 4,
            'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active':
              openTab === 4,
          }"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="mr-2 h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            /></svg
          >Cargo Info
        </a>
      </li>
      <li class="mr-2">
        <a
          href="#"
          class="group inline-flex rounded-t-lg border-b-2 border-transparent px-4 py-4 text-center text-sm font-medium text-gray-500 dark:text-gray-400"
          v-on:click="toggleTabs(5)"
          v-bind:class="{
            'text-purple-600 bg-white': openTab !== 5,
            'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active':
              openTab === 5,
          }"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="mr-2 h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            /></svg
          >Remarks
        </a>
      </li>
    </ul>
    <div
      v-on:click="toggleTabs(1)"
      v-bind:class="{ hidden: openTab !== 1, block: openTab === 1 }"
    >
      <strong>1) Time</strong>
      <table class="w-full table-auto">
        <thead>
          <tr class="light-gray">
            <th class="diag"></th>
            <th>Arrival</th>
            <th class="diag"></th>
            <th>Departure</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="light-gray">First Pilot</td>
            <td>
              <input
                v-model="form.vessel_info.first_pilot_on"
                type="datetime-local"
                class="w-full border-none"
              />
            </td>
            <td class="light-gray">Unberth</td>
            <td>
              <input
                v-model="form.vessel_info.departure_unberth"
                type="datetime-local"
                class="w-full border-none"
              />
            </td>
          </tr>
          <tr>
            <td class="light-gray">Anchorage</td>
            <td>
              <input
                v-model="form.vessel_info.arrival_anchorage"
                type="datetime-local"
                placeholder="Anchorage Input"
                class="w-full border-none"
              />
            </td>
            <td class="light-gray">Anchorage</td>
            <td>
              <input
                v-model="form.vessel_info.departure_anchorage"
                type="datetime-local"
                placeholder="Anchorage Input"
                class="w-full border-none"
              />
            </td>
          </tr>
          <tr>
            <td class="light-gray">Berth</td>
            <td>
              <input
                v-model="form.vessel_info.berth"
                type="datetime-local"
                class="w-full border-none"
              />
            </td>
            <td class="light-gray">Last Pilot Off</td>
            <td>
              <input
                v-model="form.vessel_info.last_pilot_off"
                type="datetime-local"
                placeholder="Anchorage Input"
                class="w-full border-none"
              />
            </td>
          </tr>
        </tbody>
      </table>

      <strong>2) Condition</strong>
      <table class="td_first_child_gray w-full">
        <thead>
          <tr>
            <th class="light-gray diag"></th>
            <th colspan="4" class="light-gray">Arrival</th>
            <th colspan="4" class="light-gray">Departure</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td rowspan="2">Draft</td>
            <td>Fwd</td>
            <td colspan="3">
              <input
                v-model="form.vessel_usage.arrival_draft_fwd"
                type="text"
                placeholder="Fwd Input"
                class="w-full border-none"
              />
            </td>
            <td>Fwd</td>
            <td colspan="3">
              <input
                v-model="form.vessel_usage.departure_draft_fwd"
                type="text"
                placeholder="Departure Fwd Input"
                class="w-full border-none"
              />
            </td>
          </tr>
          <tr>
            <td>Aft</td>
            <td colspan="3">
              <input
                v-model="form.vessel_usage.arrival_draft_aft"
                type="text"
                placeholder="Arrival Aft Input"
                class="w-full border-none"
              />
            </td>
            <td>Aft</td>
            <td colspan="3">
              <input
                v-model="form.vessel_usage.departure_draft_aft"
                type="text"
                placeholder="Departure Aft Input"
                class="w-full border-none"
              />
            </td>
          </tr>

          <tr>
            <td>Ballast(M/T)</td>
            <td colspan="4">
              <input
                v-model="form.vessel_usage.arrival_ballast"
                type="text"
                placeholder="Arrival Ballast(M/T) Input"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                v-model="form.vessel_usage.departure_ballast"
                type="text"
                placeholder="Departure Ballast(M/T) Input"
                class="w-full border-none"
              />
            </td>
          </tr>

          <tr>
            <td rowspan="3">R.O.B (M/T)</td>
            <td>F/O</td>
            <td>
              <input
                v-model="form.vessel_usage.arrival_rob_fo"
                type="text"
                placeholder="Arrival F/O Input"
                class="w-full border-none"
              />
            </td>
            <td>D/O</td>
            <td>
              <input
                v-model="form.vessel_usage.arrival_rob_do"
                type="text"
                placeholder="Arrival D/O Input"
                class="w-full border-none"
              />
            </td>
            <td>F/O</td>
            <td>
              <input
                v-model="form.vessel_usage.departure_rob_fo"
                type="text"
                placeholder="Departure F/O Input"
                class="w-full border-none"
              />
            </td>
            <td>D/O</td>
            <td>
              <input
                v-model="form.vessel_usage.departure_rob_do"
                type="text"
                placeholder="Departure D/O Input"
                class="w-full border-none"
              />
            </td>
          </tr>
          <tr>
            <td>F/W</td>
            <td>
              <input
                v-model="form.vessel_usage.arrival_rob_fw"
                type="text"
                placeholder="Arrival F/W Input"
                class="w-full border-none"
              />
            </td>
            <td></td>
            <td></td>
            <td>F/W</td>
            <td>
              <input
                v-model="form.vessel_usage.departure_rob_fw"
                type="text"
                placeholder="Departure F/W Input"
                class="w-full border-none"
              />
            </td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>LSFO</td>
            <td>
              <input
                v-model="form.vessel_usage.arrival_rob_lsfo"
                type="text"
                placeholder="Arrival LSFO Input"
                class="w-full border-none"
              />
            </td>
            <td>LSDO</td>
            <td>
              <input
                v-model="form.vessel_usage.arrival_rob_lsdo"
                type="text"
                placeholder="Arrival LSDO Input"
                class="w-full border-none"
              />
            </td>
            <td>LSFO</td>
            <td>
              <input
                v-model="form.vessel_usage.departure_rob_lsfo"
                type="text"
                placeholder="Departure LSFO Input"
                class="w-full border-none"
              />
            </td>
            <td>LSDO</td>
            <td>
              <input
                v-model="form.vessel_usage.departure_rob_lsdo"
                type="text"
                placeholder="Departure LSDO Input"
                class="w-full border-none"
              />
            </td>
          </tr>

          <tr>
            <td rowspan="2">Bunker Supply</td>
            <td>F/O</td>
            <td>
              <input
                v-model="form.vessel_usage.arrival_bunker_fo"
                type="text"
                placeholder="Arrival F/O Input"
                class="w-full border-none"
              />
            </td>
            <td>D/O</td>
            <td>
              <input
                v-model="form.vessel_usage.arrival_bunker_do"
                type="text"
                placeholder="Arrival D/O Input"
                class="w-full border-none"
              />
            </td>
            <td>F/O</td>
            <td>
              <input
                v-model="form.vessel_usage.departure_bunker_fo"
                type="text"
                placeholder="Departure F/O Input"
                class="w-full border-none"
              />
            </td>
            <td>D/O</td>
            <td>
              <input
                v-model="form.vessel_usage.departure_bunker_do"
                type="text"
                placeholder="Departure D/O Input"
                class="w-full border-none"
              />
            </td>
          </tr>
          <tr>
            <td colspan="4"></td>
            <td>LSFO</td>
            <td>
              <input
                v-model="form.vessel_usage.departure_bunker_lsfo"
                type="text"
                placeholder="Departure LSFO Input"
                class="w-full border-none"
              />
            </td>
            <td>LSDO</td>
            <td>
              <input
                v-model="form.vessel_usage.departure_bunker_lsdo"
                type="text"
                placeholder="Departure LSDO Input"
                class="w-full border-none"
              />
            </td>
          </tr>

          <tr>
            <td>F/W Supply</td>
            <td colspan="4">
              <input
                v-model="form.vessel_usage.arrival_fw_supply"
                type="text"
                placeholder="Arrival F/W Supply Input"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                v-model="form.vessel_usage.departure_fw_supply"
                type="text"
                placeholder="Departure F/W Supply Input"
                class="w-full border-none"
              />
            </td>
          </tr>
          <tr>
            <td>Dead-Weight(M/T)</td>
            <td colspan="4">
              <input
                v-model="form.vessel_usage.arrival_dead_weight"
                type="text"
                placeholder="Arrival Dead-Weight(M/T) Input"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                v-model="form.vessel_usage.departure_dead_weight"
                type="text"
                placeholder="Departure Dead-Weight(M/T) Input"
                class="w-full border-none"
              />
            </td>
          </tr>
          <tr>
            <td>Displacement(M/T)</td>
            <td colspan="4">
              <input
                v-model="form.vessel_usage.arrival_displacement"
                type="text"
                placeholder="Arrival Displacement(M/T) Input"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                v-model="form.vessel_usage.departure_displacement"
                type="text"
                placeholder="Departure Displacement(M/T) Input"
                class="w-full border-none"
              />
            </td>
          </tr>
          <tr>
            <td>Stability(GM)</td>
            <td colspan="4">
              <input
                v-model="form.vessel_usage.arrival_stability"
                type="text"
                placeholder="Arrival Stability(GM) Input"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                v-model="form.vessel_usage.departure_stability"
                type="text"
                placeholder="Departure Stability(GM) Input"
                class="w-full border-none"
              />
            </td>
          </tr>
          <tr>
            <td>Tug Boat</td>
            <td colspan="4">
              <input
                type="text"
                v-model="form.vessel_usage.arrival_no_of_tug"
                placeholder="Arrival Tug Boat Input"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                v-model="form.vessel_usage.departure_no_of_tug"
                type="text"
                placeholder="Departure Tug Boat Input"
                class="w-full border-none"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div
      class="mt-2"
      v-on:click="toggleTabs(2)"
      v-bind:class="{ hidden: openTab !== 2, block: openTab === 2 }"
    >
      <table class="table-auto">
        <thead>
          <tr>
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Port</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="border px-4 py-2">
              <input
                v-model="form.vessel_info.eta_next_port"
                type="datetime-local"
                placeholder="date"
                class="w-full border-none"
              />
            </td>
            <td class="border px-4 py-2">
              <v-select
                v-model="form.vessel_info.next_port"
                @search="fetchOptions"
                required
                :options="portName"
                label="code_name"
                :reduce="(portName) => portName.code"
                placeholder="Enter Port Code or Name"
                class="mt-1 w-full placeholder-gray-600"
              ></v-select>
              <Error v-if="errors?.port" :errors="errors.port" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div
      v-on:click="toggleTabs(3)"
      v-bind:class="{ hidden: openTab !== 3, block: openTab === 3 }"
    >
      <div>
        <strong class="main-list d-block">3. Port Log</strong>
        <div class="sub-list">
          <strong>1) TERMINAL WORKING</strong>
          <table class="w-full">
            <thead>
              <tr class="light-gray">
                <th class="diag"></th>
                <th>
                  Work <br />
                  Commenced
                </th>
                <th>
                  Work <br />
                  Completed
                </th>
                <th>
                  A <br />
                  B. Down
                </th>
                <th>
                  B <br />
                  Meal
                </th>
                <th>
                  C <br />
                  Weather
                </th>
                <th>
                  D <br />
                  Other
                </th>
                <th>Total</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in form.terminal_works" :key="index">
                <td>
                  <input
                    v-model="form.terminal_works[index].description"
                    type="text"
                    placeholder="Terminal Work"
                    class="w-full border-none"
                  />
                </td>
                <td>
                  <input
                    v-model="form.terminal_works[index].commence"
                    type="datetime-local"
                    placeholder="Work commenced"
                    class="w-full border-none"
                  />
                </td>
                <td>
                  <input
                    v-model="form.terminal_works[index].complete"
                    type="datetime-local"
                    placeholder="Work Completed"
                    class="w-full border-none"
                  />
                </td>
                <td>
                  <input
                    v-model="form.terminal_works[index].break_down"
                    type="text"
                    placeholder="Down"
                    class="w-full border-none"
                  />
                </td>
                <td>
                  <input
                    v-model="form.terminal_works[index].meal"
                    type="text"
                    placeholder="Meal"
                    class="w-full border-none"
                  />
                </td>
                <td>
                  <input
                    v-model="form.terminal_works[index].weather"
                    type="text"
                    placeholder="Weather"
                    class="w-full border-none"
                  />
                </td>
                <td>
                  <input
                    v-model="form.terminal_works[index].other"
                    type="text"
                    placeholder="Other"
                    class="w-full border-none"
                  />
                </td>
                <td>
                  <input
                    type="text"
                    v-model="form.terminal_works[index].total"
                    placeholder="Total"
                    class="w-full border-none"
                  />
                </td>
                <td>
                  <button
                    v-if="index != 0"
                    type="button"
                    @click="removeTerminal(index)"
                    class="focus:outline-none rounded-md border border-transparent bg-red-600 px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-purple-700 focus:shadow-outline-purple active:bg-purple-600"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-6 w-6"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20 12H4"
                      />
                    </svg>
                  </button>
                  <button
                    v-else
                    type="button"
                    @click="addTerminal()"
                    class="focus:outline-none rounded-md border border-transparent bg-green-600 px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-purple-700 focus:shadow-outline-purple active:bg-purple-600"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="h-5"></div>

          <div class="flex gap-2">
            <div class="w-full">
              <strong class="d-block">2) Average Number of Used Crane: </strong>
              <input
                v-model="form.vessel_info.average_number_of_used_crane"
                type="text"
                placeholder="Average Number of Used Crane"
                class="w-full"
              />
              <strong class="d-block">3) Working Hours:</strong>
              <table class="td_first_child_gray w-full">
                <tbody>
                  <tr>
                    <td class="w-50">Gross Working Hours</td>
                    <td>
                      <input
                        v-model="form.vessel_info.gross_working_hours"
                        type="text"
                        placeholder="Gross Working Hours"
                        class="w-full border-none"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td>Net Working Hours</td>
                    <td>
                      <input
                        v-model="form.vessel_info.net_working_hours"
                        type="text"
                        placeholder="Net Working Hours"
                        class="w-full border-none"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td>Lost Time By G/Crane</td>
                    <td>
                      <input
                        v-model="form.vessel_info.lost_time_by_g_crane"
                        type="text"
                        placeholder="Lost Time By G/Crane"
                        class="w-full border-none"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td>Gross Gang Hours</td>
                    <td>
                      <input
                        v-model="form.vessel_info.gross_gang_hours"
                        type="text"
                        placeholder="Gross Gang Hours"
                        class="w-full border-none"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td>Net Gang Hours</td>
                    <td>
                      <input
                        v-model="form.vessel_info.net_gang_hours"
                        type="text"
                        placeholder="Net Gang Hours"
                        class="w-full border-none"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="w-full">
              <strong>4) Handing Moves:</strong>
              <table class="td_first_child_gray w-full">
                <tbody>
                  <tr>
                    <td class="w-50">Hatch Cover Handing</td>
                    <td>
                      <input
                        v-model="form.vessel_info.hatch_cover_handing"
                        type="text"
                        placeholder="Hatch Cover Handing"
                        class="w-full border-none"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td>Gear Box Handing</td>
                    <td>
                      <input
                        v-model="form.vessel_info.gear_box_handing"
                        type="text"
                        placeholder="Gear Box Handing"
                        class="w-full border-none"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td>Total Container Handing Movees</td>
                    <td>
                      <input
                        v-model="form.vessel_info.total_container_handing_moves"
                        type="text"
                        placeholder="Total Container Handing Movees"
                        class="w-full border-none"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>

              <strong>5) Productivity:</strong>
              <table class="td_first_child_gray w-full">
                <thead>
                  <tr class="light-gray">
                    <th></th>
                    <th>Gross</th>
                    <th>Net</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Terminal</td>
                    <td>
                      <input
                        v-model="form.vessel_info.terminal_gross_productivity"
                        type="text"
                        placeholder="Terminal Gross"
                        class="w-full border-none"
                      />
                    </td>
                    <td>
                      <input
                        v-model="form.vessel_info.terminal_net_productivity"
                        type="text"
                        placeholder="Terminal Net"
                        class="w-full border-none"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td>Per G/C</td>
                    <td>
                      <input
                        v-model="form.vessel_info.gc_gross_productivity"
                        type="text"
                        placeholder="Per G/C Gross"
                        class="w-full border-none"
                      />
                    </td>
                    <td>
                      <input
                        v-model="form.vessel_info.gc_net_productivity"
                        type="text"
                        placeholder="Per G/C Net"
                        class="w-full border-none"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- tab close -->
    </div>

    <div
      v-on:click="toggleTabs(4)"
      v-bind:class="{ hidden: openTab !== 4, block: openTab === 4 }"
    >
      <div>
        <button
          type="button"
          @click="downloadSampleTdrFile"
          @mouseenter="isTooltipShowing = true"
          @mouseleave="isTooltipShowing = false"
          title="All red coloured columns are mandatory"
          class="focus:outline-none float-right mb-2 rounded-lg border border-transparent bg-purple-600 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-purple-700 focus:shadow-outline-purple active:bg-purple-600"
        >
          Download Sample File
        </button>
      </div>
      <div class="mt-2">
        <DropZone></DropZone>
      </div>
    </div>

    <div
      v-on:click="toggleTabs(5)"
      v-bind:class="{ hidden: openTab !== 5, block: openTab === 5 }"
    >
        <h4 class="text-2xl mb-2">Remarks</h4>
        <textarea
                v-model="form.remark"
                class="w-full"
              ></textarea>
    </div>
  </div>
  <button
    v-if="openTab == 5"
    type="submit"
    :disabled="isLoading"
    class="fon2t-medium mt- focus:outline-none float-right mt-4 flex items-center justify-between rounded-lg border border-transparent bg-purple-600 px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 hover:bg-purple-700 focus:shadow-outline-purple active:bg-purple-600"
  >
    <span v-if="page == 'create'">Create TDR</span>
    <span v-else>Update TDR</span>
  </button>
  <button
    type="button"
    v-else
    v-on:click="toggleTabs(openTab + 1, 'next')"
    :disabled="isLoading"
    class="fon2t-medium mt- focus:outline-none float-right mt-4 flex items-center justify-between rounded-lg border border-transparent bg-purple-600 px-4 py-2 text-sm uppercase leading-5 text-white transition-colors duration-150 hover:bg-purple-700 focus:shadow-outline-purple active:bg-purple-600"
  >
    Next
    <svg
      xmlns="http://www.w3.org/2000/svg"
      class="h-5 w-5"
      viewBox="0 0 20 20"
      fill="currentColor"
    >
      <path
        fill-rule="evenodd"
        d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"
        clip-rule="evenodd"
      />
      <path
        fill-rule="evenodd"
        d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"
        clip-rule="evenodd"
      />
    </svg>
  </button>
  <button
    type="button"
    v-if="openTab != 1"
    v-on:click="toggleTabs(openTab - 1, 'back')"
    :disabled="isLoading"
    class="fon2t-medium mt- focus:outline-none mt-4 flex items-center justify-between rounded-lg border border-transparent bg-purple-600 px-4 py-2 text-sm uppercase leading-5 text-white transition-colors duration-150 hover:bg-purple-700 focus:shadow-outline-purple active:bg-purple-600"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      class="h-5 w-5"
      viewBox="0 0 20 20"
      fill="currentColor"
    >
      <path
        fill-rule="evenodd"
        d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z"
        clip-rule="evenodd"
      />
    </svg>
    Back
  </button>
</template>

<style lang="postcss" scoped>
.table,
.table th,
.table td {
  @apply border-collapse border border-gray-400 px-1 text-center text-gray-700;
}

.input-group {
  @apply flex w-full flex-col items-center justify-center md:flex-row md:gap-2;
}

.label-group {
  @apply mt-3 block w-full text-sm;
}

.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}

.label-item-input {
  @apply focus:outline-none mt-1 block w-full rounded text-sm focus:border-purple-400 focus:shadow-outline-purple disabled:cursor-not-allowed disabled:bg-gray-200 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray dark:disabled:bg-gray-900;
}

.form-input {
  @apply focus:outline-none mt-1 block rounded text-sm focus:border-purple-400 focus:shadow-outline-purple dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray;
}

/* all input field height set */
.form-input {
  height: 2.05rem;
}

.vs__dropdown-menu {
  min-width: 250px;
}

.v-select.vs--single.vs--searchable.mt-1.placeholder-gray-600.w-full {
  min-width: 250px;
  margin-top: 4px;
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
<style>
.light-gray {
  background: rgb(205, 205, 205);
}

.page_break {
  page-break-before: always;
}

.w-full {
  width: 100% !important;
}

.w-45 {
  width: 45% !important;
}

.w-50 {
  width: 50% !important;
}

.w-75 {
  width: 75% !important;
}

table {
  border-collapse: collapse;
}

table,
tr,
td,
th {
  border: 1px solid #000;
}

#page_null th {
  text-align: center;
}

#page_null thead tr:first-child {
  font-size: 24px;
}

#page_null thead tr:nth-child(2) {
  font-size: 18px;
}

#page_null td,
#page_null th {
  text-align: center;
}

#page_null td {
  font-weight: 400;
}

.main-list {
  font-size: 17px;
  text-decoration: underline;
}

.sub-list {
  margin-left: 20px;
  font-size: 14px;
}

.td_first_child_gray tr td:first-child {
  background: rgb(205, 205, 205);
}

.d-block {
  display: block !important;
}

.d-inline {
  display: inline !important;
}

.float-left {
  float: left !important;
}

.float-right {
  float: right !important;
}

.overflow-hidden {
  overflow: hidden !important;
}

.diag {
  background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' preserveAspectRatio='none' viewBox='0 0 100 100'><line x1='0' y1='0' x2='100' y2='100' stroke='black' vector-effect='non-scaling-stroke'/></svg>");
  background-repeat: no-repeat;
  background-position: center center;
  background-size: 100% 100%, auto;
}
</style>
