<script setup>
import Error from "../Error.vue";
import {onMounted,watch} from "vue";
import usePermission from "../../composables/administration/usePermission";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
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

function inputCheckBymenu2(permissionMenuKey){
  Object.entries(props.form.permissions[permissionMenuKey]).forEach(([permissionSubjectKey, permissionSubjectData]) => {
    if(props.form.permissions[permissionMenuKey].is_checked){
      props.form.permissions[permissionMenuKey][permissionSubjectKey].is_checked = true;
    } else {
      props.form.permissions[permissionMenuKey][permissionSubjectKey].is_checked = false;
    }

    Object.entries(props.form.permissions[permissionMenuKey][permissionSubjectKey]).forEach(([permissionIndex, permissionData]) => {
      if (permissionIndex !== 'is_checked') {
        if(props.form.permissions[permissionMenuKey].is_checked){
          props.form.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked = true;
        } else{
          props.form.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked = false;
        }
      }
    });
  });
}

function inputCheckedByMenu(permissionMenuKey) {

  console.log("Permission " + permissionMenuKey);

  const sum = Object.values(props.form.permissions[permissionMenuKey]).reduce((sum, obj) => {
    //console.log("ASAS " , obj)
    // return sum + obj.property;
    if(obj){
      if(!obj.is_checked){
        return sum + 1;
      } else{
        return sum + 0;
      }
    } else{
      return sum + 0;
    }
  },0);

  if(sum === 0){
    props.form.permissions[permissionMenuKey].is_checked = true;
  } else {
    props.form.permissions[permissionMenuKey].is_checked = false;
  }

}

function inputCheckedBySubject2(permissionMenuKey, permissionSubjectKey)
{
  Object.entries(props.form.permissions[permissionMenuKey][permissionSubjectKey]).forEach(([permissionIndex, permissionData]) => {
    if (permissionIndex !== 'is_checked') {
      if(props.form.permissions[permissionMenuKey][permissionSubjectKey].is_checked){
        props.form.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked = true;
      } else {
        props.form.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked = false;
      }
    }
  });
  inputCheckedByMenu(permissionMenuKey);
}
function inputCheckedBySubject(permissionMenuKey, permissionSubjectKey) {
  Object.entries(props.form.permissions[permissionMenuKey][permissionSubjectKey]).forEach(([permissionIndex, permissionData]) => {
    if (permissionIndex !== 'is_checked') {
      props.form.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked = !props.form.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked;
    }
  });

  const sum = Object.values(props.form.permissions[permissionMenuKey][permissionSubjectKey]).reduce((sum, obj) => {
    // return sum + obj.property;
    if(obj.name){
      if(!obj.is_checked){
        return sum + 1;
      } else{
        return sum + 0;
      }
    } else{
      return sum + 0;
    }
  },0);

  if(sum === 0){
    props.form.permissions[permissionMenuKey][permissionSubjectKey].is_checked = true;
  } else {
    props.form.permissions[permissionMenuKey][permissionSubjectKey].is_checked = false;
  }

  inputCheckedByMenu(permissionMenuKey);

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
      <span class="text-gray-700 ">Role Name <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.name" required placeholder="Role Name" class="block w-full mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  form-input" />
    </label>
  </div>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded ">
    <legend class="px-2 text-gray-700 ">Permissions <span class="text-red-500">*</span></legend>
    <div class="mt-2">
      <div :id="'menu_' + menuIndex" :class="menuIndex%2===0 ? 'bg-gray-100' : 'bg-yellow-100'" style="position: relative" class="px-2 py-2 border sm:rounded-lg mb-1" v-for="(permissionMenuData,permissionMenuKey,menuIndex) in form?.permissions" :key="menuIndex">
        <label class="flex items-center mb-2 text-gray-600 ">
          <input @change="inputCheckBymenu2(permissionMenuKey)" v-model="form.permissions[permissionMenuKey].is_checked" type="checkbox" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple ">
          <span class="ml-2 font-bold">{{ permissionMenuKey }}</span>
        </label>
        <template v-for="(permissionSubjectData,permissionSubjectKey,subjectIndex) in form?.permissions[permissionMenuKey]" :key="subjectIndex">
          <label class="flex ml-6 items-center mb-2 text-gray-600 " v-if="permissionSubjectKey!=='is_checked'">
            <input @change="inputCheckedBySubject2(permissionMenuKey,permissionSubjectKey)" type="checkbox" :class="'menu_' + menuIndex" v-model="form.permissions[permissionMenuKey][permissionSubjectKey].is_checked" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple ">
            <span class="ml-2 font-bold">{{ permissionSubjectKey }}</span>
          </label>
          <div class="inline-block mb-2 ml-12">
            <template v-for="(permissionData,permissionIndex) in form?.permissions[permissionMenuKey][permissionSubjectKey]" :key="permissionIndex">
              <label @change="inputCheckedByPermission(permissionMenuKey,permissionSubjectKey,permissionIndex)" v-if="permissionData?.name" class="flex flex-column items-center text-gray-600  ml-2 break-words">
                <input type="checkbox" :class="'menu_' + menuIndex,'permission_' + subjectIndex" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple " v-model="form.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked" :value="permissionData?.id">
                <span class="ml-1 font-normal">{{ permissionData?.name }}</span>
              </label>
            </template>
          </div>
        </template>
      </div>
    </div>
  </fieldset>
  <ErrorComponent :errors="errors"></ErrorComponent>
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
  @apply text-gray-700 ;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed ;
}
.form-input {
  @apply block mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple ;
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