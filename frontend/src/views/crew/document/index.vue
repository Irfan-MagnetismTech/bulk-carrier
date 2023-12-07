<script setup>
import {onMounted, ref, watchEffect, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useCrewDocument from "../../../composables/crew/useCrewDocument";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from "../../../store";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";

const icons = useHeroIcon();
const router = useRouter();
import env from '../../../config/env';

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { crewDocuments, getCrewDocuments, deleteCrewDocument, isLoading, isTableLoading } = useCrewDocument();
const debouncedValue = useDebouncedRef('', 800);
const { setTitle } = Title();
setTitle('Crew Documents');

const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "full_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Crew Name",
      "filter_type": "input"
    },
    {
      "relation_name": 'crwCurrentRank',
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Rank",
      "filter_type": "input"
    },
    {
      "relation_name": 'crwCrewProfile',
      "field_name": "pre_mobile_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Contact",
      "filter_type": "input"
    },
    {
      "relation_name": 'crewDocuments',
      "field_name": "document_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Documents",
    },
  ]
});

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

const currentPage = ref(1);
const paginatedPage = ref(1);
let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this item!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteCrewDocument(id);
    }
  })
}

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'crw.documents.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getCrewDocuments(filterOptions.value)
        .then(() => {
          const customDataTable = document.getElementById("customDataTable");
          paginatedPage.value = filterOptions.value.page;
          if (customDataTable) {
            tableScrollWidth.value = customDataTable.scrollWidth;
          }
        })
        .catch((error) => {
          console.error("Error fetching ranks:", error);
        });
  });
  filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = useDebouncedRef('', 800);
  });
});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Crew Document List</h2>
    <default-button :title="'Create Item'" :to="{ name: 'crw.documents.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
        <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">
          <tr v-for="(crwDocument,index) in crewDocuments?.data" :key="index">
            <td>{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
            <td class="text-left">{{ crwDocument?.full_name }}</td>
            <td class="text-left">{{ crwDocument?.crwCurrentRank?.name }}</td>
            <td>{{ crwDocument?.pre_mobile_no }}</td>
            <td style="text-align: left !important;">
              <span v-for="(document,index) in crwDocument?.crewDocuments" :key="index" class="text-xs mr-2 mb-2 inline-block py-1 px-2.5 leading-none whitespace-nowrap align-baseline font-bold bg-gray-200 text-gray-700 rounded">
                {{ document?.document_name }}
              </span>
            </td>
            <td>
              <span :class="crwDocument?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crwDocument?.business_unit }}</span>
            </td>
            <td>
              <nobr>
                <action-button :action="'edit'" :to="{ name: 'crw.documents.edit', params: { documentId: crwDocument?.id } }"></action-button>
                <action-button @click="confirmDelete(crwDocument?.id)" :action="'delete'"></action-button>
              </nobr>
            </td>
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && crewDocuments?.data?.length"></LoaderComponent>
          </tbody>
        <tfoot v-if="!crewDocuments?.data?.length" class="relative h-[250px]">
        <tr v-if="isLoading">
          <td colspan="7"></td>
        </tr>
        <tr v-else-if="isTableLoading">
          <td colspan="7">
            <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>
          </td>
        </tr>
        <tr v-else-if="!crewDocuments?.data?.length">
          <td colspan="7">No data found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="crewDocuments" to="crw.documents.index" :page="page"></Paginate>
  </div>
</template>