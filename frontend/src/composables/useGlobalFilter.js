import { ref, customRef } from 'vue'


export default function useGlobalFilter() {

    const showFilter = ref(false);

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
        filterOptions.filter_options.forEach((option, index) => {
          filterOptions.filter_options[index].search_param = "";
          filterOptions.filter_options[index].order_by = null;
        });
    }

    return {
		showFilter, 
        swapFilter,
        setSortingState,
        clearFilter
	};
}