<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Voyage Boat Note Details</h2>
    <default-button :title="'Voyage Boat Note Index'" :to="{ name: 'ops.voyage-boat-notes.index' }" :icon="icons.DataBase"></default-button>
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
                          <td><span :class="voyageBoatNote?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ voyageBoatNote?.business_unit }}</span></td>
                      </tr>
                      <tr>
                          <th class="w-40">Voyage No</th>
                          <td>{{ voyageBoatNote?.opsVoyage?.voyage_sequence }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Vessel Name</th>
                          <td>{{ voyageBoatNote?.opsVessel?.name }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Draft</th>
                          <td>{{ voyageBoatNote?.vessel_draft }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Density</th>
                          <td>{{ voyageBoatNote?.water_density }}</td>
                      </tr>
                  </tbody>
              </table>

          </div>
      </div>
      <div class="flex md:gap-4 mt-1 md:mt-2" v-if="voyageBoatNote?.opsVoyageBoatNoteLines?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Sector Information</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th>SL</th>
                <th>Type</th>
                <th>Loading Point</th>
                <th>Unloading Point</th>
                <th>Quantity</th>
                <th>Attachment</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(boatNoteLines, index) in voyageBoatNote?.opsVoyageBoatNoteLines">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  {{ boatNoteLines?.voyage_note_type }}
                </td>
                <td>
                  {{ boatNoteLines?.loading_point }}
                </td>
                <td>
                  {{ boatNoteLines?.unloading_point }}
                </td>
                <td>
                  {{ boatNoteLines?.quantity }}
                </td>
                <td>
                  <span v-if="boatNoteLines?.attachment != 'null'">
                    <a class="text-red-700" target="_blank" :href="env.BASE_API_URL+'/'+boatNoteLines?.attachment">{{ 'Click Here' }}</a>
                  </span>
                  <span v-else>Not Found...</span>
                </td>                
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
import { ref, watchEffect, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useVoyageBoatNote from '../../../composables/operations/useVoyageBoatNote';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';

const icons = useHeroIcon();

const route = useRoute();
const voyageBoatNoteId = route.params.voyageBoatNoteId;
const { voyageBoatNote, showVoyageBoatNote, errors } = useVoyageBoatNote();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Voyage Boat Note Details');

onMounted(() => {
    showVoyageBoatNote(voyageBoatNoteId)
});
</script>