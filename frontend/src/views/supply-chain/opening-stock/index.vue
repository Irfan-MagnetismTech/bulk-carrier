<script setup>
import { onMounted, ref, watchEffect, watchPostEffect } from 'vue'
import ActionButton from '../../../components/buttons/ActionButton.vue'
import DefaultButton from '../../../components/buttons/DefaultButton.vue'
import useOpeningStock from '../../../composables/supply-chain/useOpeningStock'
import useHelper from '../../../composables/useHelper.js'
import Title from '../../../services/title'
import Swal from 'sweetalert2'
import Paginate from '../../../components/utils/paginate.vue'
import useHeroIcon from '../../../assets/heroIcon'
import useDebouncedRef from '../../../composables/useDebouncedRef'
import FilterWithBusinessUnit from '../../../components/searching/FilterWithBusinessUnit.vue'
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";

const { getOpeningStocks, openingStocks, deleteOpeningStock, isLoading, isTableLoading } =
    useOpeningStock()
const businessUnit = ref(Store.getters.getCurrentUser.business_unit)

const { numberFormat } = useHelper()
const { setTitle } = Title()

const props = defineProps({
    page: {
        type: Number,
        default: 1,
    },
})

setTitle('Opening Stocks')

const icons = useHeroIcon()

const debouncedValue = useDebouncedRef('', 800)

let showFilter = ref(false)
// let isTableLoader = ref(false);

function swapFilter() {
    showFilter.value = !showFilter.value
}

let filterOptions = ref({
    "business_unit": businessUnit.value,
    "items_per_page": 15,
    "isFilter": false,
    "page": props.page,
    "filter_options": [
        {
            "relation_name": null,
            "field_name": 'date',
            "search_param": '',
            "action": null,
            "order_by": null,
            "date_from": null,
        },
        {
            "relation_name": 'scmWarehouse',
            "field_name": 'name',
            "search_param": '',
            "action": null,
            "order_by": null,
            "date_from": null,
        },
    ],
})

function setSortingState(index, order) {
  filterOptions.value.filter_options.forEach(function (t) {
    t.order_by = null;
  });
  filterOptions.value.filter_options[index].order_by = order;
}

const currentPage = ref(1);
const paginatedPage = ref(1);
const tableScrollWidth = ref(null)
const screenWidth = screen.width > 768 ? screen.width - 260 : screen.width
let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

function clearFilter() {
  filterOptions.value.business_unit = businessUnit.value;
  filterOptions.value.filter_options = filterOptions.value.filter_options.map((option) => ({
     ...option,
    search_param: null,
    order_by: null,
   }));
}

onMounted(() => {
    watchPostEffect(() => {
        if(currentPage.value == props.page && currentPage.value != 1) {
            filterOptions.value.page = 1;
        } else {
            filterOptions.value.page = props.page;
        }
        currentPage.value = props.page;
        if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
        filterOptions.value.isFilter = true;
        }
        getOpeningStocks(filterOptions.value)
            .then(() => {
                paginatedPage.value = filterOptions.value.page;
                const customDataTable =
                    document.getElementById('customDataTable')
                if (customDataTable) {
                    tableScrollWidth.value = customDataTable.scrollWidth
                }
                // isTableLoader.value = true;
            })
            .catch(error => {
                console.error('Error fetching opening-stock category:', error)
            })
    })
    filterOptions.value.filter_options.forEach((option, index) => {
        filterOptions.value.filter_options[index].search_param =
            useDebouncedRef('', 800)
    })
}) // Code for global search end here
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You want to delete this Unit!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
    }).then(result => {
        if (result.isConfirmed) {
            deleteOpeningStock(id)
        }
    })
}
</script>

