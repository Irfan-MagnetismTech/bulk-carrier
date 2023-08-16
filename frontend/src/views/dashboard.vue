<script setup>
import { ref, watch } from 'vue';
import Title from "../services/title";
import { onMounted, nextTick } from '@vue/runtime-core';
import { useStore } from "vuex";
import useShiftPerformance from "../composables/revenue/useShiftPerformance";
import useAuth from '../services/auth';
import * as echarts from 'echarts';
import 'echarts-liquidfill';
import useTank from "../composables/configuration/useTank";
import router from "../router";

const store = useStore();
const { setTitle } = Title();
const { shift } = useAuth();
const { shiftPerformance, getLastShiftPerformance } = useShiftPerformance();
setTitle('Dashboard');

const userViewedShiftInfo = store.state.erpConfiguration.hasViewedPreviousShiftPerformance;

const spin = ref(false);

function initializedChart(){
  store.state.erpConfiguration.allTanks.value.forEach((tank, index) => {
    let chartId = `chart${index + 1}`;
    nextTick(() => {
      const chartElement = document.getElementById(chartId);

      if (chartElement) {
        const stockPercentage = tank.last_reading / tank.tank_size;
        const options = {
          series: [{
            type: 'liquidFill',
            waveAnimation: true,
            animation: true,
            radius: '50%',
            color: [tank.fuel_color],
            label: {
              normal: {
                formatter: function (params) {
                  return (params.value * 100).toFixed(2) + '%';
                },
                textStyle: {
                  fontSize: 20,
                  fontWeight: 'bold'
                }
              }
            },
            outline: {
              show: true,
              itemStyle: {
                borderColor: tank.fuel_color,
                shadowColor: tank.fuel_color
              }
            },
            data: [{ value: stockPercentage }],
          }]
        };

        const chart = echarts.init(chartElement);
        chart.setOption(options);

        window.addEventListener('resize', () => {
          chart.resize();
        });
      }
    });
  });
}

watch(() => store.state.erpConfiguration.allTanks.value, () => {
  initializedChart();
  spin.value = false;
});

onMounted(() => {
    store.dispatch('getAllTanks');
    store.dispatch('getMaterialSummary');
    store.dispatch('getAllUnits');
    store.dispatch('getAllVehicleTypes');
    store.dispatch('getAllPaymentMethod');
})

function updateTankInfo() {
  spin.value = true;
  store.dispatch('getAllTanks');
}
</script>
<template>
    <!-- Following code for chart start -->
    
    <div class="grid gap-4 md:grid-cols-3 mt-2">
      

      <template v-for="(tankData,index) in store?.state?.erpConfiguration?.allTanks?.value" :key="index">
        <router-link :to="{ name: 'configuration.warehouse.show', params: { warehouseId: tankData.id } }">
          <div class="p-4 bg-gray-200 rounded-lg shadow-xs dark:bg-gray-800 relative h-[200px]">
            <h4 class="font-semibold text-gray-800 dark:text-gray-300">
              {{ tankData?.tank_name }}
            </h4>
            <canvas :id="'chart'+(index + 1)" class="chart" width="479" height="239" style="display: block; width: 479px; height: 239px;" :class="'chart'+(index + 1)"></canvas>
            <div class="flex justify-center space-x-3 text-sm text-gray-600 dark:text-gray-400 absolute bottom-2">
              <!-- Chart legend -->
              <div class="flex items-center justify-around">
                <span class="inline-block w-3 h-3 mr-1 bg-blue-500 rounded-full"></span>
                <span class="text-xs">Size {{ tankData?.tank_size }}</span>
              </div>
              <div class="flex items-center justify-around">
                <span class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                <span class="text-xs">Stock {{ tankData?.last_reading }}</span>
              </div>
              <div class="flex items-center justify-around">
                <span class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                <span class="text-xs">Dip Stick (mm) {{ tankData?.dip_stick }}</span>
              </div>
            </div>
          </div>
        </router-link>
      </template>

      
    </div>
    <!-- Following code for chart end -->

    <div class="mx-auto">
      <button type="button" @click="updateTankInfo" class="my-5 p-2 w-64 flex items-center justify-center bg-indigo-500 rounded-md shadow-md text-white hover:bg-indigo-600 duration-150 ease-linear">
      <span>Update Tank </span>
      <span class="animate ml-3" :class="spin ? 'animate-spin' : ''">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
      </svg>
      </span>

    </button>
    </div>

  <div class="w-full mt-2 overflow-hidden rounded-lg shadow-xs">
    <div class="w-full overflow-x-auto scroll-container">
      <table class="w-full whitespace-no-wrap">
        <thead>
        <tr id="dashboard_material_summary_head" class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-2 py-2" colspan="2">Material Summary</th>
        </tr>
        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-2 py-2">Material</th>
          <th class="px-2 py-2">Stock</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <template v-for="(materialData,index) in store?.state?.erpConfiguration?.materialSummary?.value">
          <tr class="text-gray-700 dark:text-gray-400" :style="{ backgroundColor: index % 2 === 0 ? '#E5E7EB' : '' }">
            <td class="px-2 py-2 text-xs">
              {{ materialData?.name }}
            </td>
            <td class="px-2 py-2 text-xs">
              {{ materialData?.stock }}
            </td>
          </tr>
        </template>
        </tbody>
      </table>
    </div>
  </div>

</template>
<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm font-semibold;
    }
    .label-item-title {
        @apply text-gray-700 dark:text-gray-300;
    }
    .label-item-input {
        @apply bg-gray-100 block w-full mt-1 p-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

    table, tr, td, th {
        @apply border 
    }
    th, td {
        @apply text-center
    }
    .chart {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    #dashboard_material_summary_head{
      background-color: #389890;
      color: #fff;
    }
    .scroll-container {
      max-height: 300px; /* Set the desired fixed height */
      overflow-y: auto; /* Enable vertical scrollbar */
    }

    /* Customize the scrollbar's appearance */
    .scroll-container::-webkit-scrollbar {
      width: 4px; /* Set the width of the scrollbar for Webkit-based browsers */
    }

    .scroll-container::-webkit-scrollbar-thumb {
      background-color: #969696; /* Set the color of the scrollbar thumb */
      border-radius: 2px;
    }

    .scroll-container::-webkit-scrollbar-thumb:hover {
      background-color: #a4a3a3; /* Set the color of the scrollbar thumb on hover */
    }

    table th,tr,td{
        border: 1px solid grey;
    }

</style>