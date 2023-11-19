import { ref, customRef } from 'vue'
import Store from '../store/index.js';


export default function useGlobalFilter() {

    const showFilter = ref(false);
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
    const currentPage = ref(1);
    const paginatedPage = ref(1);

    function swapFilter() {
        showFilter.value = !showFilter.value;
    }

    function setSortingState(index, order, filterOptions) {
        console.log(filterOptions)

        filterOptions.filter_options.forEach(function (t) {
          t.order_by = null;
        });
        filterOptions.filter_options[index].order_by = order;
    }

    function clearFilter(filterOptions) {
        filterOptions.business_unit = businessUnit.value;
        filterOptions.filter_options = filterOptions.filter_options.map((option) => ({
            ...option,
            search_param: null,
            order_by: null,
        }));
    }

    function setPaginationState(filterOptions, page) {
        if(currentPage.value == page && currentPage.value != 1) {
            filterOptions.page = 1;
          } else {
            filterOptions.page = page;
          }
          currentPage.value = page;
    }

    return {
		showFilter, 
        swapFilter,
        setSortingState,
        clearFilter,
        setPaginationState,
        currentPage,
        paginatedPage
	};
}