<template>
    <!-- Heading -->
    <div class="my-3 flex w-full items-center justify-between" v-once>
        <h2 class="text-2xl font-semibold text-gray-700">Opening Stock List</h2>
        <default-button
            :title="'Create Opening Stock'"
            :to="{ name: 'scm.opening-stock.create' }"
            :icon="icons.AddIcon"
        ></default-button>
    </div>

    <div id="customDataTable">
        <div
            class="table-responsive max-w-screen"
            :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }"
        >
            <table class="whitespace-no-wrap w-full">
                <!-- <thead v-once>
          <tr class="w-full">
            <th>#</th>
            <th>Date</th>
            <th>Warehouse</th>
            <th>Business Unit</th>
            <th>Action</th>
          </tr>
          </thead> -->
                <thead>
                    <tr class="w-full">
                        <th class="w-16">
                            <div class="flex w-full items-center justify-between">#<button @click="swapFilter()" type="button" v-html="icons.FilterIcon"></button>
                            </div>
                        </th>
                        <th>
                            <div class="flex justify-center items-center">
                                <span>Date</span>
                                <div class="flex cursor-pointer flex-col">
                                    <div v-html="icons.descIcon" @click="setSortingState(0, 'asc')" :class="{'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300':filterOptions.filter_options[0].order_by !== 'asc', }" class="font-semibold"></div>
                                    <div v-html="icons.ascIcon"  @click="setSortingState(0, 'desc')" :class="{'text-gray-800': filterOptions.filter_options[0].order_by === 'desc','text-gray-300':filterOptions.filter_options[0].order_by !== 'desc', }" class="font-semibold"></div>
                                </div>
                            </div>
                        </th>
                        <th>
                            <div class="flex justify-center items-center">
                                <span><nobr>Warehouse</nobr></span>
                                <div class="flex cursor-pointer flex-col">
                                    <div v-html="icons.descIcon" @click="setSortingState(1, 'asc')" :class="{'text-gray-800':filterOptions.filter_options[1].order_by === 'asc','text-gray-300':filterOptions.filter_options[1].order_by !== 'asc', }" class="font-semibold"></div>
                                    <div v-html="icons.ascIcon"  @click="setSortingState(1, 'desc')" :class="{'text-gray-800':filterOptions.filter_options[1].order_by === 'desc','text-gray-300':filterOptions.filter_options[1].order_by !== 'desc',}" class="font-semibold"></div>
                                </div>
                            </div>
                        </th>
                        <th>
                            <div class="flex justify-center items-center">
                                <span><nobr>Business Unit</nobr></span>
                            </div>
                        </th>
                        <th class="">Action</th>
                    </tr>
                    <tr class="w-full" v-if="showFilter">
                        <th>
                            <select v-model="filterOptions.items_per_page" class="filter_input">
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </th>
                        <th><input v-model="filterOptions.filter_options[0].search_param" type="text" placeholder="" class="filter_input" autocomplete="off"/></th>
                        <th><input v-model="filterOptions.filter_options[1].search_param" type="text" placeholder="" class="filter_input" autocomplete="off"/></th>
                        <th><filter-with-business-unit v-model="filterOptions.business_unit"></filter-with-business-unit></th>
                        <th><button title="Clear Filter" @click="clearFilter()" type="button" v-html="icons.NotFilterIcon"></button></th>
                    </tr>
                </thead>
                <tbody class="relative">
                    <tr v-for="(openingStock, index) in openingStocks?.data ? openingStocks?.data : openingStocks" :key="index">
                        <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
                        <td><nobr>{{ openingStock?.date }}</nobr></td>
                        <td>{{ openingStock?.scmWarehouse?.name }}</td>
                        <td><span :class="openingStock?.business_unit === 'PSML'? 'bg-green-100 text-green-700': 'bg-orange-100 text-orange-700'" class="rounded-full px-2 py-1 font-semibold leading-tight">{{ openingStock?.business_unit }}</span></td>
                        <td>
                            <nobr>
                                <action-button :action="'edit'" :to="{ name: 'scm.opening-stock.edit', params: { openingStockId: openingStock.id }, }"></action-button>
                                <action-button @click="confirmDelete(openingStock.id)" :action="'delete'" ></action-button>
                            </nobr>
                        </td>
                    </tr>
                    <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && openingStocks?.data?.length"></LoaderComponent>
                </tbody>
                <tfoot
                    v-if="!openingStocks?.data?.length" class="bg-white dark:bg-gray-800 relative h-[250px]">
                    <tr v-if="isLoading">
                        <!-- <td colspan="7">Loading...</td> -->
                    </tr>
                    <tr v-else-if="isTableLoading">
                        <td colspan="7">
                        <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                        </td>
                    </tr>
                    <tr v-else-if="!openingStocks?.data?.length">
                        <td colspan="7">No Data found.</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <Paginate
            :data="openingStocks"
            to="scm.opening-stock.index"
            :page="page"
        ></Paginate>
    </div>
</template>
