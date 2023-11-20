import { ref, customRef } from 'vue'
import Store from '../store/index.js';


export default function useGlobalFilter() {

    const showFilter = ref(false);
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

    function swapFilter() {
        showFilter.value = !showFilter.value;
    }

    function setSortingState(index, order, filterOptions) {
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

    return {
		showFilter, 
        swapFilter,
        setSortingState,
        clearFilter,
	};
}