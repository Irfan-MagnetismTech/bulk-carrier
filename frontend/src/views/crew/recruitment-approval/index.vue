<script setup>
import {onMounted, ref, watchEffect, watch} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useRecruitmentApproval from "../../../composables/crew/useRecruitmentApproval";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from "../../../store";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import {useRouter} from "vue-router/dist/vue-router";
const icons = useHeroIcon();
const router = useRouter();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { recruitmentApprovals, getRecruitmentApprovals, deleteRecruitmentApproval, isLoading } = useRecruitmentApproval();
const { setTitle } = Title();
setTitle('Recruitment Approval');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);


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
      deleteRecruitmentApproval(id);
    }
  })
}

watch(
    () => businessUnit.value,
    (newBusinessUnit, oldBusinessUnit) => {
      if (newBusinessUnit !== oldBusinessUnit) {
        router.push({ name: "crw.recruitmentApprovals.index", query: { page: 1 } })
      }
    }
);

onMounted(() => {
  watchEffect(() => {
  getRecruitmentApprovals(props.page,businessUnit.value)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching ranks:", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Recruitment Approval List</h2>
    <default-button :title="'Create Item'" :to="{ name: 'crw.recruitmentApprovals.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <filter-with-business-unit v-model="businessUnit"></filter-with-business-unit>
    <!-- Search -->
    <div class="relative w-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
      </svg>
      <input type="text" placeholder="Search..." class="search" />
    </div>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
          <thead v-once>
          <tr class="w-full">
            <th>#</th>
            <th>Applied Date</th>
            <th>Page Title</th>
            <th>Subject</th>
            <th>Business Unit</th>
            <th>Approved</th>
            <th>Agreed to Join</th>
            <th>Selected</th>
            <th>Panel</th>
            <th>Rest</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(rcrApproval,index) in recruitmentApprovals?.data" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ rcrApproval?.applied_date }}</td>
            <td>{{ rcrApproval?.page_title }}</td>
            <td>{{ rcrApproval?.subject }}</td>
            <td>
              <span :class="rcrApproval?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ rcrApproval?.business_unit }}</span>
            </td>
            <td>{{ rcrApproval?.total_approved }}</td>
            <td>{{ rcrApproval?.crew_agreed_to_join }}</td>
            <td>{{ rcrApproval?.crew_selected }}</td>
            <td>{{ rcrApproval?.crew_panel }}</td>
            <td>{{ rcrApproval?.crew_rest }}</td>
            <td>
              <action-button :action="'edit'" :to="{ name: 'crw.recruitmentApprovals.edit', params: { recruitmentApprovalId: rcrApproval?.id } }"></action-button>
              <action-button @click="confirmDelete(rcrApproval?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!recruitmentApprovals?.data?.length">
          <tr v-if="isLoading">
            <td colspan="11">Loading...</td>
          </tr>
          <tr v-else-if="!recruitmentApprovals?.data?.length">
            <td colspan="11">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="recruitmentApprovals" to="crw.recruitmentApprovals.index" :page="page"></Paginate>
  </div>
</template>