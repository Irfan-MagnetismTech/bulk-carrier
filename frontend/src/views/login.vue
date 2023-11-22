<template>
  <div id="login_page_body" class="flex items-center justify-center min-h-screen p-6 bg-gray-50 dark-disabled:bg-gray-900">
    <div class="overflow-hidden bg-white rounded-lg shadow-xl dark-disabled:bg-gray-800 w-full md:w-1/3" style="z-index: 999;">
      <div class="flex items-center justify-center">
        <img aria-hidden="true" class="object-cover align-middle py-4 w-2/4" src="/torony-small-logo.png" alt="Torony">
      </div>
      <div class="flex flex-col overflow-y-auto md:flex-row">
        <div class="flex items-center justify-center p-6 sm:p-8 w-full">
          <div class="w-full">
            <form class="" @submit.prevent="login(user)">
              <div>
                <label class="block mb-1 text-sm leading-relaxed tracking-tight text-gray-700 dark-disabled:text-gray-100">Email Address</label>
                <input type="email" v-model="user.email" required placeholder="Your Email" class="w-full px-4 py-2 transition duration-300 ease-in-out transform bg-gray-100 border-transparent rounded-md dark-disabled:text-gray-100 dark-disabled:bg-gray-600 dark-disabled:focus:border-gray-600 dark-disabled:focus:bg-gray-500 focus:ring-blue-600 focus:outline-none" />
              </div>
              <div class="mt-4">
                <label class="block mb-1 text-sm leading-relaxed tracking-tight text-gray-700 dark-disabled:text-gray-100">Password</label>
                <input type="password" v-model="user.password" required minlength="6" placeholder="Your Password" class="w-full px-4 py-2 transition duration-300 ease-in-out transform bg-gray-100 border-transparent rounded-md dark-disabled:text-gray-100 dark-disabled:bg-gray-600 dark-disabled:focus:border-gray-600 dark-disabled:focus:bg-gray-500 focus:ring-blue-600 focus:outline-none" />
              </div>
              <button type="submit" :disabled="isLoading" class="inline-flex items-center justify-center flex-none w-full px-4 py-2 mt-6 text-sm font-semibold leading-6 text-gray-100 transition-colors duration-300 bg-gray-900 border border-transparent rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-600 focus:outline-none">
                <span v-if="!isLoading">Login</span>
                <svg v-else-if="isLoading" class="iconify iconify--eos-icons w-6 h-6" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                  <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z" opacity=".5" fill="currentColor" />
                  <path d="M20 12h2A10 10 0 0 0 12 2v2a8 8 0 0 1 8 8z" fill="currentColor">
                    <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite" />
                  </path>
                </svg>
              </button>
            </form>
            <backend-error :errors="errors"></backend-error>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="absolute inset-y-0 flex items-center pl-2 group_logo">
    <img aria-hidden="true" class="object-cover w-full align-middle" src="/b-group.png" alt="Office">
  </div>
  <div class="overlay_div"></div>
</template>

<script setup>
import { ref } from "vue";
import BackendError from "../components/BackendError.vue";
import useAuth from "../services/auth";

const user = ref({ email: "", password: "", shift: "A" })
const { login, isLoading, errors } = useAuth();
import Title from "../services/title";

const { setTitle } = Title();

setTitle('Login');
</script>