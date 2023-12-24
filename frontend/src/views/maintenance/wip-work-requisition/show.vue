<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useWipWorkRequisition from '../../../composables/maintenance/useWipWorkRequisition';
import moment from 'moment';
import useMaintenanceHelper from '../../../composables/maintenance/useMaintenanceHelper';



const icons = useHeroIcon();

const route = useRoute();
const wipWorkRequisitionId = route.params.wipWorkRequisitionId;
const { wipWorkRequisition, showWipWorkRequisition, errors } = useWipWorkRequisition();

const { setTitle } = Title();

setTitle('Wip Work Requisition Details');

onMounted(() => {
  showWipWorkRequisition(wipWorkRequisitionId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">WIP Work Requisition Details</h2>
    <default-button :title="'WIP Work Requisition List'" :to="{ name: 'mnt.wip-work-requisitions.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">WIP Work Requisition Information</h2>
          <table class="w-full">
            <!-- <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="2">Personal Info</td>
            </tr>
            </thead> -->
            <tbody>
              <tr>
                <th class="w-40">Business Unit</th>
                <td><span :class="wipWorkRequisition?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ wipWorkRequisition?.business_unit }}</span></td>
              </tr>

              <tr>
                <th class="w-40">Requisition Date</th>
                <td>{{ moment(wipWorkRequisition?.requisition_date).format('DD/MM/YYYY') }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Reference No</th>
                <td>{{ wipWorkRequisition?.reference_no }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Maintenance Type</th>
                <td>{{ wipWorkRequisition?.maintenance_type }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Vessel</th>
                <td>{{ wipWorkRequisition?.opsVessel?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Department</th>
                <td>{{ wipWorkRequisition?.mntWorkRequisitionItem?.MntItem?.MntItemGroup?.MntShipDepartment?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Item Group</th>
                <td>{{ wipWorkRequisition?.mntWorkRequisitionItem?.MntItem?.MntItemGroup?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Item</th>
                <td>{{ wipWorkRequisition?.mntWorkRequisitionItem?.MntItem?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Present Runnig Hour</th>
                <td>{{ wipWorkRequisition?.mntWorkRequisitionItem?.present_run_hour }} Hour</td>
              </tr>

              
              <tr>
                <th class="w-40">Act. Start Date</th>
                <td>{{ moment(wipWorkRequisition?.act_start_date).format('DD/MM/YYYY') }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Act. Completion Date</th>
                <td>{{ moment(wipWorkRequisition?.act_completion_date).format('DD/MM/YYYY') }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Assign To</th>
                <td>{{ wipWorkRequisition?.assigned_to }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Responsible Person</th>
                <td>{{ wipWorkRequisition?.responsible_person }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Status</th>
                <td><span :class="wipWorkRequisition?.status == 0 ? 'text-yellow-700 bg-yellow-100' : (wipWorkRequisition?.status == 1 ? 'text-blue-700 bg-blue-100' : 'text-green-700 bg-green-100') " class="px-2 py-1 font-semibold leading-tight rounded-full">{{ wipWorkRequisition?.status == 0 ? 'Pending' : (wipWorkRequisition?.status == 1 ? 'WIP' : 'Done') }}</span></td>
              </tr>

              
              <!-- <tr>
                <th class="w-40">Assigned Jobs</th>
                <td>
                    <table class="w-full">
                    <thead>
                      <th class="text-center"> Job Description </th>
                      <th class="text-center"> Cycle </th>
                      <th class="text-center"> Last Done </th>
                      <th class="text-center"> Prev. Run Hrs. </th>
                      <th class="text-center"> Next Due </th>
                    </thead>
                    <tbody>
                      <tr v-for="(mntWorkRequisitionLine, index) in wipWorkRequisition.mntWorkRequisitionLines" :key="index">
                        <td> {{ mntWorkRequisitionLine?.job_description  }} </td>
                        <td> {{mntWorkRequisitionLine?.cycle }} {{ mntWorkRequisitionLine.cycle_unit }}</td>
                        <td> {{ mntWorkRequisitionLine.last_done ? moment(mntWorkRequisitionLine.last_done).format('DD/MM/YYYY') : null }} </td>
                        <td> {{ mntWorkRequisitionLine?.previous_run_hour }} </td>
                        <td> {{ mntWorkRequisitionLine.cycle_unit == 'Hours' ? (mntWorkRequisitionLine.next_due + " Hour") : (mntWorkRequisitionLine.next_due ? moment(mntWorkRequisitionLine.next_due).format('DD/MM/YYYY') : null) }} </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr> -->


























              <!-- <tr>
                <th class="w-40">Vessel</th>
                <td>{{ job?.opsVessel?.name }}</td>
              </tr>
              
              <tr>
                <th class="w-40">Ship Department</th>
                <td>{{ job?.mntItem?.mntItemGroup?.mntShipDepartment?.name }}</td>
              </tr>
              <tr>
                <th class="w-40">Item Group</th>
                <td>{{ job?.mntItem?.mntItemGroup?.name }}</td>
              </tr>


              
              <tr>
                <th class="w-40">Item Code</th>
                <td>{{ job?.mntItem?.item_code }}</td>
              </tr>
              
              <tr>
                <th class="w-40">Item Name</th>
                <td>{{ job?.mntItem?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Present Runnig Hour</th>
                <td>{{ job?.present_run_hour }} Hours</td>
              </tr>
                            
              <tr>
                <th class="w-40">Job List</th>
                <td>
                  <table class="w-full">
                    <thead>
                      <th class="text-center"> Job Description </th>
                      <th class="text-center"> Cycle </th>
                      <th class="text-center"> Add To Upcoming List </th>
                      <th class="text-center"> Last Done </th>
                      <th class="text-center"> Remarks </th>
                    </thead>
                    <tbody>
                      <tr v-for="(jobLine, index) in job.mntJobLines" :key="index">
                        <td> {{ jobLine?.job_description  }} </td>
                        <td> {{jobLine?.cycle }} {{ jobLine.cycle_unit }}</td>
                        <td> <p class="text-center">{{ jobLine?.min_limit}} {{ jobLine.cycle_unit }}</p> </td>
                        <td> <span class="text-center block">{{ moment(jobLine.last_done).format('DD/MM/YYYY') }}</span> </td>
                        <td> {{ jobLine.remarks }} </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr> -->
            </tbody>
          </table>
          
        </div>
      </div>
  </div>
</template>
<style lang="postcss" scoped>
  th, td, tr {
    @apply text-left border-gray-500
  }

  #profileDetailTable th{
    text-align: center;
  }
  #profileDetailTable thead tr{
    @apply bg-gray-200
  }

</style>