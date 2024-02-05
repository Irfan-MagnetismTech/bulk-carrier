// purchaseRequisitionsUtils.js
import { ref, onMounted, watchPostEffect,toRefs } from 'vue';
import { useRouter } from 'vue-router';

export default function useIndexUtils(props, filterOptions, dynamicRouteName, getMethod) {
  const router = useRouter();

  const currentPage = ref(1);
  const paginatedPage = ref(1);

  const tableScrollWidth = ref(null);
  const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
  let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

  onMounted(() => {
    watchPostEffect(() => {
      if (currentPage.value == props.page && currentPage.value != 1) {
        filterOptions.value.page = 1;
        router.push({ name: dynamicRouteName, query: { page: filterOptions.value.page } });
      } else {
        filterOptions.value.page = props.page;
      }
      currentPage.value = props.page;
      if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
        filterOptions.value.isFilter = true;
      }
      getMethod(filterOptions.value)
        .then(() => {
          paginatedPage.value = filterOptions.value.page;
          const customDataTable = document.getElementById("customDataTable");
          if (customDataTable) {
            tableScrollWidth.value = customDataTable.scrollWidth;
          }
        })
        .catch((error) => {
          console.error("Error fetching PR:", error);
        });
    });
  });
    return {currentPage,paginatedPage,stringifiedFilterOptions,tableScrollWidth,screenWidth}
}
