<script setup>
import { onMounted } from 'vue';
import moment from 'moment';
import { useRoute } from 'vue-router';
import useAgencyContract from "../../../composables/crew/useAgencyContract";
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';
import { formatDate } from "../../../utils/helper.js";

const icons = useHeroIcon();

const route = useRoute();
const agencyContractId = route.params.agencyContractId;
const { agencyContract, showAgencyContract, errors } = useAgencyContract();

const { setTitle } = Title();

setTitle('Agency Contract Details');

onMounted(() => {
  showAgencyContract(agencyContractId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Agency Contract Details</h2>
    <default-button :title="'Agency Contract'" :to="{ name: 'crw.agencyContracts.index' }" :icon="icons.DataBase"></default-button>
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
                <td class="text-left"><span :class="agencyContract?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ agencyContract?.business_unit }}</span></td>
              </tr>
              <tr>
                <th class="w-40 text-left">Contract Name</th>
                <td class="text-left">{{ agencyContract?.contract_name }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Agency Name</th>
                <td class="text-left">{{ agencyContract?.crwAgency?.agency_name }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Validity (From-Till)</th>
                <td class="text-left">{{ formatDate(agencyContract?.validity_from) }}
                  - {{ formatDate(agencyContract?.validity_till) }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Billing Cycle (In Days)</th>
                <td class="text-left">{{ agencyContract?.billing_cycle }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Billing Currency</th>
                <td class="text-left">{{ agencyContract?.billing_currency }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Attachment</th>
                <td class="text-left">
                  <a v-html="icons.Attachment" type="button" v-if="typeof agencyContract?.attachment === 'string'"
                     class="text-green-800" target="_blank" :href="env.BASE_API_URL+'/'+agencyContract?.attachment"></a>
                  <a v-else>---</a>
                </td>
              </tr>
              <tr>
                <th class="w-40 text-left">Terms & Condition</th>
                <td class="text-left">{{ agencyContract?.terms_and_conditions }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Services Offered</th>
                <td class="text-left">{{ agencyContract?.service_offered }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Remarks</th>
                <td class="text-left">{{ agencyContract?.remarks }}</td>
              </tr>
            </tbody>
          </table>
          <table class="w-full mt-1">
            <thead>
            <tr>
              <td class="!text-center text-white font-bold uppercase bg-green-600" colspan="2">Bank Info</td>
            </tr>
            </thead>
            <tbody>
            <tr>
              <th class="w-40 text-left">Account Holder</th>
              <td class="text-left">{{ agencyContract?.account_holder_name }}</td>
            </tr>
            <tr>
              <th class="w-40 text-left">Bank Name</th>
              <td class="text-left">{{ agencyContract?.bank_name }}</td>
            </tr>
            <tr>
              <th class="w-40 text-left">Bank Address</th>
              <td class="text-left">{{ agencyContract?.bank_address }}</td>
            </tr>
            <tr>
              <th class="w-40 text-left">Account No</th>
              <td class="text-left">{{ agencyContract?.account_no }}</td>
            </tr>
            <tr>
              <th class="w-40 text-left">Swift Code</th>
              <td class="text-left">{{ agencyContract?.swift_code }}</td>
            </tr>
            </tbody>
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