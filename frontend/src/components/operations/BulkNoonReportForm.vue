<template>

<!-- General -->
  <ul class="flex flex-wrap -mb-px">
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-on:click="toggleTabs(1)" v-bind:class="{'text-purple-600 bg-white': openTab !== 1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 bg-gray-100': openTab === 1}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>General and Port Information
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-on:click="toggleTabs(2)" v-bind:class="{'text-purple-600 bg-white': openTab !== 2, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 bg-gray-100': openTab === 2}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Distance, Vessel and Cargo Tank
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-on:click="toggleTabs(3)" v-bind:class="{'text-purple-600 bg-white': openTab !== 3, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 bg-gray-100': openTab === 3}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Bunker and Engine Information
        </a>
      </li>
    </ul>

    <div v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">

      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <business-unit-input v-model="form.business_unit" :page="'edit'"></business-unit-input>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Report Type <span class="text-red-500">*</span></span>
            <select v-model="form.type" class="form-input" required :class="{ 'bg-gray-100 text-gray-900': formType === 'edit' }" :disabled="formType=='edit'" >
              <option value="" disabled selected>Select Option</option>
              <option value="Noon Report">Noon Report</option>
              <option value="Arrival Report">Arrival Report</option>
              <option value="Departure Report">Departure Report</option>
            </select>
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700">Master </span>
          <input type="text" v-model="form.ship_master" placeholder="Master" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700">Chief Engineer </span>
          <input type="text" v-model="form.chief_engineer" placeholder="Chief Engineer" class="form-input" autocomplete="off" />
        </label>
      </div>

      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
                <span class="text-gray-700 ">Vessel <span class="text-red-500">*</span></span>
                <v-select :options="vessels" placeholder="--Choose an option--" v-model="form.opsVessel" label="name" class="block form-input" :class="{ 'bg-gray-100': formType === 'edit' }" :disabled="formType=='edit'" >
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
                <span class="text-gray-700 ">Voyage <span class="text-red-500">*</span></span>
                <v-select :options="voyages" placeholder="--Choose an option--" v-model="form.opsVoyage" label="voyage_sequence" class="block form-input" :class="{ 'bg-gray-100': formType === 'edit' }" :disabled="formType=='edit'" >
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
          <input type="datetime-local" v-model="form.date_time" placeholder="Date/Time" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 ">GMT Time </span>
          <input type="datetime-local" v-model="form.gmt_time" placeholder="GMT Time" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 ">Location </span>
          <input type="text" v-model="form.location" placeholder="Location" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 ">Latitude </span>
          <input type="text" v-model="form.latitude" placeholder="Latitude" class="form-input" autocomplete="off" />
        </label>
      </div>

      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Longitude </span>
            <input type="text" v-model="form.longitude" placeholder="Longitude" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Fuel Figures From </span>
            <input type="text" v-model="form.fuel_figures_from" placeholder="Fuel Figures From" class="form-input" autocomplete="off" />
          </label>
      </div>

      <!-- Upcoming Ports -->
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Upcoming Ports</legend>
        <div class="dt-responsive table-responsive" v-for="(port, index) in form.opsBulkNoonReportPorts" :key="index">
          <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full mt-2 text-sm">
                    <span class="text-gray-700 flex">Last Port <span class="text-red-500">*</span>
                      <span v-show="isPortDuplicate" class="text-yellow-600 pl-1" title="Duplicate Material" v-html="icons.ExclamationTriangle"></span>
                    </span>
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
            </label>
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 flex">Next Port <span class="text-red-500">*</span>
                <span v-show="isPortDuplicate" class="text-yellow-600 pl-1" title="Duplicate Material" v-html="icons.ExclamationTriangle"></span>
              </span>
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
            </label>

          </div>

          <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700">ETA </span>
              <input type="datetime-local" class="form-input" v-model.trim="form.opsBulkNoonReportPorts[index].eta">
            </label>
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700">Distance Run </span>
              <input type="text" class="form-input" v-model.trim="form.opsBulkNoonReportPorts[index].distance_run" placeholder="Distance Run ">
            </label>
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700">DTG </span>
              <input type="text" class="form-input" v-model.trim="form.opsBulkNoonReportPorts[index].dtg" placeholder="DTG">
            </label>
          </div>

          <RemarksComponet v-model="form.opsBulkNoonReportPorts[index].remarks" :maxlength="500" :fieldLabel="'Remarks'"></RemarksComponet>

          <div class="hidden flex flex-col justify-center w-full md:flex-row md:gap-2 my-3">
            <button type="button" @click="addPort()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
            </button>
            <button type="button" v-if="index>0" @click="removePort(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
              </svg>
            </button> 
            
          </div>
        </div>
      </fieldset>
    </div>

    <div v-bind:class="{'hidden': openTab !== 2, 'block': openTab === 2}" class="border border-gray-200 rounded-md my-3 p-2">

      <!-- Distance and Vessel -->
      <div>
        <h4 class="text-md font-semibold my-3">Distance and Vessel</h4>

        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full text-sm">
              <span class="text-gray-700 ">CP/Ordered Speed </span>
              <input type="text" v-model="form.opsBulkNoonReportDistance.cp_ordered_speed" placeholder="CP/Ordered Speed" class="form-input" autocomplete="off" />
            </label>
            <label class="block w-full text-sm">
              <span class="text-gray-700 ">Average RPM </span>
              <input type="text" v-model="form.opsBulkNoonReportDistance.average_rpm" placeholder="Average RPM" class="form-input" autocomplete="off" />
            </label>
            <label class="block w-full text-sm">
              <span class="text-gray-700 ">Reported Speed </span>
              <input type="text" v-model="form.opsBulkNoonReportDistance.reported_speed" placeholder="Reported Speed" class="form-input" autocomplete="off" />
            </label>
            <label class="block w-full text-sm">
              <span class="text-gray-700 ">Fwd Draft </span>
              <input type="text" v-model="form.opsBulkNoonReportDistance.fwd_draft" placeholder="Fwd Draft" class="form-input" autocomplete="off" />
            </label>
        </div>

        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Observed Distance </span>
            <input type="text" v-model="form.opsBulkNoonReportDistance.observed_distance" placeholder="Observed Distance" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Mild Draft </span>
            <input type="text" v-model="form.opsBulkNoonReportDistance.mild_draft" placeholder="Mild Draft" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Engine Distance </span>
            <input type="text" v-model="form.opsBulkNoonReportDistance.engine_distance" placeholder="Engine Distance" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Aft Draft </span>
            <input type="text" v-model="form.opsBulkNoonReportDistance.aft_draft" placeholder="Aft Draft" class="form-input" autocomplete="off" />
          </label>
        </div>

        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Main Engine Revs </span>
            <input type="text" v-model="form.opsBulkNoonReportDistance.main_engine_revs" placeholder="Main Engine Revs" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Heading </span>
            <input type="text" v-model="form.opsBulkNoonReportDistance.heading" placeholder="Heading" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Slip % </span>
            <input type="number" step="0.001" v-model="form.opsBulkNoonReportDistance.slip_percent" placeholder="Slip %" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Steaming Hours </span>
            <input type="number" step="0.001" v-model="form.opsBulkNoonReportDistance.steaming_hours" placeholder="Steaming Hours" class="form-input" autocomplete="off" />
          </label>
        </div>

        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Salinity </span>
            <input type="text" v-model="form.opsBulkNoonReportDistance.salinity" placeholder="Salinity" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">S. DWT </span>
            <input type="text" v-model="form.opsBulkNoonReportDistance.s_dwt" placeholder="S. DWT" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Ballast </span>
            <input type="text" v-model="form.opsBulkNoonReportDistance.ballast" placeholder="Ballast" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">S. Displacement </span>
            <input type="text" v-model="form.opsBulkNoonReportDistance.s_displacement" placeholder="S. Displacement" class="form-input" autocomplete="off" />
          </label>
        </div>

        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700">FW Last Day Noon ROB </span>
            <input type="text" v-model="form.fw_last_day_noon_rob" placeholder="FW Last Day Noon ROB" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700">FW Production </span>
            <input type="text" v-model="form.fw_production" placeholder="FW Production" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700">FW Consumption </span>
            <input type="text" v-model="form.fw_consumption" placeholder="FW Consumption" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700">FW Today Noon ROB </span>
            <input type="text" v-model="form.fw_today_noon_rob" placeholder="FW Today Noon ROB" class="form-input" autocomplete="off" />
          </label>
        </div>
      </div>

      <!-- Cargo Tank and Info -->
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
                  Action
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(tank, index) in form.opsBulkNoonReportCargoTanks" :key="index">
              
              <td>
                <span class="show-block">
                  CT {{ index+1 }}
                </span>
              </td>
              <td>
                <input type="number" step="0.001" class="form-input" v-model="form.opsBulkNoonReportCargoTanks[index].liq_level">
              </td>
              <td>
                <input type="number" step="0.001" class="form-input" v-model="form.opsBulkNoonReportCargoTanks[index].pressure">
              </td>
              <td>
                <input type="number" step="0.001" class="form-input" v-model="form.opsBulkNoonReportCargoTanks[index].vapor_temp">
              </td>
              <td>
                <input type="number" step="0.001" class="form-input" v-model="form.opsBulkNoonReportCargoTanks[index].liq_temp">
              </td>
              <td>
                <input type="number" step="0.001" class="form-input" v-model="form.opsBulkNoonReportCargoTanks[index].quantity_mt">
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
    </div>

  
    <div v-bind:class="{'hidden': openTab !== 3, 'block': openTab === 3}" class="border border-gray-200 rounded-md my-3 p-2">

      <!-- Bunker Consumption -->
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Bunker Consumption</legend>
        <div class="dt-responsive table-responsive">
          <table id="dataTable" class="w-full table table-striped table-bordered">
            <thead>
              <tr>
                <th>Type</th>
                <th>Previous ROB</th>
                <th>Received</th>
                <th>Consumption</th>
                <th>ROB</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(bunker, index) in form.opsBulkNoonReportConsumptions" :key="index">
                  <td>
                    <nobr class="show-block">
                      {{ bunker?.name }}
                    </nobr>
                  </td>
                  <td>
                    <input type="text" class="form-input" v-model.trim="form.opsBulkNoonReportConsumptions[index].previous_rob">
                  </td>
                  <td>
                    <input type="text" class="form-input" v-model.trim="form.opsBulkNoonReportConsumptions[index].received">
                  </td>
                  <td>
                    <input type="text" class="form-input" v-model.trim="form.opsBulkNoonReportConsumptions[index].consumption">
                  </td>
                  <td>
                    <input type="text" class="form-input" v-model.trim="form.opsBulkNoonReportConsumptions[index].rob">
                  </td>
                  <td>
                    <a @click="showBunkerConsumptionModal(index)" style="display: inline-block;cursor: pointer" class="relative tooltip">
                      <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                      </svg>
                      <span class="tooltiptext">Consumption Details</span>
                    </a>
                  </td>
              </tr>
            </tbody>
          </table>
        </div>
      </fieldset>

      <!-- Engine Info -->
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Engine Info</legend>

        <div class="dt-responsive table-responsive">
          <table id="dataTable" class="w-full table table-striped table-bordered">
            <thead>
                <th class="w-72">Unit <span class="text-red-500">*</span></th>
                <th>PCO</th>
                <th>Rack</th>
                <th>Exh. Temp.</th>
                <th>Action</th>
            </thead>
            <tbody>

              <tr v-for="(item, index) in form.opsBulkNoonReportEngineInputs" :key="index">
                <td>
                  
                  <div class="flex items-center justify-between">
                    <select v-model.trim="item.type" class="form-input" required>
                        <option value="" disabled selected>Select</option>
                        <option value="engine_unit">Engine Unit</option>
                        <option v-for="(item, index2) in engineTemparatureTypes" :key="index2">{{ item }}</option>
                    </select>
                    <span class="text-red-500 pl-1" v-if="item.type=='engine_unit'">*</span>
                    <input type="text" v-if="item.type=='engine_unit'" class="form-input ml-2" v-model.trim="item.engine_unit" required>
                    <span v-show="isEngineInputDuplicate" class="text-yellow-600 pl-1" title="Duplicate Material" v-html="icons.ExclamationTriangle"></span>

                  </div>
                </td>
                <td>
                  <input type="text" class="form-input" v-model.trim="item.pco" placeholder="PCO">
                </td>
                <td>
                  <input type="text" class="form-input" v-model.trim="item.rack" placeholder="Rack">
                </td>
                <td>
                  <input type="text" class="form-input" v-model.trim="item.exh_temp" placeholder="Exh. Temp">
                </td>
                <td>
                  <button type="button" v-if="index>0" @click="removeEngineType(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                  </button> 
                  <button v-else type="button" @click="addEngineType()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </td>
              </tr>
              
            </tbody>
          </table>
        </div>
      </fieldset>

      <div>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700">Wind Condition </span>
          <input type="text" v-model="form.sea_condition" placeholder="Wind Condition" class="form-input" autocomplete="off" />
        </label>
      </div>
    
      <RemarksComponet v-model="form.remarks" :maxlength="300" :fieldLabel="'Remarks'"></RemarksComponet>

    </div>


  <!-- Consumption Module -->
  <div v-show="isBunkerConsumptionModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark-disabled:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark-disabled:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeBunkerConsumptionModel">
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
          <table id="dataTable" class="w-full table table-striped table-bordered">
            <thead>
              <tr>
                <th>Head</th>
                <th>Amount</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(details, index) in bunkerConsumptionDetails" :key="index">
                <td>
                  <select v-model.trim="details.type" class="form-input">
                    <option value="" disabled selected>Select</option>
                    <option v-for="(item, index2) in bunkerConsumptionHeads" :key="index2">{{ item }}</option>
                  </select>
                </td>
                <td>
                  <input type="number" step="0.001" v-model.trim="details.amount" class="form-input text-right" placeholder="Amount" />
                </td>
                <td>
                  <button type="button" v-if="index>0" @click="removeConsumptionHead(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                  </button> 
                  <button v-else type="button" @click="addConsumptionHead()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark-disabled:bg-gray-800">
          <button type="button" @click="closeBunkerConsumptionModel" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            Cancel
          </button>
          <button type="button" @click="pushBunkerConsumption"
              class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Submit
          </button>
        </footer>
      </div>
    </form>
  </div>

  <button v-if="openTab==3" type="submit" :disabled="isLoading" class="flex float-right items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
      <span v-if="formType=='create'">Create</span>
      <span v-else>Update</span>
    </button>

    <button type="button" v-else v-on:click="toggleTabs(openTab + 1)" :disabled="isLoading" class="flex float-right items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 uppercase focus:outline-none focus:shadow-outline-purple">Next
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
      </svg>
    </button>
    <button type="button" v-if="openTab!=1" v-on:click="toggleTabs(openTab - 1)" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 uppercase focus:outline-none focus:shadow-outline-purple">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
      </svg>
      Back
    </button>
  
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<script setup>
import { ref, watch, onMounted } from "vue";
import Error from "../Error.vue";
import useVoyage from "../../composables/operations/useVoyage";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import usePort from '../../composables/operations/usePort';
import useBulkNoonReport from "../../composables/operations/useBulkNoonReport";
import cloneDeep from 'lodash/cloneDeep';
import RemarksComponet from '../../components/utils/RemarksComponent.vue';
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import useHeroIcon from "../../assets/heroIcon";

