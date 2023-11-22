<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 ">Cargo Tariff Details</h2>
    <default-button :title="'Cargo Tariff List'" :to="{ name: 'ops.configurations.cargo-tariffs.index' }" :icon="icons.DataBase"></default-button>
  </div>
  
  <!-- Cargo Tariff Show -->
  <div class="bg-white rounded-md p-2">
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Tariff Name <span class="text-red-500">*</span></span>
            <span class="show-block">{{ cargoTariff?.tariff_name }}</span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Vessel <span class="text-red-500">*</span></span>
            <span class="show-block">{{ cargoTariff?.opsVessel?.name }}</span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 "> Loading Point <span class="text-red-500">*</span></span>
            <span class="show-block">{{ cargoTariff?.loading_point }}</span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Unloading Point <span class="text-red-500">*</span></span>
            <span class="show-block">{{ cargoTariff?.unloading_point }}</span>
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Cargo Type <span class="text-red-500">*</span></span>
              <span class="show-block">{{ cargoTariff?.opsCargoType?.cargo_type }}</span>
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Currency <span class="text-red-500">*</span></span>
              <span class="show-block">{{ cargoTariff?.currency }}</span>

          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Status <span class="text-red-500">*</span></span>
              <span class="show-block">{{ cargoTariff?.status }}</span>
          </label>
          <label class="block w-full mt-2 text-sm"></label>
    </div>

    <div class="mt-3 md:mt-8">
      <div id="customDataTable">
        <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
          
          <table class="w-full whitespace-no-wrap" >
              <thead v-once class="bg-gray-300">
                <tr class="w-full">
                  <th class="!w-48 block">Particulars</th>
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
                      <span class="show-block">{{ cargoTariff.opsCargoTariffLines[index].particular }}</span>
                    </td>
                    <td>
                      <span class="show-block">{{ cargoTariff.opsCargoTariffLines[index].unit }}</span>
                    </td>
                    <td>
                      <span class="show-block">{{ numberFormat(cargoTariff.opsCargoTariffLines[index].jan) }}</span>
                    </td>
                    <td>
                      <span class="show-block">{{ numberFormat(cargoTariff.opsCargoTariffLines[index].feb) }}</span>
                    </td>
                    <td>
                      <span class="show-block">{{ numberFormat(cargoTariff.opsCargoTariffLines[index].mar) }}</span>
                    </td>
                    <td>
                      <span class="show-block">{{ numberFormat(cargoTariff.opsCargoTariffLines[index].apr) }}</span>
                    </td>
                    <td>
                      <span class="show-block">{{ numberFormat(cargoTariff.opsCargoTariffLines[index].may) }}</span>
                    </td>
                    <td>
                      <span class="show-block">{{ numberFormat(cargoTariff.opsCargoTariffLines[index].jun) }}</span>
                    </td>
                    <td>
                      <span class="show-block">{{ numberFormat(cargoTariff.opsCargoTariffLines[index].jul) }}</span>
                    </td>
                    <td>
                      <span class="show-block">{{ numberFormat(cargoTariff.opsCargoTariffLines[index].aug) }}</span>
                    </td>
                    <td>
                      <span class="show-block">{{ numberFormat(cargoTariff.opsCargoTariffLines[index].sep) }}</span>
                    </td>
                    <td>
                      <span class="show-block">{{ numberFormat(cargoTariff.opsCargoTariffLines[index].oct) }}</span>
                    </td>
                    <td>
                      <span class="show-block">{{ numberFormat(cargoTariff.opsCargoTariffLines[index].nov) }}</span>
                    </td>
                    <td>
                      <span class="show-block">{{ numberFormat(cargoTariff.opsCargoTariffLines[index].dec) }}</span>
                    </td>
                  </tr>
              </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</template>
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