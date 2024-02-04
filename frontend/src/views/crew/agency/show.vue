<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useAgency from "../../../composables/crew/useAgency";
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';

const icons = useHeroIcon();

const route = useRoute();
const agencyId = route.params.agencyId;
const { agency, showAgency, errors } = useAgency();

const { setTitle } = Title();

setTitle('Agency Details');

onMounted(() => {
  showAgency(agencyId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Agency Details</h2>
    <default-button :title="'Agency'" :to="{ name: 'crw.agencies.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <table class="w-full">
            <thead>
            <tr>
              <td class="!text-center text-white font-bold uppercase bg-green-600" colspan="2">Basic Info</td>
            </tr>
            </thead>
            <tbody>
              <tr>
                <th class="w-40 text-left">Business Unit</th>
                <td class="text-left"><span :class="agency?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ agency?.business_unit }}</span></td>
              </tr>
              <tr>
                <th class="w-40 text-left">Agency Name</th>
                <td class="text-left">{{ agency?.agency_name }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Legal Name</th>
                <td class="text-left">{{ agency?.legal_name }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Tax Identification</th>
                <td class="text-left">{{ agency?.tax_identification }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Business License No.</th>
                <td class="text-left">{{ agency?.business_license_no }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Company Reg No</th>
                <td class="text-left">{{ agency?.company_reg_no }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Address</th>
                <td class="text-left">{{ agency?.address }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Phone </th>
                <td class="text-left">{{ agency?.phone }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Email  </th>
                <td class="text-left">{{ agency?.email }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Country  </th>
                <td class="text-left">{{ agency?.country }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Website  </th>
                <td class="text-left">{{ agency?.website }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Logo</th>
                <td class="text-left">
                  <template v-if="agency.logo">
                    <img
                        :src="env.BASE_API_URL + '/' + agency.logo"
                        alt="Agency logo"
                        class="h-20 w-auto"
                    />
                  </template>
                  <template v-else>
                    <span>Not Available</span>
                  </template>
                </td>
              </tr>
            </tbody>
          </table>
          <table class="w-full mt-1" id="profileDetailTable">
            <thead>
            <tr>
              <td class="!text-center text-white uppercase bg-green-600 font-bold" colspan="8">Contact Person List</td>
            </tr>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Position</th>
              <th>Contact No</th>
              <th>Email</th>
              <th>Purpose</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(agencyPersonData,index) in agency?.crwAgencyContactPersons" :key="index">
              <td>{{ index + 1 }}</td>
              <td class="text-left">{{ agencyPersonData?.name }}</td>
              <td class="text-left">{{ agencyPersonData?.position }}</td>
              <td class="text-left">{{ agencyPersonData?.contact_no }}</td>
              <td class="text-left">{{ agencyPersonData?.email }}</td>
              <td class="text-left">{{ agencyPersonData?.purpose }}</td>
            </tr>
            </tbody>
            <tfoot v-if="!agency?.crwAgencyContactPersons?.length">
            <tr>
              <td colspan="6">No data found.</td>
            </tr>
            </tfoot>
          </table>
        </div>
      </div>
  </div>
</template>
<style lang="postcss" scoped>
  th, td, tr {
    @apply border-gray-500
  }

  tfoot td{
    @apply text-center
  }

  #profileDetailTable th{
    text-align: center;
  }
  #profileDetailTable thead tr{
    @apply bg-gray-200
  }

</style>