const icons = useHeroIcon();
const { ports, searchPorts } = usePort();
const { voyage, voyages, showVoyage, getVoyageList } = useVoyage();
const { vessel, vessels, getVesselList, showVessel } = useVessel();
const { getBunkerConsumptionHeadList, getEngineTemparatureTypeList, bunkerConsumptionHeads, engineTemparatureTypes, isLoading, checkValidation } = useBulkNoonReport();

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
const bunkerConsumptionDetails = ref([{type: ''}]);
const currentConsumptionIndex = ref(null);
const editInitiated = ref(false);
const isPortDuplicate = ref(false);
const isEngineInputDuplicate = ref(false);

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  console.log("Active Tab Object "+ tabNumber);
  if (openTab.value === 1) {
    let tab1RequiredFields = ['business_unit', 'ops_voyage_id', 'ops_vessel_id', {name: 'opsBulkNoonReportPorts', check: ['last_port', 'next_port']}];
    if (!checkValidation(openTab, tabNumber, props, tab1RequiredFields)) {
      return;
    }
  }
  openTab.value = tabNumber;
}

function showBunkerConsumptionModal(opsBunkerIndex) {
  isBunkerConsumptionModalOpen.value = 1
  currentConsumptionIndex.value = opsBunkerIndex
  if(props.form.opsBulkNoonReportConsumptions[opsBunkerIndex]?.opsBulkNoonReportConsumptionHeads && props.form.opsBulkNoonReportConsumptions[opsBunkerIndex]?.opsBulkNoonReportConsumptionHeads?.length > 0) {
    bunkerConsumptionDetails.value = cloneDeep(props.form.opsBulkNoonReportConsumptions[opsBunkerIndex]?.opsBulkNoonReportConsumptionHeads)
  } else {
    bunkerConsumptionDetails.value = [{type: ''}]
  }
}

