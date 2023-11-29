<script setup>
import { onMounted, ref, toRefs } from 'vue';
import FilterWithBusinessUnit from '../searching/FilterWithBusinessUnit.vue'
import { itemsPerPageOptions } from '../../config/setting.js';
import useGlobalFilter from '../../composables/useGlobalFilter';
import useHeroIcon from '../../assets/heroIcon';
import useDebouncedRef from "../../composables/useDebouncedRef";



const props = defineProps({
  //get filter option props
  filterOptions: {
    type: Object,
    required: true,
  }

})
const { filterOptions } = toRefs(props);
const {showFilter,  swapFilter, setSortingState, clearFilter } = useGlobalFilter()
const icons = useHeroIcon();
const itemsPerPage = [
  { label: '15', value: 15 },
  { label: '30', value: 30 },
  { label: '50', value: 50 },
  { label: '100', value: 100 },
];

onMounted(() => {
  filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = useDebouncedRef('', 800);
  });

});


function clear(){
  clearFilter(filterOptions.value);
};

function setSortState(index, order) {
  setSortingState(index, order, filterOptions.value)
}
</script>
<template>
     <thead>
            <tr class="w-full">
              <th class="w-16">
                <div class="w-full flex items-center justify-between">
                  # <button @click="swapFilter()" type="button" v-html="icons.FilterIcon"></button>
                </div>
              </th>
              <th v-for="(option, key) in filterOptions.filter_options" :key="key" :class="option?.class">
                <nobr>
                  <div class="flex justify-center items-center">
                  <span class="mr-2">{{ option.label }}</span>
                  <div class="flex flex-col cursor-pointer" v-if="option.filter_type">
                    <div
                      v-html="icons.descIcon"
                      @click="setSortState(key, 'asc')"
                      :class="{ 'text-gray-800': option.order_by === 'asc', 'text-gray-300': option.order_by !== 'asc' }"
                      class="font-semibold"
                    ></div>
                    <div
                      v-html="icons.ascIcon"
                      @click="setSortState(key, 'desc')"
                      :class="{ 'text-gray-800': option.order_by === 'desc', 'text-gray-300': option.order_by !== 'desc' }"
                      class="font-semibold"
                    ></div>
                  </div>
                </div>
                </nobr>
              </th>
              <th v-if="filterOptions.business_unit"><nobr>Business Unit</nobr></th>
              <th class=""><nobr>Action</nobr></th>
            </tr>
            <tr class="w-full" v-if="showFilter">
              <th>
                <select v-model="filterOptions.items_per_page" class="filter_input">
                  <option v-for="(item, index) in itemsPerPageOptions" :key="index" :value="item.value">
                    {{ item.label }}
                  </option>
                </select>
              </th>
              <th v-for="(option, key) in filterOptions.filter_options" :key="key">
                <!-- <input v-model="option.search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /> -->
                <template v-if="option.filter_type === 'input'">
                  <input v-model="option.search_param" type="text" placeholder="" class="filter_input" autocomplete="off" />
                </template>
                <template v-else-if="option.filter_type === 'date'">
                  <input v-model="option.search_param" type="date" placeholder="" class="filter_input" autocomplete="off" />
                </template>
                <template v-else-if="option.filter_type === 'select'">
                  <select v-model="option.search_param" class="filter_input" autocomplete="off">
                    <option v-for="(selectOption, index) in option.select_options" :key="index" :value="selectOption.value" :selected="selectOption.defaultSelected">
                      {{ selectOption.label }}
                    </option>
                  </select>
                </template>
                <template v-else-if="option.filter_type === 'component'">
                  <component :is="option.component" v-model="option.search_param" v-bind="option.componentProps"/>
                </template>
                <template v-else>
                  <input type="text" readonly placeholder="" :value="option?.input_value" class="filter_input vms-readonly-input text-center" autocomplete="off" />
                </template>
              </th>
              <th v-if="filterOptions.business_unit"><filter-with-business-unit v-model="filterOptions.business_unit"></filter-with-business-unit></th>
              <th>
                <button title="Clear Filter" @click="clear()" type="button" v-html="icons.NotFilterIcon"></button>
              </th>
            </tr>
          </thead>
</template>
<style lang="postcss" scoped>

</style>