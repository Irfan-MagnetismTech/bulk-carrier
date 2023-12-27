<template>
   <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Charterer Profile Details</h2>
    <default-button :title="'Charterer Profile Index'" :to="{ name: 'ops.charterer-profiles.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="chartererProfile?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ chartererProfile?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Charterer Name</th>
                        <td>{{ chartererProfile?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Company Legal Name</th>
                        <td>{{ chartererProfile?.company_legal_name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Charterer Owner Code</th>
                        <td>{{ chartererProfile?.owner_code }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Contact No</th>
                        <td>{{ chartererProfile?.contact_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Country</th>
                        <td>{{ chartererProfile?.country }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Address</th>
                        <td>{{ chartererProfile?.address }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Billing Address</th>
                        <td>{{ chartererProfile?.billing_address }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Billing Email</th>
                        <td>{{ chartererProfile?.billing_email }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Email</th>
                        <td>{{ chartererProfile?.email }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Website</th>
                        <td>{{ chartererProfile?.website }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2">
        <div class="w-full" v-if="chartererProfile?.opsChartererBankAccounts?.length">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Bank Account Info</td>
                </tr>
            </thead>
            <tbody v-for="(opsChartererBankAccount, index) in  chartererProfile?.opsChartererBankAccounts" :key="opsChartererBankAccount?.id">
                <tr class="bg-gray-200">
                    <th class="w-40">Bank No.</th>
                    <td>{{ (index + 1) < 10 ? '0' + (index + 1) : (index + 1) }}</td>
                </tr>
                <tr>
                    <th class="w-40">Bank Name</th>
                    <td>{{  opsChartererBankAccount?.bank_name }}</td>
                </tr>
                <tr>
                    <th class="w-40">Bank Branch</th>
                    <td>{{ opsChartererBankAccount?.bank_branch_name }}</td>
                </tr>
                <tr>
                    <th class="w-40">Account No</th>
                    <td>{{ opsChartererBankAccount?.account_no }}</td>
                </tr>
                <tr>
                    <th class="w-40">Account Name</th>
                    <td>{{ opsChartererBankAccount?.account_name }}</td>
                </tr>
                <tr>
                    <th class="w-40">Swift Code</th>
                    <td>{{ opsChartererBankAccount?.swift_code }}</td>
                </tr>
                <tr>
                    <th class="w-40">Routing No</th>
                    <td>{{ opsChartererBankAccount?.routing_no }}</td>
                </tr>
                <tr>
                    <th class="w-40">Currency</th>
                    <td>{{ opsChartererBankAccount?.currency }}</td>
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
import useChartererProfile from '../../../composables/operations/useChartererProfile';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";

const icons = useHeroIcon();

const route = useRoute();
const chartererProfileId = route.params.chartererProfileId;
const { chartererProfile, showChartererProfile, errors } = useChartererProfile();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Charterer Profile Details');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;


onMounted(() => {
  watchEffect(() => {
    showChartererProfile(chartererProfileId)
      .then(() => {
        const customDataTable = document.getElementById("customDataTable");

        if (customDataTable) {
          tableScrollWidth.value = customDataTable.scrollWidth;
        }
      })
      .catch((error) => {
        console.error("Error fetching users:", error);
      });
  });
});
</script>