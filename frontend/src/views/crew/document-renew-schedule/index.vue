<script setup>
import {onMounted, ref, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useUser from "../../../composables/administration/useUser";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
const icons = useHeroIcon();
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import useCrewDocument from "../../../composables/crew/useCrewDocument";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { crewDocumentRenewSchedules, getCrewDocumentRenewSchedules, isLoading, isTableLoading } = useCrewDocument();
const { setTitle } = Title();
setTitle('Renew Schedule');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
let showFilter = ref(false);
// let isTableLoader = ref(false);


function swapFilter() {
  showFilter.value = !showFilter.value;
}

let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "document_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Document Name",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "left_days",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Left Days",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "left_days",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Crew Name",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "left_days",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Contact",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "left_days",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Email",
      "filter_type": "input"
    },
  ]
});

function setSortingState(index, order) {
  filterOptions.value.filter_options.forEach(function (t) {
    t.order_by = null;
  });
  filterOptions.value.filter_options[index].order_by = order;
}

const currentPage = ref(1);
const paginatedPage = ref(1);
let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete this user!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteUser(id);
    }
  })
}

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
    getCrewDocumentRenewSchedules(filterOptions.value)
    .then(() => {
      paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
      // isTableLoader.value = true;
    })
    .catch((error) => {
      console.error("Error fetching users:", error);
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
    <h2 class="text-2xl font-semibold text-gray-700">Renew Schedule</h2>
<!--    <default-button :title="'Create User'" :to="{ name: 'administration.users.create' }" :icon="icons.AddIcon"></default-button>-->
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
        <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">
          <tr v-for="(user,index) in crewDocumentRenewSchedules.data" :key="index">
            <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
            <td>Passport</td>
            <td>20</td>
            <td>{{ user?.name }}</td>
            <td>015225522005</td>
            <td>{{ user?.email }}</td>
            <td>
              <strong>{{ user?.business_unit }}</strong>
            </td>
            <td>
<!--              <nobr>-->
<!--                <action-button :action="'edit'" :to="{ name: 'administration.users.edit', params: { userId: user?.id } }"></action-button>-->
<!--                <action-button @click="confirmDelete(user?.id)" :action="'delete'"></action-button>-->
<!--              </nobr>-->
            </td>
          </tr>
<!--          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && users?.data?.length"></LoaderComponent>-->
          </tbody>
<!--          <tfoot v-if="!users?.data?.length" class="relative h-[250px]">-->
<!--          <tr v-if="isLoading">-->
<!--            &lt;!&ndash; <td colspan="6">Loading...</td> &ndash;&gt;-->
<!--          </tr>-->
<!--          <tr v-else-if="isTableLoading">-->
<!--              <td colspan="8">-->
<!--                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                -->
<!--              </td>-->
<!--          </tr>-->
<!--          <tr v-else-if="!users?.data?.length">-->
<!--            <td colspan="8">No user found.</td>-->
<!--          </tr>-->
<!--          </tfoot>-->
      </table>
    </div>
<!--    <Paginate :data="users" to="administration.users.index" :page="page"></Paginate>-->
  </div>
</template>