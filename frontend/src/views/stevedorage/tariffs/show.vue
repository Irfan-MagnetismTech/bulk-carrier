<script setup>
import { onMounted } from '@vue/runtime-core';
import { useRoute } from 'vue-router';
import useTariff from "../../../composables/stevedorage/useTariff";

const route = useRoute();
const tariffId = route.params.tariffId;
const { tariff, showTariff } = useTariff();

onMounted(() => {
  showTariff(tariffId);
});
</script>

<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
        <span>Tariff Details:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ tariffId }}</span>
    </div>
    <!-- Table -->
    <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Field Name</th>
                        <th class="px-4 py-3">Data</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">Port Code</td>
                        <td class="px-4 py-3 text-sm">{{ tariff?.port_code }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="px-4 py-3">Cost Type</td>
                      <td class="px-4 py-3 text-sm">{{ tariff?.cost_type }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="px-4 py-3">Expire Date</td>
                      <td class="px-4 py-3 text-sm">{{ tariff?.expire_date }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="px-4 py-3">Status</td>
                      <td class="px-4 py-3 text-sm" v-if="tariff?.status === 0">Save</td>
                      <td class="px-4 py-3 text-sm" v-if="tariff?.status === 1">Save & Active</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
  <span>Tariff Heads :</span>
  </div>
  <div class="">
    <!--Card 1-->
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom">Charge Name</th>
        <th class="px-4 py-3 align-bottom">Code</th>
        <th class="px-4 py-3 align-bottom">Currency</th>
        <th class="px-4 py-3 align-bottom">Vendor</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(tariffHead, index) in tariff.tariff_heads" :key="tariffHead.id">
        <td class="px-1 py-1" style="width: 25%">
          <input type="text" v-model="tariff.tariff_heads[index].name" placeholder="Charge name" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 25%">
          <input type="text" v-model="tariff.tariff_heads[index].code" placeholder="Code" class="block w-full disabled rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 25%">
          <input type="text" v-model="tariff.tariff_heads[index].currency" placeholder="Currency" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 25%">
          <input type="text" v-model="tariff.tariff_heads[index].vendor" placeholder="Vendor" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
      </tr>
      </tbody>
    </table>
  </div>
  <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
    <span>Tariff Charges :</span>
  </div>
  <div class="mb-8">
    <!--Card 1-->
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 dark:text-gray-300">Loading <span class="text-red-500">*</span></legend>
      <table class="w-full whitespace-no-wrap loading_ts_container_charges" id="table">
        <thead>
        <tr style="background-color: #2c8038" class="text-xs font-semibold tracking-wide text-center uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th colspan="20" style="color: white" class="px-4 py-3 align-bottom">TS CONTAINER CHARGES</th>
        </tr>
        <tr style="background-color: #d0cfcf" class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th>CHARGE CODE</th>
          <th>CURR.</th>
          <th colspan="3">FULL</th>
          <th colspan="3">EMPTY</th>
          <th colspan="3">REFFER</th>
          <th colspan="3">TANK</th>
          <th colspan="3">AKWARD</th>
          <th colspan="3">DG</th>
        </tr>
        </thead>

        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400" v-for="(tariffCharge, index) in tariff.tariff_charges" :key="tariffCharge.id">
          <template v-if="tariff.tariff_charges[index].operation_type == 'Loading' && tariff.tariff_charges[index].load_status == 'TS'">
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].charge_code" placeholder="code" readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].currency" placeholder="Curr." readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fcl_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fcl_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fcl_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].mty_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].mty_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].mty_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].rf_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].rf_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].rf_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fr_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fr_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fr_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].tk_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].tk_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].tk_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].dg_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].dg_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].dg_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
          </template>
        </tr>
        </tbody>
      </table>

      <table class="w-full whitespace-no-wrap mt-2 loading_lc_container_charges" id="table">
        <thead>
        <tr style="background-color: #2c8038" class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th colspan="20" style="color: white" class="px-4 py-3 align-bottom">LC CONTAINER CHARGES</th>
        </tr>
        <tr style="background-color: #d0cfcf;" class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th>CHARGE CODE</th>
          <th>CURR.</th>
          <th colspan="3">FULL</th>
          <th colspan="3">EMPTY</th>
          <th colspan="3">REFFER</th>
          <th colspan="3">TANK</th>
          <th colspan="3">AKWARD</th>
          <th colspan="3">DG</th>
        </tr>
        </thead>

        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400" v-for="(tariffCharge, index) in tariff.tariff_charges" :key="tariffCharge.id">
          <template v-if="tariff.tariff_charges[index].operation_type == 'Loading' && tariff.tariff_charges[index].load_status == 'LC'">
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].charge_code" placeholder="code" readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].currency" placeholder="Curr." readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fcl_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fcl_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fcl_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].mty_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].mty_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].mty_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].rf_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].rf_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].rf_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fr_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fr_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fr_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].tk_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].tk_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].tk_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].dg_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].dg_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].dg_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
          </template>
        </tr>
        </tbody>
      </table>
    </fieldset>

    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 dark:text-gray-300">Discharge <span class="text-red-500">*</span></legend>
      <table class="w-full whitespace-no-wrap discharge_ts_container_charges" id="table">
        <thead>
        <tr style="background-color: #b74646" class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th colspan="20" class="px-4 py-3 align-bottom" style="color: white">TS CONTAINER CHARGES</th>
        </tr>
        <tr style="background-color: #d0cfcf" class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th>CHARGE CODE</th>
          <th>CURR.</th>
          <th colspan="3">FULL</th>
          <th colspan="3">EMPTY</th>
          <th colspan="3">REFFER</th>
          <th colspan="3">TANK</th>
          <th colspan="3">AKWARD</th>
          <th colspan="3">DG</th>
        </tr>
        </thead>

        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400" v-for="(tariffCharge, index) in tariff.tariff_charges" :key="tariffCharge.id">
          <template v-if="tariff.tariff_charges[index].operation_type == 'Discharge' && tariff.tariff_charges[index].load_status == 'TS'">
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].charge_code" placeholder="code" readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].currency" placeholder="Curr." readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fcl_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fcl_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fcl_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].mty_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].mty_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].mty_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].rf_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].rf_40" placeholder="" class="block disabled disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].rf_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fr_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fr_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fr_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].tk_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].tk_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].tk_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].dg_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].dg_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].dg_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
          </template>
        </tr>
        </tbody>
      </table>

      <table class="w-full whitespace-no-wrap mt-2 discharge_lc_container_charges" id="table">
        <thead>
        <tr style="background-color: #b74646" class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th colspan="20" class="px-4 py-3 align-bottom" style="color: white">LC CONTAINER CHARGES</th>
        </tr>
        <tr style="background-color: #d0cfcf" class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th>CHARGE CODE</th>
          <th>CURR.</th>
          <th colspan="3">FULL</th>
          <th colspan="3">EMPTY</th>
          <th colspan="3">REFFER</th>
          <th colspan="3">TANK</th>
          <th colspan="3">AKWARD</th>
          <th colspan="3">DG</th>
        </tr>
        </thead>

        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400" v-for="(tariffCharge, index) in tariff.tariff_charges" :key="tariffCharge.id">
          <template v-if="tariff.tariff_charges[index].operation_type == 'Discharge' && tariff.tariff_charges[index].load_status == 'LC'">
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].charge_code" placeholder="code" readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].currency" placeholder="Curr." readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fcl_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fcl_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fcl_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].mty_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].mty_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].mty_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].rf_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].rf_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].rf_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fr_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fr_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].fr_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].tk_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].tk_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].tk_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].dg_20" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].dg_40" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="tariff.tariff_charges[index].dg_45" placeholder="" class="block disabled w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
          </template>
        </tr>
        </tbody>
      </table>
    </fieldset>
  </div>
</template>

<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}

.disabled {
  opacity: 0.9;
  pointer-events: none;
}

</style>
