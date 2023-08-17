<script setup>
import { onMounted } from '@vue/runtime-core';
import useVoyage from '../../../composables/operation/useVoyage';
import {computed, ref, watch} from "vue";
import useContractAssign from "../../../composables/commercial/useContractAssign";
import Title from "../../../services/title";
import moment from "moment";
import useTariff from "../../../composables/stevedorage/useTariff";

import useSweetAlert2 from "../../../composables/useSweetAlert2";
import Swal from "sweetalert2";
const sweetAlert = useSweetAlert2();

const { voyages, voyageName, getVoyages, getVoyageName, deleteVoyage } = useVoyage();

const { tariffAssign } = useTariff();

const { form, contractVoyages, getContractVoyages, showVoyageWithNo, tariffAssignCustomerList, getTariffAssignCustomerList, isLoading } = useContractAssign();

const { setTitle } = Title();

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
}


function getVoyageData(form) {
  showVoyageWithNo(form.voyage_id);
  //getTariffAssignCustomerList(form.voyage_id);
}

watch(() => contractVoyages, (value) => {
  let voyage = contractVoyages.value.find(voyage => voyage.id === form.value.voyage_id);
  if(voyage.life_cycle.is_load_assign){
    getTariffAssignCustomerList(form.value.voyage_id);
  } else {
    sweetAlert.showError('Load status not updated yet. Please update load status first.');
  }
}, { deep: true });

function fetchOptions(search, loading) {
  getVoyageName(search);
}

setTitle('Tariff Assign');

function setTariffInAll(e,operationTypeKey,portKey,customerKey) {
  let className = operationTypeKey+'_'+portKey;
  //get all select box with the class name and set the value
  let selectBoxes = document.getElementsByClassName(className);

  for(const [key, value] of Object.entries(tariffAssignCustomerList.value[operationTypeKey]['ports'][portKey]['customers'])) {
    value.assigned_tariff = e.target.value;
  }

  for (let i = 0; i < selectBoxes.length; i++) {
    selectBoxes[i].value = e.target.value;
  }

}

function assignTariff() {

  let data = {
    voyage_id: form.value.voyage_id,
  };

  let voyage = contractVoyages.value.find(voyage => voyage.id === form.value.voyage_id);

  if(voyage.life_cycle.is_tariff_assign){
    Swal.fire({
      title: '',
      text: 'Load status has already been uploaded. Would you like to overwrite with another upload?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Overwrite it!'
    }).then((result) => {
      if (result.isConfirmed) {
        for(const [operationTypeKey, operationTypeValue] of Object.entries(tariffAssignCustomerList.value)) {
          data = {
            ...data,
            [operationTypeKey]: [],
          };
          for(const [portKey, portValue] of Object.entries(operationTypeValue.ports)) {
            data[operationTypeKey].push({
              port: portKey,
              customers: [],
            });
            console.log(data);
            for(const [customerKey, customerValue] of Object.entries(portValue.customers)) {
              let containers = [];
              for(const [sectorKey,sectorValue] of Object.entries(customerValue.sectors)) {
                if(sectorValue['general_load'].is_stevedorage_applicable){
                  containers = [...containers, ...sectorValue['general_load'].sector_containers];
                }
              }
              data[operationTypeKey][data[operationTypeKey].length - 1].customers.push({
                customer: customerKey,
                assigned_tariff_id: customerValue.assigned_tariff,
                containers: containers,
              });
            }
          }
        }
        tariffAssign(data);
      }
    })
  } else {
    for(const [operationTypeKey, operationTypeValue] of Object.entries(tariffAssignCustomerList.value)) {
      data = {
        ...data,
        [operationTypeKey]: [],
      };
      for(const [portKey, portValue] of Object.entries(operationTypeValue.ports)) {
        data[operationTypeKey].push({
          port: portKey,
          customers: [],
        });
        for(const [customerKey, customerValue] of Object.entries(portValue.customers)) {
          let containers = [];
          for(const [sectorKey,sectorValue] of Object.entries(customerValue.sectors)) {
            if(sectorValue['general_load'].is_stevedorage_applicable){
              containers = [...containers, ...sectorValue['general_load'].sector_containers];
            }
          }
          data[operationTypeKey][data[operationTypeKey].length - 1].customers.push({
            customer: customerKey,
            assigned_tariff_id: customerValue.assigned_tariff,
            containers: containers,
          });
        }
      }
    }
    tariffAssign(data);
  }
}

