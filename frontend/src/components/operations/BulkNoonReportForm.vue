<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
    <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Report Type </span>
        <select v-model="form.type" class="form-input">
          <option value="" disabled>Select Option</option>
          <option value="Noon Report">Noon Report</option>
          <option value="Arrival Report">Arrival Report</option>
          <option value="Departure Report">Departure Report</option>
        </select>
        <Error v-if="errors?.type" :errors="errors.type" />
    </label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Vessel </span>
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
            <span class="text-gray-700 ">Voyage </span>
            <v-select :options="voyages" placeholder="--Choose an option--" v-model="form.opsVoyage" label="voyage_sequence" class="block form-input">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.opsVoyage"
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
      <span class="text-gray-700 ">Date/Time </span>
      <input type="text" v-model="form.date_time" placeholder="Date/Time" class="form-input" autocomplete="off" />
      <Error v-if="errors?.date_time" :errors="errors.date_time" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">GMT Time </span>
      <input type="text" v-model="form.gmt_time" placeholder="GMT Time" class="form-input" autocomplete="off" />
      <Error v-if="errors?.gmt_time" :errors="errors.gmt_time" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Location </span>
      <input type="text" v-model="form.location" placeholder="Location" class="form-input" autocomplete="off" />
      <Error v-if="errors?.location" :errors="errors.location" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Latitude </span>
      <input type="text" v-model="form.latitude" placeholder="Latitude" class="form-input" autocomplete="off" />
      <Error v-if="errors?.latitude" :errors="errors.latitude" />
    </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Longitude </span>
        <input type="text" v-model="form.longitude" placeholder="Longitude" class="form-input" autocomplete="off" />
        <Error v-if="errors?.longitude" :errors="errors.longitude" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Fuel Figures From </span>
        <input type="text" v-model="form.fuel_figures_from" placeholder="Fuel Figures From" class="form-input" autocomplete="off" />
        <Error v-if="errors?.fuel_figures_from" :errors="errors.fuel_figures_from" />
      </label>
  </div>

  <h4 class="text-md font-semibold my-3">Upcoming Ports</h4>

  <div class="dt-responsive table-responsive">
    <table id="dataTable" class="w-full table table-striped table-bordered">
      <thead>
        <tr>
          <th class="w-64">Last Port</th>
          <th class="w-64">Next Port</th>
          <th>ETA</th>
          <th>Distance Run</th>
          <th>DTG</th>
          <th>Remarks</th>
          <th class="w-16">
            Action
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(port, index) in form.opsBulkNoonReportPorts" :key="index">
          <td>
            <v-select :options="ports" placeholder="Search Port" v-model="form.opsBulkNoonReportPorts[index].lastPort" label="code_name" class="block form-input">
              <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.opsBulkNoonReportPorts[index].lastPort"
                      v-bind="attributes"
                      v-on="events"
                      />
              </template>
            </v-select>
            <input type="hidden" v-model="form.opsBulkNoonReportPorts[index].last_port" />
          </td>
          <td>
            <v-select :options="ports" placeholder="Search Port" v-model="form.opsBulkNoonReportPorts[index].nextPort" label="code_name" class="block form-input">
              <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.opsBulkNoonReportPorts[index].nextPort"
                      v-bind="attributes"
                      v-on="events"
                      />
              </template>
            </v-select>
            <input type="hidden" v-model="form.opsBulkNoonReportPorts[index].next_port" />
          </td>
          <td>
            <input type="datetime-local" class="form-input" v-model="form.opsBulkNoonReportPorts[index].eta">
          </td>
          <td>
            <input type="text" class="form-input" v-model="form.opsBulkNoonReportPorts[index].distance_run">
          </td>
          <td>
            <input type="text" class="form-input" v-model="form.opsBulkNoonReportPorts[index].dtg">
          </td>
          <td>
            <input type="text" class="form-input" v-model="form.opsBulkNoonReportPorts[index].remarks">
          </td>
          <td>
            <button type="button" v-if="index>0" @click="removePort(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
              </svg>
            </button> 
            <button v-else type="button" @click="addPort()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>


  <h4 class="text-md font-semibold my-3">Distance and Vessel</h4>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full text-sm">
        <span class="text-gray-700 ">CP/Ordered Speed </span>
        <input type="text" v-model="form.opsBulkNoonReportDistance.cp_ordered_speed" placeholder="CP/Ordered Speed" class="form-input" autocomplete="off" />
        <Error v-if="errors?.opsBulkNoonReportDistance.cp_ordered_speed" :errors="errors.opsBulkNoonReportDistance.cp_ordered_speed" />
      </label>
      <label class="block w-full text-sm">
        <span class="text-gray-700 ">Average RPM </span>
        <input type="text" v-model="form.opsBulkNoonReportDistance.average_rpm" placeholder="Average RPM" class="form-input" autocomplete="off" />
        <Error v-if="errors?.opsBulkNoonReportDistance.average_rpm" :errors="errors.opsBulkNoonReportDistance.average_rpm" />
      </label>
      <label class="block w-full text-sm">
        <span class="text-gray-700 ">Reported Speed </span>
        <input type="text" v-model="form.opsBulkNoonReportDistance.reported_speed" placeholder="Reported Speed" class="form-input" autocomplete="off" />
        <Error v-if="errors?.opsBulkNoonReportDistance.reported_speed" :errors="errors.opsBulkNoonReportDistance.reported_speed" />
      </label>
      <label class="block w-full text-sm">
        <span class="text-gray-700 ">Fwd Draft </span>
        <input type="text" v-model="form.opsBulkNoonReportDistance.fwd_draft" placeholder="Fwd Draft" class="form-input" autocomplete="off" />
        <Error v-if="errors?.opsBulkNoonReportDistance.fwd_draft" :errors="errors.opsBulkNoonReportDistance.fwd_draft" />
      </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Observed Distance </span>
      <input type="text" v-model="form.opsBulkNoonReportDistance.observed_distance" placeholder="Observed Distance" class="form-input" autocomplete="off" />
      <Error v-if="errors?.opsBulkNoonReportDistance.observed_distance" :errors="errors.opsBulkNoonReportDistance.observed_distance" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Mild Draft </span>
      <input type="text" v-model="form.opsBulkNoonReportDistance.mild_draft" placeholder="Mild Draft" class="form-input" autocomplete="off" />
      <Error v-if="errors?.opsBulkNoonReportDistance.mild_draft" :errors="errors.opsBulkNoonReportDistance.mild_draft" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Engine Distance </span>
      <input type="text" v-model="form.opsBulkNoonReportDistance.engine_distance" placeholder="Engine Distance" class="form-input" autocomplete="off" />
      <Error v-if="errors?.opsBulkNoonReportDistance.engine_distance" :errors="errors.opsBulkNoonReportDistance.engine_distance" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Aft Draft </span>
      <input type="text" v-model="form.opsBulkNoonReportDistance.aft_draft" placeholder="Aft Draft" class="form-input" autocomplete="off" />
      <Error v-if="errors?.opsBulkNoonReportDistance.aft_draft" :errors="errors.opsBulkNoonReportDistance.aft_draft" />
    </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Main Engine Revs </span>
      <input type="text" v-model="form.opsBulkNoonReportDistance.main_engine_revs" placeholder="Main Engine Revs" class="form-input" autocomplete="off" />
      <Error v-if="errors?.opsBulkNoonReportDistance.main_engine_revs" :errors="errors.opsBulkNoonReportDistance.main_engine_revs" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Heading </span>
      <input type="text" v-model="form.opsBulkNoonReportDistance.heading" placeholder="Heading" class="form-input" autocomplete="off" />
      <Error v-if="errors?.opsBulkNoonReportDistance.heading" :errors="errors.opsBulkNoonReportDistance.heading" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Slip % </span>
      <input type="text" v-model="form.opsBulkNoonReportDistance.slip_percent" placeholder="Slip %" class="form-input" autocomplete="off" />
      <Error v-if="errors?.opsBulkNoonReportDistance.slip_percent" :errors="errors.opsBulkNoonReportDistance.slip_percent" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Steaming Hours </span>
      <input type="text" v-model="form.opsBulkNoonReportDistance.steaming_hours" placeholder="Steaming Hours" class="form-input" autocomplete="off" />
      <Error v-if="errors?.opsBulkNoonReportDistance.steaming_hours" :errors="errors.opsBulkNoonReportDistance.steaming_hours" />
    </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Salinity </span>
      <input type="text" v-model="form.opsBulkNoonReportDistance.salinity" placeholder="Salinity" class="form-input" autocomplete="off" />
      <Error v-if="errors?.opsBulkNoonReportDistance.salinity" :errors="errors.opsBulkNoonReportDistance.salinity" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">S. DWT </span>
      <input type="text" v-model="form.opsBulkNoonReportDistance.s_dwt" placeholder="S. DWT" class="form-input" autocomplete="off" />
      <Error v-if="errors?.opsBulkNoonReportDistance.s_dwt" :errors="errors.opsBulkNoonReportDistance.s_dwt" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Ballast </span>
      <input type="text" v-model="form.opsBulkNoonReportDistance.ballast" placeholder="Ballast" class="form-input" autocomplete="off" />
      <Error v-if="errors?.opsBulkNoonReportDistance.ballast" :errors="errors.opsBulkNoonReportDistance.ballast" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">S. Displacement </span>
      <input type="text" v-model="form.opsBulkNoonReportDistance.s_displacement" placeholder="S. Displacement" class="form-input" autocomplete="off" />
      <Error v-if="errors?.opsBulkNoonReportDistance.s_displacement" :errors="errors.opsBulkNoonReportDistance.s_displacement" />
    </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">FW Last Day Noon ROB </span>
      <input type="text" v-model="form.fw_last_day_noon_rob" placeholder="FW Last Day Noon ROB" class="form-input" autocomplete="off" />
      <Error v-if="errors?.fw_last_day_noon_rob" :errors="errors.fw_last_day_noon_rob" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">FW Production </span>
      <input type="text" v-model="form.fw_production" placeholder="FW Production" class="form-input" autocomplete="off" />
      <Error v-if="errors?.fw_production" :errors="errors.fw_production" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">FW Consumption </span>
      <input type="text" v-model="form.fw_consumption" placeholder="FW Consumption" class="form-input" autocomplete="off" />
      <Error v-if="errors?.fw_consumption" :errors="errors.fw_consumption" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">FW Today Noon ROB </span>
      <input type="text" v-model="form.fw_today_noon_rob" placeholder="FW Today Noon ROB" class="form-input" autocomplete="off" />
      <Error v-if="errors?.fw_today_noon_rob" :errors="errors.fw_today_noon_rob" />
    </label>
  </div>

  <div class="mt-5">
    <h4 class="text-md font-semibold my-3">Cargo Tank Info</h4>

    <table class="w-full whitespace-no-wrap" >
        <thead v-once>
          <tr class="w-full">
            <th class="w-72">Cargo Tanks</th>
            <th>Liq Level</th>
            <th><nobr> Pressure </nobr></th>
            <th><nobr> Vapor Temp </nobr></th>
            <th><nobr> Liq Temp </nobr></th>
            <th><nobr> Quantity (MT) </nobr></th>
            <th class="w-16">
              <button type="button" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(tank, index) in form.opsBulkNoonReportCargoTanks" :key="index">
          
          <td>
            <input type="text" class="form-input" v-model="form.opsBulkNoonReportCargoTanks[index].cargo_tanks">
          </td>
          <td>
            <input type="text" class="form-input" v-model="form.opsBulkNoonReportCargoTanks[index].liq_level">
          </td>
          <td>
            <input type="text" class="form-input" v-model="form.opsBulkNoonReportCargoTanks[index].pressure">
          </td>
          <td>
            <input type="text" class="form-input" v-model="form.opsBulkNoonReportCargoTanks[index].vapor_temp">
          </td>
          <td>
            <input type="text" class="form-input" v-model="form.opsBulkNoonReportCargoTanks[index].liq_temp">
          </td>
          <td>
            <input type="text" class="form-input" v-model="form.opsBulkNoonReportCargoTanks[index].quantity_mt">
          </td>
          <td>
            <button type="button" v-if="index>0" @click="removeTank(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
              </svg>
            </button> 
            <button v-else type="button" @click="addTank()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
            </button>
          </td>
        </tr> 
        </tbody>
      </table>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">Master </span>
      <input type="text" v-model="form.ship_master" placeholder="Master" class="form-input" autocomplete="off" />
      <Error v-if="errors?.ship_master" :errors="errors.ship_master" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">Chief Engineer </span>
      <input type="text" v-model="form.chief_engineer" placeholder="Chief Engineer" class="form-input" autocomplete="off" />
      <Error v-if="errors?.chief_engineer" :errors="errors.chief_engineer" />
    </label>
  </div>

  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
    <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Bunker Consumption</legend>
    <div class="dt-responsive table-responsive">
      <table id="dataTable" class="w-full table table-striped table-bordered">
        <thead>
          <tr>
            <th>Type</th>
            <th>Previous ROB</th>
            <th>Received</th>
            <th>Consumption Used For</th>
            <th>ROB</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(bunker, index) in form.opsBunkers" :key="index">
              <td>
                <span class="show-block">
                  {{ bunker?.name }}
                </span>
              </td>
              <td>
                <input type="text" class="form-input">
              </td>
              <td>
                <input type="text" class="form-input">
              </td>
              <td>
                <input type="text" class="form-input">
              </td>
              <td>
                <input type="text" class="form-input">
              </td>
              <td>
                <a @click="showBunkerConsumptionModal(bunker?.id)" style="display: inline-block;cursor: pointer" class="relative tooltip">
                  <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                  </svg>
                  <span class="tooltiptext">Renew</span>
                </a>
              </td>
          </tr>
        </tbody>
      </table>
    </div>
  </fieldset>

  <div class="mt-5">
    <h4 class="text-md font-semibold my-3">Engine Info</h4>

    <div class="dt-responsive table-responsive">
      <table id="dataTable" class="w-full table table-striped table-bordered">
        <thead>
            <th>Unit</th>
            <th>PCO</th>
            <th>Rack</th>
            <th>Exh. Temp.</th>
        </thead>
        <tbody>

          <tr >

          
          </tr>

          <tr>
            <td colspan="2">ME Tc Exh. In</td>
            <td>
              <input type="text" class="filter_input">
            </td>
            <td>
              <input type="text" class="filter_input">
            </td>
          </tr>
          <tr>
            <td colspan="2">ME TC Exh. Out</td>
            <td>
              <input type="text" class="filter_input">
            </td>
            <td>
              <input type="text" class="filter_input">
            </td>
          </tr>
          <tr>
            <td colspan="2">ME TC LO OUT</td>
            <td>
              <input type="text" class="filter_input">
            </td>
            <td>
              <input type="text" class="filter_input">
            </td>
          </tr>
          <tr>
            <td colspan="2">ME Scv. Temp.</td>
            <td>
              <input type="text" class="filter_input">
            </td>
            <td>
              <input type="text" class="filter_input">
            </td>
          </tr>
          <tr>
            <td colspan="2">ME Scv. Press</td>
            <td>
              <input type="text" class="filter_input">
            </td>
            <td>
              <input type="text" class="filter_input">
            </td>
          </tr>
          <tr>
            <td colspan="2">ME FW Out Temp.</td>
            <td>
              <input type="text" class="filter_input">
            </td>
            <td>
              <input type="text" class="filter_input">
            </td>
          </tr>
          <tr>
            <td colspan="2">ME FW In Temp.</td>
            <td>
              <input type="text" class="filter_input">
            </td>
            <td>
              <input type="text" class="filter_input">
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div v-show="isBunkerConsumptionModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark-disabled:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark-disabled:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeCrewDocumentAddModal">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
              <path
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <!-- Modal body -->
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead v-once>
          <tr style="background-color: #04AA6D;color: white"
              class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark-disabled:border-gray-700 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th colspan="5">Add Consumption Details</th>
          </tr>
          </thead>
        </table>

        <div class="dt-responsive table-responsive">
          <table id="dataTable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Head</th>
                <th>Amount</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <select v-model.trim="form.validity_period" class="form-input" required>
                    <option value="" disabled selected>Select</option>
                    <option v-for="(item, index) in bunkerConsumptionHeads" :key="index">{{ item }}</option>
                  </select>
                </td>
                <td>
                  <input type="number" step="0.001" class="form-input" placeholder="Amount" />
                </td>
                
              </tr>
            </tbody>
          </table>
        </div>

        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark-disabled:bg-gray-800">
          <button type="button" @click="closeCrewDocumentAddModal" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            Cancel
          </button>
          <button type="button"
              class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Submit
          </button>
        </footer>
      </div>
    </form>
  </div>
  
