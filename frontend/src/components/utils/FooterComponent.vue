<script setup>
import { onMounted, ref, toRefs, watch } from 'vue';
import FilterWithBusinessUnit from '../searching/FilterWithBusinessUnit.vue'
import { itemsPerPageOptions } from '../../config/setting.js';
import useGlobalFilter from '../../composables/useGlobalFilter';
import useHeroIcon from '../../assets/heroIcon';
import useDebouncedRef from "../../composables/useDebouncedRef";
import LoaderComponent from '../utils/LoaderComponent.vue';


const props = defineProps({
  //get filter option props
  dataList: {
    type: Object,
    required: true,
  },
  isLoading: {
    type: Boolean,
    required: true,
  },
  isTableLoading: {
    type: Boolean,
    required: true,
  },
  Colspan: {
    type: Number,
    required: true,
  },
})


</script>
<template>
            <tfoot v-if="!dataList?.data?.length" class="relative h-[250px]">
                <tr v-if="isLoading">
                </tr>
                <tr v-else-if="isTableLoading">
                    <td :colspan="Colspan">
                      <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                    </td>
                </tr>
                <tr v-else-if="!dataList?.data?.length">
                  <td :colspan="Colspan">No Data found.</td>
                </tr>
            </tfoot>
</template>
<style lang="postcss" scoped>

</style>