onMounted(() => {
  //getContractVoyages();
});
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Assign Tariff </h2>
  </div>
  <div class="px-2 py-2 mb-5 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <form @submit.prevent="getVoyageData(form)" class="flex items-end justify-between gap-4">
      <!-- Booking Form -->
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voyage No</span>
        <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchOptions" v-model="form.voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
      </label>
      <!-- Submit button -->
      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
    </form>
  </div>
  <template v-if="Object.keys(tariffAssignCustomerList).length">
    <form @submit.prevent="assignTariff">
      <div>
        <div class="w-full overflow-hidden">
          <div class="w-full overflow-x-auto">
            <table class="w-full mb-5 whitespace-no-wrap">
              <thead v-once>
              <tr style="background-color: #465d85;color: white" class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-4 py-3">Voyage</th>
                <th class="px-4 py-3">Vessel</th>
                <th class="px-4 py-3">Service</th>
                <th class="px-4 py-3">ETD</th>
                <th class="px-4 py-3">ETA</th>
              </tr>
              </thead>
              <tbody>
              <tr class="text-center text-gray-700 dark:text-gray-400">
                <td class="px-4 py-3 text-sm">{{ contractVoyages[0]?.voyage }}</td>
                <td class="px-4 py-3 text-sm">{{ contractVoyages[0]?.vessel?.name }}</td>
                <td class="px-4 py-3 text-sm">{{ contractVoyages[0]?.service?.code }}</td>
                <td class="px-4 py-3 text-sm">{{ contractVoyages[0]?.voyage_schedule_first_port?.etd_date ? moment(contractVoyages[0]?.voyage_schedule_first_port?.etd_date).format('DD-MM-YYYY') : null}}</td>
                <td class="px-4 py-3 text-sm">{{ contractVoyages[0]?.voyage_schedule_first_port?.eta_date ? moment(contractVoyages[0]?.voyage_schedule_first_port?.eta_date).format('DD-MM-YYYY') : null}}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div>
        <div class="w-full overflow-hidden">
          <div class="w-full overflow-x-auto">
            <fieldset v-for="(operationType,operationTypeKey,operationTypeIndex) in tariffAssignCustomerList" :key="operationTypeIndex" :class="operationTypeIndex%2===0 ? 'border-green-700' : 'border-red-700'" class="px-2 border-2 rounded dark:border-gray-400 mb-2">
              <legend :class="operationTypeIndex %2 === 0 ? 'text-green-600' : 'text-red-700'" class="px-2 text-gray-700 dark:text-gray-300 font-bold text-lg uppercase">{{ operationTypeKey }}</legend>
              <table class="w-full mb-5 whitespace-no-wrap">
                <thead v-once>
                <tr style="background-color: #465d85;color: white" class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                  <th class="px-4 py-3">Ports</th>
                  <th class="px-4 py-3">Customer Name & CID</th>
                  <th class="px-4 py-3">Sector</th>
                  <th class="px-4 py-3">Contract No.</th>
                  <th class="px-4 py-3">Rate Type</th>
                  <th class="px-4 py-3">Term</th>
                  <th class="px-4 py-3">TTL BOX</th>
                  <th class="px-4 py-3">Stv. Recovery</th>
                  <th class="px-4 py-3">Exception</th>
                  <th class="px-4 py-3">Tariff Assign</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <template v-for="(port, portKey, portIndex) in operationType?.ports" :key="portIndex">
                  <template v-for="(customer, customerKey, customerIndex) in port.customers" :key="customerIndex">
                    <template v-for="(sector, sectorKey, sectorIndex) in customer.sectors" :key="sectorIndex">
                      <tr>
                        <td :rowspan="port.port_row_span + port.port_row_span" v-if="customerIndex == 0 && sectorIndex == 0">
                          <strong>{{ portKey }}</strong>
                        </td>
                        <td :rowspan="customer.customer_row_span + customer.customer_row_span" v-if="sectorIndex == 0" :class="customerIndex % 2 === 0 ? 'bg-gray-200' : ''">
                          <strong>{{ customer.customer.customer_name }}</strong> <br>
                          ({{ customer.customer.customer_code }})
                        </td>
                        <td :rowspan="2" :class="customerIndex % 2 === 0 ? 'bg-gray-200' : ''"> {{ sectorKey }} </td>
                        <td :class="customerIndex % 2 === 0 ? 'bg-gray-200' : ''">{{ sector?.general_load?.contract_no ?? '---' }} </td>
                        <td :class="customerIndex % 2 === 0 ? 'bg-gray-200' : ''">{{ sector?.general_load?.rate_type ?? '---' }}</td>
                        <td :class="customerIndex % 2 === 0 ? 'bg-gray-200' : ''">{{ sector?.general_load?.term ?? '---' }}</td>
                        <td :class="customerIndex % 2 === 0 ? 'bg-gray-200' : ''">{{ sector?.general_load?.ttl_container ?? '---' }}</td>
                        <td :class="customerIndex % 2 === 0 ? 'bg-gray-200' : ''">
                          <template v-if="Object.keys(sector?.general_load).length">
                            <template v-if="sector?.general_load?.is_stevedorage_applicable">YES</template>
                            <template v-else>NO</template>
                          </template>
                          <template v-else>---</template>
                        </td>
                        <td class="px-4 py-3 text-sm" :rowspan="customer.customer_row_span + customer.customer_row_span" v-if="sectorIndex == 0" :class="customerIndex % 2 === 0 ? 'bg-gray-200' : ''">
                          <select required v-model="tariffAssignCustomerList[operationTypeKey]['ports'][portKey]['customers'][customerKey].assigned_tariff" :class="operationTypeKey+'_'+portKey" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                            <option value="" selected disabled>Select Option</option>
                            <option :value="activeTariff?.id" v-for="(activeTariff,index) in port?.active_tariffs">{{ activeTariff?.name }}</option>
                          </select>
                        </td>
                        <td :rowspan="port.port_row_span + port.port_row_span" v-if="customerIndex == 0 && sectorIndex == 0" class="px-4 py-3 text-sm">
                          <select @change="setTariffInAll($event,operationTypeKey,portKey,customerKey)" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                            <option value="" selected disabled>Select Option</option>
                            <option :value="activeTariff?.id" v-for="(activeTariff,index) in port?.active_tariffs">{{ activeTariff?.name }}</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td :class="customerIndex % 2 === 0 ? 'bg-gray-200' : ''">{{ sector?.force_load?.contract_no ?? '---' }} </td>
                        <td :class="customerIndex % 2 === 0 ? 'bg-gray-200' : ''">{{ sector?.force_load?.rate_type ?? '---' }}</td>
                        <td :class="customerIndex % 2 === 0 ? 'bg-gray-200' : ''">{{ sector?.force_load?.term ?? '---' }}</td>
                        <td :class="customerIndex % 2 === 0 ? 'bg-gray-200' : ''">{{ sector?.force_load?.ttl_container ?? '---' }}</td>
                        <td :class="customerIndex % 2 === 0 ? 'bg-gray-200' : ''">
                          <template v-if="Object.keys(sector?.force_load).length">
                            <template v-if="sector?.force_load?.is_stevedorage_applicable">YES</template>
                            <template v-else>NO</template>
                          </template>
                          <template v-else>---</template>
                        </td>
                      </tr>
                    </template>
                  </template>
                </template>

                <!-- <template v-for="(operationTypePort,operationTypePortKey,operationTypePortIndex) in operationType?.customers">
                    <tr>
                      <td> Hello </td>
                    </tr>
                </template> -->
                <!-- <template v-for="(operationTypePort,operationTypePortKey,operationTypePortIndex) in operationType?.customers">
                  <template v-for="(portWiseCustomers,portWiseCustomerKey,portWiseCustomerIndex) in operationTypePort">
                    <template v-for="(sector,sectorKey,sectorIndex) in portWiseCustomers?.sectors">
                      <template v-for="(loadingMood,loadingMoodKey,loadingMoodIndex) in sector">
                        <tr class="text-center text-gray-700 dark:text-gray-400">
                          <td :rowspan="Object.keys(operationTypePort).length" v-if="portWiseCustomerIndex === 0 && sectorIndex===0 && loadingMoodIndex===0" class="px-4 py-3 text-sm">{{ operationTypePortKey }}</td>
                          <td v-if="portWiseCustomerIndex === 0 && sectorIndex===0 && loadingMoodIndex===0" class="px-4 py-3 text-sm">
                            <strong>{{ portWiseCustomers?.customer?.customer_code }}</strong>
                            <br>
                            {{ portWiseCustomers?.customer?.customer_name }}
                          </td> -->
                <!--                        <td :rowspan="operationType?.port_row_span" v-if="portWiseCustomerIndex===0 && sectorIndex===0 && loadingMoodIndex===0" class="px-4 py-3 text-sm">{{ operationTypePortKey }}</td>-->
                <!--                        <td :rowspan="Object.keys(portWiseCustomers?.sectors).length * Object.keys(sector).length" v-if="sectorIndex===0 && loadingMoodIndex===0" class="px-4 py-3 text-sm">-->
                <!--                          <strong>{{ portWiseCustomers?.customer?.customer_code }}</strong>-->
                <!--                          <br>-->
                <!--                          {{ portWiseCustomers?.customer?.customer_name }}-->
                <!--                        </td>-->
                <!--                        <td :rowspan="Object.keys(sector).length" v-if="loadingMoodIndex === 0" class="px-4 py-3 text-sm"><nobr>{{ sectorKey }}</nobr></td>-->
                <!--                        <td :rowspan="Object.keys(sector).length" v-if="loadingMoodIndex === 0" class="px-4 py-3 text-sm">{{ loadingMood?.contract_no }}</td>-->
                <!--                        <td :rowspan="Object.keys(sector).length" v-if="loadingMoodIndex === 0" class="px-4 py-3 text-sm">{{ loadingMood?.rate_type }}</td>-->
                <!--                        <td :rowspan="Object.keys(sector).length" v-if="loadingMoodIndex === 0" class="px-4 py-3 text-sm">{{ loadingMood?.term }}</td>-->
                <!--                        <td :rowspan="Object.keys(sector).length" v-if="loadingMoodIndex === 0" class="px-4 py-3 text-sm">{{ loadingMood?.ttl_container }}</td>-->
                <!--                        <td :rowspan="Object.keys(sector).length" v-if="loadingMoodIndex === 0" class="px-4 py-3 text-sm">-->
                <!--                          <template v-if="loadingMood?.is_stevedorage_applicable">YES</template>-->
                <!--                          <template v-else>NO</template>-->
                <!--                        </td>-->
                <!--                        <td :rowspan="Object.keys(operationTypePort).length * Object.keys(portWiseCustomers?.sectors).length * Object.keys(sector).length" v-if="portWiseCustomerIndex===0 && sectorIndex===0 && loadingMoodIndex===0" class="px-4 py-3 text-sm">-->
                <!--                          <select name="" id="" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
                <!--                            <option value="" selected disabled>Select Option</option>-->
                <!--                            <option value="">dsf</option>-->
                <!--                            <option value="">sdfds</option>-->
                <!--                          </select>-->
                <!--                        </td>-->
                <!--                        <td :rowspan="Object.keys(sector).length" v-if="loadingMoodIndex === 0" class="px-4 py-3 text-sm">-->
                <!--                          <select name="" id="" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
                <!--                            <option value="" selected disabled>Select Option</option>-->
                <!--                            <option value="">dsf</option>-->
                <!--                            <option value="">sdfds</option>-->
                <!--                          </select>-->
                <!--                        </td>-->
                <!-- </tr>
              </template>
            </template>
          </template>
        </template> -->
                </tbody>
              </table>
            </fieldset>
          </div>
          <button type="submit" :disabled="isLoading" class="w-full px-4 py-2 mt-4 mb-10 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>

        </div>
      </div>
    </form>
  </template>
</template>

<style lang="postcss" scoped>
@tailwind components;

@layer components {

  thead td{
    text-transform: capitalize;
  }

  tbody td,
  thead td,
  thead th {
    @apply px-1 py-1 text-xs border-r text-center;
    font-size: 10px !important;
  }
}

table th,tr,td{
  border: 1px solid grey;
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