function closeBunkerConsumptionModel() {
  isBunkerConsumptionModalOpen.value = 0
  bunkerConsumptionDetails.value = [{type: ''}]
}

function pushBunkerConsumption() {
  props.form.opsBulkNoonReportConsumptions[currentConsumptionIndex.value].opsBulkNoonReportConsumptionHeads = bunkerConsumptionDetails.value
  bunkerConsumptionDetails.value = [{type: ''}]
  isBunkerConsumptionModalOpen.value = 0
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

function addConsumptionHead() {
  bunkerConsumptionDetails.value.push({type: ''});
}

function removeConsumptionHead(index) {
  bunkerConsumptionDetails.value.splice(index, 1);
}

function addEngineType() {
  props.form.opsBulkNoonReportEngineInputs.push({...props.engineObject});
}

function removeEngineType(index) {
  props.form.opsBulkNoonReportEngineInputs.splice(index, 1);
}



watch(() => props.form.opsVoyage, (value) => {
  props.form.ops_voyage_id = null;
if(value) {
  props.form.ops_voyage_id = value?.id
}
}, { deep: true })

watch(() => props.form.opsVessel, (value) => {
  props.form.ops_vessel_id = null;
  if(value) {
    props.form.ops_vessel_id = value?.id
    let loadStatus = false;
    showVessel(value?.id, loadStatus);
    getVoyageList(props.form.business_unit, props.form.ops_vessel_id);
  }
}, { deep: true })

watch(() => props.form.opsBulkNoonReportPorts, (value) => {
  var portCodes = [];

  for(const port in value) {
    value[port].last_port = value[port]?.lastPort?.code
    value[port].next_port = value[port]?.nextPort?.code


    const lastPortCode = value[port]?.lastPort?.code;
    const nextPortCode = value[port]?.nextPort?.code;

    portCodes.push(lastPortCode);
    portCodes.push(nextPortCode);

  }

  if (portCodes.length !== new Set(portCodes).size) {
      isPortDuplicate.value = true;
    } else {
      isPortDuplicate.value = false;
    }

}, {deep: true} )

watch(() => props.form.opsBulkNoonReportEngineInputs, (value) => {
  var engineInputs = [];
  var engineUnits = [];

  for(const engineInputIndex in value) {
    engineInputs.push(value[engineInputIndex].type)
    engineUnits.push(value[engineInputIndex].engine_unit)
  }


  let newEngineInputs = engineInputs.filter(function(item) {
      return item !== 'engine_unit'
  })

  let newEngineUnits = engineUnits.filter(function(item) {
      return item !== null
  })


  if ((newEngineInputs.length !== new Set(newEngineInputs).size )|| (newEngineUnits.length !== new Set(newEngineUnits).size)) {
    isEngineInputDuplicate.value = true;
  } else {
    isEngineInputDuplicate.value = false;
  }


}, { deep: true })

watch(() => vessel, (value) => {
if(value?.value) {
  if(props?.formType != 'edit' || (props?.formType == 'edit' && editInitiated.value == true)) {
    props.form.opsBulkNoonReportConsumptions = value?.value?.opsBunkers
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
  getBunkerConsumptionHeadList();
  getEngineTemparatureTypeList();
  searchPorts("", props.form.business_unit);
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