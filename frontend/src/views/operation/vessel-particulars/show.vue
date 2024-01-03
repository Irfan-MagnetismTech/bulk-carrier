<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Vessel Particular Details</h2>
    <default-button :title="'Vessel Particular List'" :to="{ name: 'ops.vessel-particulars.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <div class="flex md:gap-4">
            <div class="w-full">
                <table class="w-full">
                    <thead>
                        <tr>
                            <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Basic Info</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="w-40">Business Unit</th>
                            <td><span :class="vesselParticular?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ vesselParticular?.business_unit }}</span></td>
                        </tr>
                        <tr>
                            <th class="w-40">Vessel</th>
                            <td>{{ vesselParticular?.opsVessel?.name }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Vessel Type</th>
                            <td>{{ vesselParticular?.opsVessel?.vessel_type }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Owner Name</th>
                            <td>{{ vesselParticular?.owner_name }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Previous Name</th>
                            <td>{{ vesselParticular?.previous_name }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Vessel Short Code</th>
                            <td>{{ vesselParticular?.short_code }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Call Sign</th>
                            <td>{{ vesselParticular?.call_sign }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Manager/Operator</th>
                            <td>{{ vesselParticular?.manager }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Classification</th>
                            <td>{{ vesselParticular?.classification }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Previous Flag</th>
                            <td>{{ vesselParticular?.previous_flag }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Flag</th>
                            <td>{{ vesselParticular?.flag }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Delivery Date</th>
                            <td>{{ vesselParticular?.delivery_date ? moment(vesselParticular?.delivery_date).format('DD-MM-YYYY') : null }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">GRT</th>
                            <td>{{ vesselParticular?.grt }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">NRT</th>
                            <td>{{ vesselParticular?.nrt }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">DWT</th>
                            <td>{{ vesselParticular?.dwt }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">IMO Number</th>
                            <td>{{ vesselParticular?.imo }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Class No.</th>
                            <td>{{ vesselParticular?.class_no }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Keel Laying Date</th>
                            <td>{{ vesselParticular?.keel_laying_date ? moment(vesselParticular?.keel_laying_date).format('DD-MM-YYYY') : null }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Launching Date</th>
                            <td>{{ vesselParticular?.launching_date ? moment(vesselParticular?.launching_date).format('DD-MM-YYYY') : null }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">MMSI</th>
                            <td>{{ vesselParticular?.mmsi }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Overall Length</th>
                            <td>{{ vesselParticular?.overall_length }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Overall Width</th>
                            <td>{{ vesselParticular?.overall_width }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Year Built</th>
                            <td>{{ vesselParticular?.year_built }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Breadth</th>
                            <td>{{ vesselParticular?.breadth }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Depth (Moulded)</th>
                            <td>{{ vesselParticular?.depth_moulded }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Maker SSAS</th>
                            <td>{{ vesselParticular?.maker_ssas }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Port of Registry</th>
                            <td>{{ vesselParticular?.port_of_registry }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Engine Type</th>
                            <td>{{ vesselParticular?.engine_type }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">LOA</th>
                            <td>{{ vesselParticular?.loa }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Depth</th>
                            <td>{{ vesselParticular?.depth }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">ECDIS Type</th>
                            <td>{{ vesselParticular?.ecdis_type }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">BHP</th>
                            <td>{{ vesselParticular?.bhp }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Email</th>
                            <td>{{ vesselParticular?.email }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">LBC</th>
                            <td>{{ vesselParticular?.lbc }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Total Cargo Hold</th>
                            <td>{{ vesselParticular?.total_cargo_hold }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Capacity</th>
                            <td>{{ vesselParticular?.capacity }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Tues Capacity</th>
                            <td>{{ vesselParticular?.tues_capacity }}</td>
                        </tr>
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
  
  tfoot td{
    @apply text-center
  }
  
</style>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useVesselParticular from '../../../composables/operations/useVesselParticular';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import moment from 'moment';
const icons = useHeroIcon();

const route = useRoute();
const vesselParticularId = route.params.vesselParticularId;
const { vesselParticular, showVesselParticular, errors } = useVesselParticular();

const { setTitle } = Title();

setTitle('Vessel Particular');

onMounted(() => {
  showVesselParticular(vesselParticularId);
});


</script>