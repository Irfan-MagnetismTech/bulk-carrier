
<script setup>
import {watch, toRefs} from 'vue';
import Error from "../../Error.vue";
import moment from 'moment';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Editor from '@tinymce/tinymce-vue';
import useVoyageSof from "../../../composables/operation/sof/useVoyageSof";
import {useRoute} from "vue-router";

const route = useRoute();
const voyageId = route.params.voyageId;
const { voyageSof, voyageSofLists, storeVoyageSofList, showVoyageSofList, deleteVoyageSofList, isVoyageSofModalOpen, isLoading, errors } = useVoyageSof();

const props = defineProps({
  data: {
    default: () => [],
  },
  uniqueServicePorts: {
    default: () => [],
  },
  sofTimes: {
    default: () => [],
  },
  sofUsages: {
    default: () => [],
  }
});

const { data, uniqueServicePorts, voyageSofListsProp } = toRefs(props);

function openDgListModal() {
  isVoyageSofModalOpen.value = 1;
  voyageSof.value = {};
}

function updateDgList(voyageSofId) {
  showVoyageSofList(voyageSofId);
}

function closeDgListModal() {
  isVoyageSofModalOpen.value = 0;
}

watch(voyageSofListsProp, (newVal, oldVal) => {
  voyageSofLists.value = newVal;
});
</script>

