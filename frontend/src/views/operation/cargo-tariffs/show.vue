<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Cargo Tariff Details</h2>
    <default-button :title="'Cargo Tariff List'" :to="{ name: 'ops.configurations.cargo-tariffs.index' }" :icon="icons.DataBase"></default-button>
  </div>
  
  <!-- Cargo Tariff Show -->
  <div class="bg-white rounded-md p-2">
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
                  <th class="w-40">Tariff Name</th>
                  <td>{{ cargoTariff?.tariff_name }}</td>
              </tr>
              <tr>
                  <th class="w-40">Vessel Name</th>
                  <td>{{ cargoTariff?.opsVessel?.name }}</td>
              </tr>
              <tr>
                  <th class="w-40">Loading Point</th>
                  <td>{{ cargoTariff?.loading_point }}</td>
              </tr>
              <tr>
                  <th class="w-40">Unloading Point</th>
                  <td>{{ cargoTariff?.unloading_point }}</td>
              </tr>
              <tr>
                  <th class="w-40">Cargo Type</th>
                  <td>{{ cargoTariff?.opsCargoType?.cargo_type }}</td>
              </tr>
              <tr>
                  <th class="w-40">Currency</th>
                  <td>{{ cargoTariff?.currency }}</td>
              </tr>
              <tr>
                  <th class="w-40">Status</th>
                  <td>{{ cargoTariff?.status }}</td>
              </tr>
            </tbody>
          </table>

      </div>
    </div>

    <div class="mt-1 md:mt-2">
      <table class="w-full mt-2">
            <thead>
                <tr>
                  <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="14"> Rate List </td>
                </tr>
                <tr class="w-full">
                  <th class="">Particulars</th>
                  <th>Unit</th>
                  <th>Jan</th>
                  <th>Feb</th>
                  <th>Mar</th>
                  <th>Apr</th>
                  <th>May</th>
                  <th>Jun</th>
                  <th>Jul</th>
                  <th>Aug</th>
                  <th>Sep</th>
                  <th>Oct</th>
                  <th>Nov</th>
                  <th>Dec</th>
                </tr>
              </thead>
              <tbody>
                  <tr v-for="(item, index) in cargoTariff.opsCargoTariffLines">
                    <td>
                      <span>{{ cargoTariff.opsCargoTariffLines[index].particular }}</span>
                    </td>
                    <td>
                      <span>{{ cargoTariff.opsCargoTariffLines[index].unit }}</span>
                    </td>
                    <td>
                      <span>{{ (cargoTariff.opsCargoTariffLines[index].jan) ? numberFormat(cargoTariff.opsCargoTariffLines[index].jan) : null }}</span>
                    </td>
                    <td>
                      <span>{{ (cargoTariff.opsCargoTariffLines[index].feb) ? numberFormat(cargoTariff.opsCargoTariffLines[index].feb) : null }}</span>
                    </td>
                    <td>
                      <span>{{ (cargoTariff.opsCargoTariffLines[index].mar) ? numberFormat(cargoTariff.opsCargoTariffLines[index].mar) : null }}</span>
                    </td>
                    <td>
                      <span>{{ (cargoTariff.opsCargoTariffLines[index].apr) ? numberFormat(cargoTariff.opsCargoTariffLines[index].apr) : null }}</span>
                    </td>
                    <td>
                      <span>{{ (cargoTariff.opsCargoTariffLines[index].may) ? numberFormat(cargoTariff.opsCargoTariffLines[index].may) : null }}</span>
                    </td>
                    <td>
                      <span>{{ (cargoTariff.opsCargoTariffLines[index].jun) ? numberFormat(cargoTariff.opsCargoTariffLines[index].jun) : null }}</span>
                    </td>
                    <td>
                      <span>{{ (cargoTariff.opsCargoTariffLines[index].jul) ? numberFormat(cargoTariff.opsCargoTariffLines[index].jul) : null }}</span>
                    </td>
                    <td>
                      <span>{{ (cargoTariff.opsCargoTariffLines[index].aug) ? numberFormat(cargoTariff.opsCargoTariffLines[index].aug) : null }}</span>
                    </td>
                    <td>
                      <span>{{ (cargoTariff.opsCargoTariffLines[index].sep) ? numberFormat(cargoTariff.opsCargoTariffLines[index].sep) : null }}</span>
                    </td>
                    <td>
                      <span>{{ (cargoTariff.opsCargoTariffLines[index].oct) ? numberFormat(cargoTariff.opsCargoTariffLines[index].oct) : null }}</span>
                    </td>
                    <td>
                      <span>{{ (cargoTariff.opsCargoTariffLines[index].nov) ? numberFormat(cargoTariff.opsCargoTariffLines[index].nov) : null }}</span>
                    </td>
                    <td>
                      <span>{{ (cargoTariff.opsCargoTariffLines[index].dec) ? numberFormat(cargoTariff.opsCargoTariffLines[index].dec) : null }}</span>
                    </td>
                  </tr>
              </tbody>
          </table>
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

#customDataTable th{
  text-align: center;
}
#customDataTable thead tr{
  @apply bg-gray-200
}

</style>
<script setup>
import { ref, watchEffect, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useCargoTariff from '../../../composables/operations/useCargoTariff';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";

const icons = useHeroIcon();

const route = useRoute();
const cargoTariffId = route.params.cargoTariffId;
const { cargoTariff, showCargoTariff, errors } = useCargoTariff();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Cargo Tariff Details');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;


onMounted(() => {
  watchEffect(() => {
    showCargoTariff(cargoTariffId)
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