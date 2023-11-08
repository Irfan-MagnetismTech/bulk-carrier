<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import useVoyageBoatNote from '../../../composables/operations/useVoyageBoatNote';
import Store from "../../../store";


const { voyageBoatNotes, getVoyageBoatNotes, deleteVoyageBoatNote, isLoading } = useVoyageBoatNote();
const icons = useHeroIcon();
const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Voyage Boat Note List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);


function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete this data!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
        deleteVoyageBoatNote(id);
    }
  })
}

onMounted(() => {
  watchEffect(() => {
    getVoyageBoatNotes(props.page, businessUnit.value)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching data.", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Voyage Boat Note List</h2>
    <default-button :title="'Create Voyage Boat Note'" :to="{ name: 'ops.voyage-boat-notes.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <filter-with-business-unit v-model="businessUnit"></filter-with-business-unit>

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
            <th>Contract Type</th>
            <th>Charterer Name</th>
            <th>Charterer Short Code</th>
            <th>Country</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody v-if="voyageBoatNotes?.data?.length">
              <tr v-for="(voyageBoatNote, index) in voyageBoatNotes.data" :key="voyageBoatNote?.id">
                  <td>{{ voyageBoatNotes.from + index }}</td>
                  <td>{{ voyageBoatNote?.contract_type }}</td>
                  <td>{{ voyageBoatNote?.opsChartererProfile?.name }}</td>
                  <td>{{ voyageBoatNote?.opsChartererProfile?.owner_code }}</td>
                  <td>{{ voyageBoatNote?.country }}</td>
                  <td>{{ voyageBoatNote?.email }}</td>
                  <td>{{ voyageBoatNote?.contact_no }}</td>
                  <td class="items-center justify-center space-x-2 text-gray-600">
                      <action-button :action="'show'" :to="{ name: 'ops.voyage-boat-notes.show', params: { voyageBoatNoteId: voyageBoatNote.id } }"></action-button>
                      <action-button :action="'edit'" :to="{ name: 'ops.voyage-boat-notes.edit', params: { voyageBoatNoteId: voyageBoatNote.id } }"></action-button>
                      <action-button @click="confirmDelete(voyageBoatNote.id)" :action="'delete'"></action-button>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
              </tr>
          </tbody>
          
          <tfoot v-if="!voyageBoatNotes?.length">
          <tr v-if="isLoading">
            <td colspan="8">Loading...</td>
          </tr>
          <tr v-else-if="!voyageBoatNotes?.data?.length">
            <td colspan="8">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="voyageBoatNotes" to="ops.voyage-boat-notes.index" :page="page"></Paginate>
  </div>
</template>