<template>
    <div class="w-full">
      <h2 class="text-2xl text-center my-3 font-semibold">
        Times
      </h2>
      <table class="w-full border-none" id="borderless">
        <tbody>
          <tr>
            <td class="font-semibold">1) Master ETA Pilot Station</td>
            <td class="flex items-center">
              <label class="label-group">
                    <input type="datetime-local" v-model="props.sofTimes.master_eta_pilot_station" placeholder="Enter Bound" class="label-item-input" />
              </label>
              <span class="pl-2"> HRS </span>
            </td>
            <td class="font-semibold">2) Anchored</td>
            <td class="flex items-center ">
              <label class="label-group">
                    <input type="datetime-local" v-model="props.sofTimes.anchored" placeholder="Enter Bound" class="label-item-input" />
              </label>
              <span class="pl-2"> HRS </span>
            </td>
          </tr>
          <tr>
            <td class="font-semibold">3) Anchored aweigh</td>
            <td class="flex items-center">
              <label class="label-group">
                    <input type="datetime-local" v-model="props.sofTimes.anchored_aweigh" placeholder="Enter Bound" class="label-item-input" />
              </label>
              <span class="pl-2"> HRS </span>
            </td>
            <td class="font-semibold">4) Pilot on Board</td>
            <td class="flex items-center">
              <label class="label-group">
                    <input type="datetime-local" v-model="props.sofTimes.pilot_on_board" placeholder="Enter Bound" class="label-item-input" />
              </label>
              <span class="pl-2"> HRS </span>
            </td>
          </tr>
          <tr>
            <td class="font-semibold">5) First Line Ashore</td>
            <td class="flex items-center">
              <label class="label-group">
                    <input type="datetime-local" v-model="props.sofTimes.first_line_ashore" placeholder="Enter Bound" class="label-item-input" />
              </label>
              <span class="pl-2"> HRS </span>
            </td>
            <td class="font-semibold">6) Secured @ Berth</td>
            <td class="flex items-center">
              <label class="label-group">
                    <input type="datetime-local" v-model="props.sofTimes.secured_berth" placeholder="Enter Bound" class="label-item-input" />
              </label>
              <span class="pl-2"> HRS </span>
            </td>
          </tr>
          <tr>
            <td class="font-semibold">7) Commenced Cargo</td>
            <td class="flex items-center">
              <label class="label-group">
                    <input type="datetime-local" v-model="props.sofTimes.commenced_cargo" placeholder="Enter Bound" class="label-item-input" />
              </label>
              <span class="pl-2"> HRS </span>
            </td>
            <td class="font-semibold">8) Completed Cargo</td>
            <td class="flex items-center">
              <label class="label-group">
                    <input type="datetime-local" v-model="props.sofTimes.completed_cargo" placeholder="Enter Bound" class="label-item-input" />
              </label>
              <span class="pl-2"> HRS </span>
            </td>
          </tr>
          <tr>
            <td class="font-semibold">9) Pilot on Board</td>
            <td class="flex items-center">
              <label class="label-group">
                    <input type="datetime-local" v-model="props.sofTimes.pilot_on_board_departure" placeholder="Enter Bound" class="label-item-input" />
              </label>
              <span class="pl-2"> HRS </span>
            </td>
            <td class="font-semibold">10) Unberth / Sailed Last Line</td>
            <td class="flex items-center">
              <label class="label-group">
                    <input type="datetime-local" v-model="props.sofTimes.unberth" placeholder="Enter Bound" class="label-item-input" />
              </label>
              <span class="pl-2"> HRS </span>
            </td>
          </tr>
          <tr>
            <!-- <td class="font-semibold">11) Arrival: </td>
            <td class="flex items-center">
              <label class="label-group">
                    <input type="datetime-local" v-model="props.sofTimes.arrival" placeholder="Enter Bound" class="label-item-input" />
              </label>
              <span class="pl-2"> HRS </span>
            </td> -->
            <td class="font-semibold">11) ETA Next Port: </td>
            <td class="flex items-center">
              <label class="label-group">
                    <input type="datetime-local" v-model="props.sofTimes.eta_next_port" placeholder="Enter Bound" class="label-item-input" />
              </label>
              <span class="pl-2"> HRS </span>
            </td>
          </tr>
        </tbody>
      </table>

      <h2 class="text-2xl text-center my-3 font-semibold">
        Usages 
      </h2>
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
                v-model="props.sofUsages.arrival_draft_fwd"
                type="text"
                placeholder="Fwd Input"
                class="w-full border-none"
              />
            </td>
            <td>Fwd</td>
            <td colspan="3">
              <input
                v-model="props.sofUsages.departure_draft_fwd"
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
                v-model="props.sofUsages.arrival_draft_aft"
                type="text"
                placeholder="Arrival Aft Input"
                class="w-full border-none"
              />
            </td>
            <td>Aft</td>
            <td colspan="3">
              <input
                v-model="props.sofUsages.departure_draft_aft"
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
                v-model="props.sofUsages.arrival_ballast"
                type="text"
                placeholder="Arrival Ballast(M/T) Input"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.departure_ballast"
                type="text"
                placeholder="Departure Ballast(M/T) Input"
                class="w-full border-none"
              />
            </td>
          </tr>
          

          <tr>
            <td rowspan="3">R.O.B (M/T)</td>
            <td>FWD</td>
            <td>
              <input
                v-model="props.sofUsages.arrival_rob_fwd"
                type="text"
                placeholder="Arrival FWD Input"
                class="w-full border-none"
              />
            </td>
            <td></td>
            <td>
              <input
                v-model="props.sofUsages.arrival_rob_fwd_2"
                type="text"
                placeholder=""
                class="w-full border-none"
              />
            </td>
            <td>FWD</td>
            <td>
              <input
                v-model="props.sofUsages.departure_rob_fwd"
                type="text"
                placeholder="Departure FWD Input"
                class="w-full border-none"
              />
            </td>
            <td></td>
            <td>
              <input
                v-model="props.sofUsages.departure_rob_fwd_2"
                type="text"
                placeholder="MEAN"
                class="w-full border-none"
              />
            </td>
          </tr>

          <tr>
            <td>FO</td>
            <td>
              <input
                v-model="props.sofUsages.arrival_rob_fo"
                type="text"
                placeholder="Arrival FO"
                class="w-full border-none"
              />
            </td>
            <td>DO</td>
            <td>
              <input
                v-model="props.sofUsages.arrival_rob_do"
                type="text"
                placeholder="Arrival DO"
                class="w-full border-none"
              />
            </td>
            <td>FO</td>
            <td>
              <input
                v-model="props.sofUsages.departure_rob_fo"
                type="text"
                placeholder="Departure FO"
                class="w-full border-none"
              />
            </td>
            <td>DO</td>
            <td>
              <input
                v-model="props.sofUsages.departure_rob_do"
                type="text"
                placeholder="Departure DO"
                class="w-full border-none"
              />
            </td>
          </tr>
          <tr>
            <td>LO</td>
            <td>
              <input
                v-model="props.sofUsages.arrival_rob_lo"
                type="text"
                placeholder="Arrival LO"
                class="w-full border-none"
              />
            </td>
            <td>FW</td>
            <td>
              <input
                v-model="props.sofUsages.arrival_rob_fw"
                type="text"
                placeholder="Arrival FW"
                class="w-full border-none"
              />
            </td>
            <td>LO</td>
            <td>
              <input
                v-model="props.sofUsages.departure_rob_lo"
                type="text"
                placeholder="Departure LO"
                class="w-full border-none"
              />
            </td>
            <td>FW</td>
            <td>
              <input
                v-model="props.sofUsages.departure_rob_fw"
                type="text"
                placeholder="Departure FW"
                class="w-full border-none"
              />
            </td>
          </tr>
          

          <tr>
            <td rowspan="2">Bunker Supply</td>
            <td>FO</td>
            <td>
              <input
                v-model="props.sofUsages.arrival_bunker_fo"
                type="text"
                placeholder="Arrival Bunker FO"
                class="w-full border-none"
              />
            </td>
            <td>DO</td>
            <td>
              <input
                v-model="props.sofUsages.arrival_bunker_do"
                type="text"
                placeholder="Arrival Bunker DO"
                class="w-full border-none"
              />
            </td>
            <td>FO</td>
            <td>
              <input
                v-model="props.sofUsages.departure_bunker_fo"
                type="text"
                placeholder="Departure Bunker FO"
                class="w-full border-none"
              />
            </td>
            <td>D/O</td>
            <td>
              <input
                v-model="props.sofUsages.departure_bunker_do"
                type="text"
                placeholder="Departure Bunker DO"
                class="w-full border-none"
              />
            </td>
          </tr>
          <tr>
            <td colspan="4"></td>
            <td>LSFO</td>
            <td>
              <input
                v-model="props.sofUsages.departure_bunker_lsfo"
                type="text"
                placeholder="Departure Bunker LSFO"
                class="w-full border-none"
              />
            </td>
            <td>LSDO</td>
            <td>
              <input
                v-model="props.sofUsages.departure_bunker_lsdo"
                type="text"
                placeholder="Departure Bunker LSDO"
                class="w-full border-none"
              />
            </td>
          </tr>
          <tr>
            <td colspan="2">WATER SUPPLIED</td>
            <td colspan="7">
              <input
                v-model="props.sofUsages.water_supplied"
                type="text"
                placeholder="Water Supplied"
                class="w-full border-none"
              />
            </td>
          </tr>

          <tr>
            <td>GM</td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.arrival_gm"
                type="text"
                placeholder="GM"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.departure_gm"
                type="text"
                placeholder="GM"
                class="w-full border-none"
              />
            </td>
          </tr>

          <tr>
            <td>NO OF TUG BOAT</td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.arrival_no_of_tug"
                type="text"
                placeholder="TUG BOAT"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.departure_no_of_tug"
                type="text"
                placeholder="TUG BOAT"
                class="w-full border-none"
              />
            </td>
          </tr>

          <tr>
            <td>DISPLACEMENT</td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.arrival_displacement"
                type="text"
                placeholder="DISPLACEMENT"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.departure_displacement"
                type="text"
                placeholder="DISPLACEMENT"
                class="w-full border-none"
              />
            </td>
          </tr>

          <tr>
            <td>DWT</td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.arrival_dwt"
                type="text"
                placeholder="DWT"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.departure_dwt"
                type="text"
                placeholder="DWT"
                class="w-full border-none"
              />
            </td>
          </tr>

          <tr>
            <td>FUEL OIL</td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.arrival_fuel"
                type="text"
                placeholder="FUEL OIL"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.departure_fuel"
                type="text"
                placeholder="FUEL OIL"
                class="w-full border-none"
              />
            </td>
          </tr>

          <tr>
            <td>DIESEL OIL</td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.arrival_diesel"
                type="text"
                placeholder="DIESEL OIL"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.departure_diesel"
                type="text"
                placeholder="DIESEL OIL"
                class="w-full border-none"
              />
            </td>
          </tr>

          <tr>
            <td>FRESH WATER</td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.arrival_fresh_water"
                type="text"
                placeholder="FRESH WATER"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                v-model="props.sofUsages.departure_fresh_water"
                type="text"
                placeholder="FRESH WATER"
                class="w-full border-none"
              />
            </td>
          </tr>
        </tbody>
      </table>
      <h2 class="text-2xl text-center my-3 font-semibold">
        Remarks
      </h2>
      <div class="w-full flex justify-between">
        <div class="w-1/2 flex items-center">
          <span class="pr-2 font-semibold w-4/12 text-sm">DELAYS</span>
          <label class="label-group">
            <input type="text"
            v-model="props.sofUsages.delays"
            placeholder="Enter Delays" class="label-item-input" />
          </label>
        </div>
        <div class="w-1/2 flex items-center">
          <span class="px-2 font-semibold w-4/12 text-sm">SHIPS MAIL</span>
          <label class="label-group">
            <input type="text" 
            v-model="props.sofUsages.ships_mail"
            placeholder="Enter Ships Mail" class="label-item-input" />
          </label>
        </div>
      </div>
      

      <!-- <h2 class="text-2xl text-center my-3 font-semibold">
        Additional Departure Report 
      </h2>
      <table>
        <tbody>
          <tr>
            <td>F/W Supply</td>
            <td colspan="4">
              <input
                
                type="text"
                placeholder="Arrival F/W Supply Input"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                
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
                
                type="text"
                placeholder="Arrival Dead-Weight(M/T) Input"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                
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
                
                type="text"
                placeholder="Arrival Displacement(M/T) Input"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                
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
                
                type="text"
                placeholder="Arrival Stability(GM) Input"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                
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
                
                placeholder="Arrival Tug Boat Input"
                class="w-full border-none"
              />
            </td>
            <td colspan="4">
              <input
                
                type="text"
                placeholder="Departure Tug Boat Input"
                class="w-full border-none"
              />
            </td>
          </tr>
        </tbody>
      </table> -->
    </div>
</template>

<style lang="postcss" scoped>
@tailwind components;

.input-group {
  @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
}
.label-group {
  @apply block w-full text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}

@layer components {
  th {
    @apply px-3 py-2;
  }
  td {
    @apply px-3 py-2 text-xs border-r;
  }
  thead tr {
    @apply text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  tbody {
    @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
  }

  td {
    @apply px-3 py-2 text-xs;
  }
  thead tr {
    @apply text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  tbody {
    @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
  }

  table th,tr,td{
    border: 1px solid gray;
  }
  #modal{
    max-width: 50rem !important;
  }
}



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

#borderless table, #borderless tr, #borderless td, #borderless th {
  border: none !important;
}
</style>