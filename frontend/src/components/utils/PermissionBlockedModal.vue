<script>
export default {
  //define props
  props: {
    permissionBlockedModal: {
      type: Boolean,
      default: false,
    },
    message:{
      type: String,
      default: 'Sorry! We cann\'t process the request',
    }
  },
  emits: ['close-permission-blocked-modal'],
  data() {
    return {
      permissionBlockedModal: false,
    };
  },
  methods: {
    toggleStatus() {
      this.$emit('close-permission-blocked-modal', this.permissionBlockedModal);
    },
  },
};
</script>
<template>
  <div class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <div class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg  sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
      <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
      <header>
        <p class="text-lg font-semibold text-white ">
          Request Denied
        </p>
      </header>
      <!-- Modal body -->
      <div class="">
        <!-- Modal title -->
        <!-- Modal description -->
        <p class="text-sm text-red-500  font-bold mb-4 mt-5">
          Sorry! We cann't process the request.
          {{ this.message }}
        </p>
      </div>
      <footer id="modal-footer" style="text-align: center" class="px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 ">
        <button @click="toggleStatus" class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          OK
        </button>
      </footer>
    </div>
  </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
    .wrapper {
        @apply grid px-4 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t  bg-gray-50 sm:grid-cols-9  ;
    }
    .paginate-btn {
        @apply px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple;
    }
    .paginate-active {
        @apply text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600;
    }

    #modal{
      background-color: #312d2d;
      max-width: 450px;
    }
  #modal-footer{
      background-color: #efebeb;
    }
}
</style>