</template>
<script setup>
import { ref, watch, onMounted } from "vue";
import Error from "../Error.vue";
import useVoyage from "../../composables/operations/useVoyage";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import usePort from '../../composables/operations/usePort';
import useBulkNoonReport from "../../composables/operations/useBulkNoonReport";

const editInitiated = ref(false);

const { ports, getPortList } = usePort();
const { voyage, voyages, showVoyage, getVoyageList } = useVoyage();
const { vessel, vessels, getVesselList, showVessel } = useVessel();
const { getBunkerConsumptionHeadList, getEngineTemparatureTypeList, bunkerConsumptionHeads, engineTemparatureTypes } = useBulkNoonReport();

const props = defineProps({
  form: {
      required: false,
      default: {},
  },
  errors: { type: [Object, Array], required: false },
  formType: { type: String, required : false },
  portObject: { type: Object, required: true },
  cargoTankObject: { type: Object, required: true },
  engineObject: { type: Object, required: true },
});

const isBunkerConsumptionModalOpen = ref(0);

function showBunkerConsumptionModal(opsBunkerIndex) {
  isBunkerConsumptionModalOpen.value = 1
}
function addPort() {
  props.form.opsBulkNoonReportPorts.push({...props.portObject});
}

function removePort(index) {
  props.form.opsBulkNoonReportPorts.splice(index, 1);
}

function addTank() {
  props.form.opsBulkNoonReportCargoTanks.push({...props.cargoTankObject});
}

function removeTank(index) {
  props.form.opsBulkNoonReportCargoTanks.splice(index, 1);
}

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
  showVessel(value?.id);
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

watch(() => props.form, (value) => {

if(props?.formType == 'edit' && editInitiated.value != true) {
 
  if(vessels.value.length > 0) {
      editInitiated.value = true
    }
}
}, {deep: true});


onMounted(() => {
getVesselList(props.form.business_unit);
getPortList();
getBunkerConsumptionHeadList();
getEngineTemparatureTypeList();
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
@apply text-gray-700 ;
}
.label-item-input {
@apply block w-full mt-1 text-sm rounded    focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed 
}
.form-input {
@apply block mt-1 text-sm rounded    focus:border-purple-400 focus:outline-none focus:shadow-outline-purple 
}
</style>