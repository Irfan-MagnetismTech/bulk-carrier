<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useUser from "../../../composables/administration/useUser";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { users, getUsers, deleteUser, isLoading } = useUser();
const { setTitle } = Title();
setTitle('User List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;


function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this user!",
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

onMounted(() => {
  watchEffect(() => {
  getUsers(props.page)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching users:", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">User List</h2>
    <default-button :title="'Create User'" :to="{ name: 'administration.users.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
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
            <th>User Role</th>
            <th class="w-1/4 md:w-64">Name</th>
            <th class="w-64">Email</th>
            <th>Business Unit</th>
            <th class="w-68">Action</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(user,index) in users" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ user?.roles }}</td>
            <td>{{ user?.name }}</td>
            <td>{{ user?.email }}</td>
            <td>
              <strong>{{ user?.business_unit }}</strong>
            </td>
            <td>
              <action-button :action="'edit'" :to="{ name: 'administration.users.edit', params: { userId: user?.id } }"></action-button>
              <action-button @click="confirmDelete(user?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!users?.length">
          <tr v-if="isLoading">
            <td colspan="6">Loading...</td>
          </tr>
          <tr v-else-if="!users?.data?.length">
            <td colspan="6">No user found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="users" to="administration.users.index" :page="page"></Paginate>
  </div>
</template>