<script setup>
import Error from "../Error.vue";
import {onMounted,watch} from "vue";
import usePermission from "../../composables/administration/usePermission";
const { permissions, getPermissions } = usePermission();

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  page: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

function inputCheckedByMenu(permissionMenuKey) {
  Object.entries(props.form.permissions[permissionMenuKey]).forEach(([permissionSubjectKey, permissionSubjectData]) => {
    props.form.permissions[permissionMenuKey][permissionSubjectKey].is_checked = !props.form.permissions[permissionMenuKey][permissionSubjectKey].is_checked;
    Object.entries(props.form.permissions[permissionMenuKey][permissionSubjectKey]).forEach(([permissionIndex, permissionData]) => {
      if (permissionIndex !== 'is_checked') {
        props.form.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked = !props.form.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked;
      }
    });
  });
}

function inputCheckedBySubject(permissionMenuKey, permissionSubjectKey) {
  Object.entries(props.form.permissions[permissionMenuKey][permissionSubjectKey]).forEach(([permissionIndex, permissionData]) => {
    if (permissionIndex !== 'is_checked') {
      props.form.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked = !props.form.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked;
    }
  });
}

function inputCheckedByPermission(permissionMenuKey,permissionSubjectKey,permissionIndex) {
  Object.entries(props.form.permissions[permissionMenuKey][permissionSubjectKey]).forEach(([permissionIndex, permissionData]) => {
    if (permissionIndex !== 'is_checked') {
      props.form.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked = !props.form.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked;
    }
  });
  inputCheckedBySubject(permissionMenuKey,permissionSubjectKey);
}

</script>

<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Role Name <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.name" required placeholder="Role Name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.name" :errors="errors.name" />
    </label>
  </div>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Permissions <span class="text-red-500">*</span></legend>
    <div class="mt-2">
      <div :id="'menu_' + menuIndex" :class="menuIndex%2===0 ? 'bg-gray-100' : 'bg-yellow-100'" style="position: relative" class="px-2 py-2 border sm:rounded-lg mb-1" v-for="(permissionMenuData,permissionMenuKey,menuIndex) in form?.permissions" :key="menuIndex">
        <label class="flex items-center mb-2 text-gray-600 dark:text-gray-400">
          <input @change="inputCheckedByMenu(permissionMenuKey)" v-model="form.permissions[permissionMenuKey].is_checked" type="checkbox" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
          <span class="ml-2 font-bold">{{ permissionMenuKey }}</span>
        </label>
        <template v-for="(permissionSubjectData,permissionSubjectKey,subjectIndex) in form?.permissions[permissionMenuKey]" :key="subjectIndex">
          <label class="flex ml-6 items-center mb-2 text-gray-600 dark:text-gray-400" v-if="permissionSubjectKey!=='is_checked'">
            <input @change="inputCheckedBySubject(permissionMenuKey,permissionSubjectKey)" type="checkbox" :class="'menu_' + menuIndex" v-model="form.permissions[permissionMenuKey][permissionSubjectKey].is_checked" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <span class="ml-2 font-bold">{{ permissionSubjectKey }}</span>
          </label>
          <div class="inline-block mb-2 ml-12">
            <template v-for="(permissionData,permissionIndex) in form?.permissions[permissionMenuKey][permissionSubjectKey]" :key="permissionIndex">
              <label @change="inputCheckedByPermission(permissionMenuKey,permissionSubjectKey,permissionIndex)" v-if="permissionData?.name" class="flex flex-column items-center text-gray-600 dark:text-gray-400 ml-2 break-words">
                <input type="checkbox" :class="'menu_' + menuIndex,'permission_' + subjectIndex" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" v-model="form.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked" :value="permissionData?.id">
                <span class="ml-1 font-normal">{{ permissionData?.name }}</span>
              </label>
            </template>
          </div>
        </template>
      </div>
    </div>
  </fieldset>
</template>

<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
>>> {
  --vs-controls-color: #374151;
  --vs-border-color: #4b5563;

  --vs-dropdown-bg: #282c34;
  --vs-dropdown-color: #eeeeee;
  --vs-dropdown-option-color: #eeeeee;

  --vs-selected-bg: #664cc3;
  --vs-selected-color: #374151;

  --vs-search-input-color: #4b5563;

  --vs-dropdown-option--active-bg: #664cc3;
  --vs-dropdown-option--active-color: #eeeeee;
}
</style>